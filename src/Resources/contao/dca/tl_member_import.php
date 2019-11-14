<?php

use ContaoBlackForest\Member\Import\DataContainer\Table\MemberImport;

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
 * Table tl_member
 */
$GLOBALS['TL_DCA']['tl_member_import'] = [

    // Config
    'config'      => [
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => [
            'keys' => [
                'id' => 'primary'
            ]
        ],
        'backlink' => 'do=member'
    ],

    // List
    'list'        => [
        'sorting'           => [
            'mode'   => 1,
            'fields' => ['title'],
            'flag'   => 1,
        ],
        'label'             => [
            'fields' => ['title'],
            'format' => '%s'
        ],
        'global_operations' => [
            'all' => [
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            ]
        ],
        'operations'        => [
            'edit'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ],
            'copy'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ],
            'delete' => [
                'label'      => &$GLOBALS['TL_LANG']['tl_member_import']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] .
                                '\'))return false;Backend.getScrollOffset()"'
            ],
            'toggle' => [
                'label'           => &$GLOBALS['TL_LANG']['tl_member_import']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => [MemberImport::class, 'toggleIcon']
            ],
            'show'   => [
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            ]
        ]
    ],

    // Palettes
    'palettes'    => [
        '__selector__' => ['assignDir'],
        'default'      => '{title_legend},title;' .
                          '{import_legend},importSource,translate_properties,disable_import;' .
                          '{address_legend:hide},company,street,postal,city,state,country;' .
                          '{contact_legend},phone,fax,email,website,language;' .
                          '{groups_legend},groups;' .
                          '{login_legend},login;' .
                          '{homedir_legend:hide},assignDir;' .
                          '{account_legend},disable,start,stop',
    ],

    // Subpalettes
    'subpalettes' => [
        'assignDir' => 'homeDir'
    ],


    // Fields
    'fields'      => [
        'id'             => [
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ],
        'tstamp'         => [
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ],
        'title'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'importSource'   => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['importSource'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => [
                'filesOnly'  => true,
                'fieldType'  => 'radio',
                'mandatory'  => true,
                'tl_class'   => 'clr',
                'extensions' => 'csv'
            ],
            'sql'       => "binary(16) NULL"
        ],
        'company'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['company'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'street'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['street'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'postal'         => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['postal'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 32, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql'       => "varchar(32) NOT NULL default ''"
        ],
        'city'           => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['city'],
            'exclude'   => true,
            'filter'    => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'state'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['state'],
            'exclude'   => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => ['maxlength' => 64, 'feGroup' => 'address', 'tl_class' => 'w50'],
            'sql'       => "varchar(64) NOT NULL default ''"
        ],
        'country'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['country'],
            'exclude'   => true,
            'filter'    => true,
            'sorting'   => true,
            'inputType' => 'select',
            'options'   => System::getCountries(),
            'eval'      => [
                'includeBlankOption' => true,
                'chosen'             => true,
                'feGroup'            => 'address',
                'tl_class'           => 'w50'
            ],
            'sql'       => "varchar(2) NOT NULL default ''"
        ],
        'phone'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['phone'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => [
                'maxlength'      => 64,
                'rgxp'           => 'phone',
                'decodeEntities' => true,
                'feGroup'        => 'contact',
                'tl_class'       => 'w50'
            ],
            'sql'       => "varchar(64) NOT NULL default ''"
        ],
        'fax'            => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['fax'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => [
                'maxlength'      => 64,
                'rgxp'           => 'phone',
                'decodeEntities' => true,
                'feGroup'        => 'contact',
                'tl_class'       => 'w50'
            ],
            'sql'       => "varchar(64) NOT NULL default ''"
        ],
        'website'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['website'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'url', 'maxlength' => 255, 'feGroup' => 'contact', 'tl_class' => 'w50'],
            'sql'       => "varchar(255) NOT NULL default ''"
        ],
        'language'       => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['language'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'select',
            'options'   => System::getLanguages(),
            'eval'      => [
                'includeBlankOption' => true,
                'chosen'             => true,
                'rgxp'               => 'locale',
                'tl_class'           => 'w50'
            ],
            'sql'       => "varchar(5) NOT NULL default ''"
        ],
        'groups'         => [
            'label'      => &$GLOBALS['TL_LANG']['tl_member_import']['groups'],
            'exclude'    => true,
            'filter'     => true,
            'inputType'  => 'checkboxWizard',
            'foreignKey' => 'tl_member_group.name',
            'eval'       => ['multiple' => true, 'feGroup' => 'login'],
            'sql'        => "blob NULL",
            'relation'   => ['type' => 'belongsToMany', 'load' => 'lazy']
        ],
        'login'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['login'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'assignDir'      => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['assignDir'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => ['submitOnChange' => true],
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'homeDir'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['homeDir'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => ['fieldType' => 'radio', 'tl_class' => 'clr'],
            'sql'       => "binary(16) NULL"
        ],
        'disable'        => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['disable'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'start'          => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''"
        ],
        'stop'           => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => ['rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'],
            'sql'       => "varchar(10) NOT NULL default ''"
        ],
        'disable_import' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['disable_import'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ],
        'translate_properties' => [
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['translate_properties'],
            'exclude'   => true,
            'inputType' => 'multiColumnWizard',
            'eval'      => [
                'columnFields' => [
                    'property'    => [
                        'label'            => &$GLOBALS['TL_LANG']['tl_member_import']['translate_properties_property'],
                        'exclude'          => true,
                        'inputType'        => 'select',
                        'options_callback' => [MemberImport::class, 'getTranslationProperties'],
                        'eval'             => [
                            'includeBlankOption' => true,
                            'chosen'             => true,
                            'style'              => 'width:360px'
                        ],
                    ],
                    'translation' => [
                        'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['translate_properties_translation'],
                        'exclude'   => true,
                        'inputType' => 'text'
                    ],
                ]
            ],
            'sql'       => "blob NULL"
        ]
    ]
];
