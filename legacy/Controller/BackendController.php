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
