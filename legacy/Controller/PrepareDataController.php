<?php

/**
 * This file is part of contaoblackforest/contao-member-import.
 *
 * (c) 2016-2019 The Contao Blackforest team.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    contaoblackforest/contao-member-import
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  20116-2019 The Contao Blackforest team.
 * @license    https://github.com/contaoblackforest/contao-member-import/blob/master/LICENSE LGPL-3.0-or-later
 * @filesource
 */

namespace ContaoBlackForest\Member\Import\Controller;

use Contao\BackendTemplate;
use Contao\Database;
use Contao\Input;
use Contao\Session;
use ContaoBlackForest\Member\Import\Event\PostPrepareDataEvent;
use ContaoBlackForest\Member\Import\Event\PrePrepareDataEvent;

/**
 * The prepare dataController.
 */
class PrepareDataController
{
    /**
     * The import data.
     */
    protected $importData;

    /**
     * The prepared data.
     */
    protected $preparedData;

    /**
     * Handle the prepare data.
     *
     * @return string The Template.
     */
    public function handle()
    {
        if (in_array(Input::get('import'), array('analysis', 'load'), null)) {
            return '';
        }

        $template = new BackendTemplate('be_member_import_prepare');

        $this->getSession();

        $step  = $this->prepareData();
        $count = $this->countImportSetting();

        $this->injectScript($step);

        $template->step  = $step;
        $template->count = $count;

        return $template->parse();
    }

    /**
     * Prepare the import data
     *
     * @return integer The step.
     */
    protected function prepareData()
    {
        if (in_array(Input::get('import'), array('import', ''), null)) {
            return $this->countImportSetting();
        }

        if (count($this->importData) === (int)Input::get('step')) {
            return Input::get('step');
        }

        global $container;

        $eventDispatcher = $container['event-dispatcher'];

        $step = Input::get('step');
        if (!$step) {
            $step = 0;
        }

        $importData = $this->importData[$step];

        $prePrepareDataEvent = new PrePrepareDataEvent($eventDispatcher, $importData['data'], $importData['setting']);
        $eventDispatcher->dispatch(PrePrepareDataEvent::NAME, $prePrepareDataEvent);

        $postPrepareDataEvent = new PostPrepareDataEvent(
            $eventDispatcher,
            $prePrepareDataEvent->getImportData(),
            $prePrepareDataEvent->getSettings()
        );
        $eventDispatcher->dispatch(PostPrepareDataEvent::NAME, $postPrepareDataEvent);

        if (!$this->preparedData) {
            $this->preparedData = array();
        }

        $this->preparedData = array_merge($this->preparedData, $postPrepareDataEvent->getPreparedData());

        $this->setSession();

        return ++$step;
    }

    /**
     * Count import setting.
     *
     * @return integer
     */
    protected function countImportSetting()
    {
        $dataBase = Database::getInstance();
        $result = $dataBase->prepare('SELECT COUNT(*) AS count FROM tl_member_import WHERE disable_import=?')
            ->execute(0);

        return $result->count;
    }

    /**
     * Get the import settings from the session.
     *
     * @return void
     */
    protected function getSession()
    {
        $session = Session::getInstance();

        $this->importData   = $session->get('member-import-data');
        $this->preparedData = $session->get('member-import-prepare');
    }

    protected function setSession()
    {
        $session = Session::getInstance();

        #$session->set('member-import-data', $this->importData);
        $session->set('member-import-prepare', $this->preparedData);
    }

    /**
     * Inject the reload script.
     *
     * @param $step integer The step.
     *
     * @return void
     */
    protected function injectScript($step)
    {
        if (in_array(Input::get('import'), array('load', 'import'))) {
            return;
        }

        if (count($this->importData) === (int)Input::get('step')) {
            $GLOBALS['TL_MOOTOOLS'][] =
                "<script>location.href = 'contao/main.php" .
                "?do=member_import&import=import&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID .
                "'</script>";

            return;
        }

        $GLOBALS['TL_MOOTOOLS'][] =
            "<script>location.href = 'contao/main.php" .
            "?do=member_import&import=prepare&step=" . $step . "&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID .
            "'</script>";
    }
}
