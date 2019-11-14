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

use ContaoBlackForest\MemberImportBundle\Callback\DataContainer\Table\Member\BuildImportMenu;

/**
 * Add global operations button
 */

$GLOBALS['TL_DCA']['tl_member']['list']['global_operations'] = array_merge(
    [
        'member_import' => [
            'label'           => &$GLOBALS['TL_LANG']['MSC']['member_import'],
            'href'            => 'act=select',
            'class'           => 'header_css_import',
            'button_callback' => [BuildImportMenu::class, '__invoke']
        ]
    ],
    $GLOBALS['TL_DCA']['tl_member']['list']['global_operations']
);
