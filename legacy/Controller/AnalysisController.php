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

use Contao\Backend;
use Contao\BackendTemplate;
use Contao\Database;
use Contao\FilesModel;
use Contao\Input;
use Contao\Session;

/**
 * The analysis controller.
 */
class AnalysisController
{
    /**
     * The import settings
     */
    protected $settings;

    /**
     * Handle the analysis for member import.
     *
     * @return string The template.
     */
    public function handle()
    {
        $template = new BackendTemplate('be_member_import_analysis');

        $this->collectSettings();

        $template->information = $this->settings;

        return $template->parse();
    }

    /**
     * Collect the import settings.
     *
     * @return void
     */
    public function collectSettings()
    {
        $session = Session::getInstance();

        if (Input::get('import') !== 'analysis') {
            $this->settings = $session->get('member-import-settings');

            return;
        }

        $dataBase = Database::getInstance();
        $result   = $dataBase->prepare('SELECT * FROM tl_member_import WHERE disable_import=?')
            ->execute(0);

        while ($result->next()) {
            if (!$source = $this->sourceExists($result->importSource)) {
                continue;
            }

            $result->importSource = $source;

            $result->translate_properties = unserialize($result->translate_properties);
            $result->groups               = unserialize($result->groups);

            $this->settings[] = $result->row();
        }

        $session->set('member-import-settings', $this->settings);

        $GLOBALS['TL_MOOTOOLS'][] =
            "<script>location.href = 'contao/main.php" .
            "?do=member_import&import=load&rt=" . REQUEST_TOKEN . "&ref=" . TL_REFERER_ID . "'</script>";
    }

    /**
     * Find the source file in file system.
     *
     * @param $uuid
     *
     * @return null|string Source exists.
     */
    protected function sourceExists($uuid)
    {
        $model = FilesModel::findByUuid($uuid);

        if (!file_exists(TL_ROOT . DIRECTORY_SEPARATOR . $model->path)) {
            return null;
        }

        return $model->path;
    }
}
