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

/**
 * The data container class for member.
 */
class Member
{
    /**
     * Inject the import menu.
     *
     * @param $href string The href.
     *
     * @param $label string The label.
     *
     * @param $title string The title.
     *
     * @param $class string The class.
     *
     * @return string
     */
    public function injectImportMenu($href, $label, $title, $class)
    {
        $GLOBALS['TL_CSS']['member-import'] = 'assets/member-import/backend/style.css';

        return '<ul class="menu ' . $class . '">' .
               '<li>' . $label . '</li>' .
               '<ul>' .
               '<li>' . $this->generateSettingsButton() . '</li>' .
               '<li>' . $this->generateImportButton() . '</li>' .
               '</ul>' .
               '</li>' .
               '</ul>';
    }

    /**
     * Generate the settings button.
     *
     * @return string The settings button.
     */
    protected function generateSettingsButton()
    {
        return '<a class="navigation settings" href="' . Backend::addToUrl('do=member&amp;table=tl_member_import') . '">' .
               $GLOBALS['TL_LANG']['MSC']['member_import_settings'] .
               '</a>';
    }

    /**
     * Generate the import button.
     *
     * @return string The import button.
     */
    protected function generateImportButton()
    {
        return '<a class="navigation autoload" onclick="if(!confirm(\'' . $this->generateConfirmMessage() .
               '\'))return false;Backend.getScrollOffset()">' . $GLOBALS['TL_LANG']['MSC']['member_import_import'] .
               '</a>';
    }

    /**
     * Generate the confirm message.
     *
     * @return string The confirm message.
     */
    protected function generateConfirmMessage()
    {
        return $GLOBALS['TL_LANG']['MSC']['member_import_import_confirm'] . ' \n' .
               $this->generateImportInformation();
    }

    /**
     * Generate the import information.
     *
     * @return string The import information.
     */
    protected function generateImportInformation()
    {
        return '&bull; foo \n' .
               '&bull; bar';
    }
}
