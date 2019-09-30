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
 * Add template files
 */
\Contao\TemplateLoader::addFiles
(
    array
    (
        'be_member_import'          => 'system/modules/member-import/templates/backend',
        'be_member_import_analysis' => 'system/modules/member-import/templates/backend',
        'be_member_import_load'     => 'system/modules/member-import/templates/backend',
        'be_member_import_prepare'  => 'system/modules/member-import/templates/backend',
        'be_member_import_import'   => 'system/modules/member-import/templates/backend',
    )
);