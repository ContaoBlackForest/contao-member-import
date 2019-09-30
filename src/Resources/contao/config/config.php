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

/**
 * Add member import table.
 */
$GLOBALS['BE_MOD']['accounts']['member']['tables'][] = 'tl_member_import';

$GLOBALS['BE_MOD']['accounts']['member_import'] = array(
    'callback'   => 'ContaoBlackForest\Member\Import\Controller\BackendController',
);

/**
 * Replace title and headline.
 */
$GLOBALS['TL_HOOKS']['parseTemplate'][] =
    array('ContaoBlackForest\Member\Import\DataContainer\Table\MemberImport', 'replaceHeadlineName');
$GLOBALS['TL_HOOKS']['parseTemplate'][] =
    array('\ContaoBlackForest\Member\Import\Controller\BackendController', 'replaceHeadlineName');

$GLOBALS['TL_CSS']['member-import'] = 'assets/member-import/backend/style.css';
