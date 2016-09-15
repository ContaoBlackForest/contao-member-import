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

use Contao\BackendTemplate;
use Contao\BaseTemplate;
use Contao\Input;
use Contao\System;

/**
 * The backend controller.
 */
class BackendController
{
    public function replaceHeadlineName(BaseTemplate &$template)
    {
        if (Input::get('do') !== 'member_import'
            || $template->getName() !== 'be_main'
        ) {
            return;
        }

        $template->title = $GLOBALS['TL_LANG']['MOD']['member'][0] . ' Import';

        $template->headline = $GLOBALS['TL_LANG']['MOD']['member'][0] . ' Import';
    }

    /**
     * Generate backend module member import.
     *
     * @return string The output
     */
    public function generate()
    {
        System::loadLanguageFile('member_import');

        $template = new BackendTemplate('be_member_import');
        $buffer   = $template->parse();

        $analysisController = new AnalysisController();
        $buffer .= $analysisController->handle();

        $fileLoadController = new FileLoadController();
        $buffer .= $fileLoadController->handle();

        $prepareDataController = new PrepareDataController();
        $buffer .= $prepareDataController->handle();

        $importDataController = new ImportDataController();
        $buffer .= $importDataController->handle();

        return $buffer;
    }
}
