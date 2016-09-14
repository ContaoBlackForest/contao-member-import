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

$GLOBALS['BE_MOD']['accounts']['member']['tables'][] = 'tl_member_import';

/**
 * Replace title and headline.
 */
$GLOBALS['TL_HOOKS']['parseTemplate'][] =
    array('ContaoBlackForest\Member\Import\DataContainer\Table\MemberImport', 'replaceHeadlineName');
