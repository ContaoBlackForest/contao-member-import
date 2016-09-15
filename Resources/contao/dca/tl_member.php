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

/** @see tl_member */

/**
 * Add global operations button
 */
$globalOperations = array(
    'member_import' => array
    (
        'label'           => &$GLOBALS['TL_LANG']['MSC']['member_import'],
        'href'            => 'act=select',
        'class'           => 'header_css_import',
        'button_callback' => array('ContaoBlackForest\Member\Import\DataContainer\Table\Member', 'injectImportMenu')
    )
);

$GLOBALS['TL_DCA']['tl_member']['list']['global_operations'] =
    array_merge($globalOperations, $GLOBALS['TL_DCA']['tl_member']['list']['global_operations']);
