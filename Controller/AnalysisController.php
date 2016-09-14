<?php

/**
 * Copyright © ContaoBlackForest
 *
 * @package   contao-member-import
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   GNU/LGPL
 * @copyright Copyright 2014-2016 ContaoBlackForest
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

            $this->settings[] = $result->row();
        }

        $session->set('member-import-settings', $this->settings);

        $GLOBALS['TL_MOOTOOLS'][] =
            "<script>location.href = '" . Backend::addToUrl("do=member_import&amp;import=load") . "'</script>";
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
