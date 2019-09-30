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

namespace ContaoBlackForest\Member\Import\DataContainer\Table;

use Contao\Backend;
use Contao\BaseTemplate;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Image;
use Contao\Input;
use Contao\System;
use Contao\Versions;

/**
 * The data container class for member import.
 */
class MemberImport
{
    public function replaceHeadlineName(BaseTemplate &$template)
    {
        if (Input::get('do') !== 'member'
            || Input::get('table') !== 'tl_member_import'
            || $template->getName() !== 'be_main'
        ) {
            return;
        }

        if (!Input::get('act')) {
            $template->title .= ' &raquo;';

            $template->headline .= ' &raquo;';
        }

        $template->title .= ' Import';

        $template->headline .= ' Import';
    }

    /**
     * Return the toggle visibility button
     *
     * @param array  $row        The row information.
     *
     * @param string $href       The href.
     *
     * @param string $label      The label.
     *
     * @param string $title      The title.
     *
     * @param string $icon       The icon.
     *
     * @param string $attributes The Attributes.
     *
     * @return string
     */
    public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
    {
        if (strlen(Input::get('tid'))) {
            $this->toggleVisibility(Input::get('tid'), (Input::get('state') == 1), (@func_get_arg(12) ?: null));
            Controller::redirect(System::getReferer());
        }

        $href .= '&amp;tid=' . $row['id'] . '&amp;state=' . $row['disable_import'];

        if ($row['disable_import']) {
            $icon = 'invisible.gif';
        }

        return '<a href="' . Backend::addToUrl($href) . '" title="' . specialchars($title) . '"' . $attributes . '>'
               . Image::getHtml($icon, $label, 'data-state="' . ($row['disable_import'] ? 0 : 1) . '"') . '</a> ';
    }


    /**
     * Disable/enable import
     *
     * @param integer       $intId      The primary key.
     *
     * @param boolean       $blnVisible The visible state.
     *
     * @param DataContainer $dc         The DataContainer.
     *
     * @return void
     */
    public function toggleVisibility($intId, $blnVisible, DataContainer $dc = null)
    {
        // Set the ID and action
        Input::setGet('id', $intId);
        Input::setGet('act', 'toggle');

        if ($dc) {
            $dc->id = $intId;
        }

        $objVersions = new Versions('tl_member', $intId);
        $objVersions->initialize();

        $time = time();

        // Update the database
        $database = Database::getInstance();
        $database->prepare(
            "UPDATE tl_member_import SET tstamp=$time, disable_import='" . ($blnVisible ? '' : 1) . "' WHERE id=?"
        )
            ->execute($intId);

        $objVersions->create();
    }

    /**
     * Get the translation properties.
     *
     * @return array The options.
     */
    public function getTranslationProperties()
    {
        System::loadLanguageFile('tl_member');

        $dataBase = Database::getInstance();

        $options = array();
        foreach ($dataBase->getFieldNames('tl_member') as $fieldName) {
            if (!array_key_exists($fieldName, $GLOBALS['TL_LANG']['tl_member'])) {
                continue;
            }

            $options[$fieldName] = $GLOBALS['TL_LANG']['tl_member'][$fieldName][0] . ' (' . $fieldName . ')';
        }

        return $options;
    }
}
