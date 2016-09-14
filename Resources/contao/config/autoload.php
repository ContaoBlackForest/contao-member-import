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

/**
 * Add template files
 */
\Contao\TemplateLoader::addFiles
(
    array
    (
        'be_member_import'          => 'system/modules/member-import/templates/backend',
        'be_member_import_analysis' => 'system/modules/member-import/templates/backend'
    )
);
