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

namespace ContaoBlackForest\Member\Import\DataContainer\Table;

use Contao\Backend;
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
}
