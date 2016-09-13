<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Table tl_member
 */
$GLOBALS['TL_DCA']['tl_member_import'] = array
(

    // Config
    'config'      => array
    (
        'dataContainer'    => 'Table',
        'enableVersioning' => true,
        'sql'              => array
        (
            'keys' => array
            (
                'id' => 'primary'
            )
        )
    ),

    // List
    'list'        => array
    (
        'sorting'           => array
        (
            'mode'   => 1,
            'fields' => array('title'),
            'flag'   => 1,
        ),
        'label'             => array
        (
            'fields' => array('title'),
            'format' => '%s'
        ),
        'global_operations' => array
        (
            'all' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"'
            )
        ),
        'operations'        => array
        (
            'edit'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['edit'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif'
            ),
            'copy'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['copy'],
                'href'  => 'act=copy',
                'icon'  => 'copy.gif'
            ),
            'delete' => array
            (
                'label'      => &$GLOBALS['TL_LANG']['tl_member_import']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] .
                                '\'))return false;Backend.getScrollOffset()"'
            ),
            'toggle' => array
            (
                'label'           => &$GLOBALS['TL_LANG']['tl_member_import']['toggle'],
                'icon'            => 'visible.gif',
                'attributes'      => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                'button_callback' => array(
                    'ContaoBlackForest\Member\Import\DataContainer\Table\MemberImport',
                    'toggleIcon'
                )
            ),
            'show'   => array
            (
                'label' => &$GLOBALS['TL_LANG']['tl_member_import']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif'
            )
        )
    ),

    // Palettes
    'palettes'    => array
    (
        '__selector__' => array('assignDir'),
        'default'      => '{title_legend},title;' .
                          '{import_legend},importSource,disable_import;' .
                          '{address_legend:hide},company,street,postal,city,state,country;' .
                          '{contact_legend},phone,fax,email,website,language;' .
                          '{groups_legend},groups;' .
                          '{login_legend},login;' .
                          '{homedir_legend:hide},assignDir;' .
                          '{account_legend},disable,start,stop',
    ),

    // Subpalettes
    'subpalettes' => array
    (
        'assignDir' => 'homeDir'
    ),


    // Fields
    'fields'      => array
    (
        'id'             => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
        'tstamp'         => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'title'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['title'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'importSource'   => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['importSource'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array(
                'filesOnly'  => true,
                'fieldType'  => 'radio',
                'mandatory'  => true,
                'tl_class'   => 'clr',
                'extensions' => 'csv'
            ),
            'sql'       => "binary(16) NULL"
        ),
        'company'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['company'],
            'exclude'   => true,
            'search'    => true,
            'sorting'   => true,
            'flag'      => 1,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'street'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['street'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'postal'         => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['postal'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 32, 'feGroup' => 'address', 'tl_class' => 'w50'),
            'sql'       => "varchar(32) NOT NULL default ''"
        ),
        'city'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['city'],
            'exclude'   => true,
            'filter'    => true,
            'search'    => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 255, 'feGroup' => 'address', 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'state'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['state'],
            'exclude'   => true,
            'sorting'   => true,
            'inputType' => 'text',
            'eval'      => array('maxlength' => 64, 'feGroup' => 'address', 'tl_class' => 'w50'),
            'sql'       => "varchar(64) NOT NULL default ''"
        ),
        'country'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['country'],
            'exclude'   => true,
            'filter'    => true,
            'sorting'   => true,
            'inputType' => 'select',
            'options'   => System::getCountries(),
            'eval'      => array(
                'includeBlankOption' => true,
                'chosen'             => true,
                'feGroup'            => 'address',
                'tl_class'           => 'w50'
            ),
            'sql'       => "varchar(2) NOT NULL default ''"
        ),
        'phone'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['phone'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'maxlength'      => 64,
                'rgxp'           => 'phone',
                'decodeEntities' => true,
                'feGroup'        => 'contact',
                'tl_class'       => 'w50'
            ),
            'sql'       => "varchar(64) NOT NULL default ''"
        ),
        'fax'            => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['fax'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array(
                'maxlength'      => 64,
                'rgxp'           => 'phone',
                'decodeEntities' => true,
                'feGroup'        => 'contact',
                'tl_class'       => 'w50'
            ),
            'sql'       => "varchar(64) NOT NULL default ''"
        ),
        'website'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['website'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'url', 'maxlength' => 255, 'feGroup' => 'contact', 'tl_class' => 'w50'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'language'       => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['language'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'select',
            'options'   => System::getLanguages(),
            'eval'      => array(
                'includeBlankOption' => true,
                'chosen'             => true,
                'rgxp'               => 'locale',
                'tl_class'           => 'w50'
            ),
            'sql'       => "varchar(5) NOT NULL default ''"
        ),
        'groups'         => array
        (
            'label'      => &$GLOBALS['TL_LANG']['tl_member_import']['groups'],
            'exclude'    => true,
            'filter'     => true,
            'inputType'  => 'checkboxWizard',
            'foreignKey' => 'tl_member_group.name',
            'eval'       => array('multiple' => true, 'feGroup' => 'login'),
            'sql'        => "blob NULL",
            'relation'   => array('type' => 'belongsToMany', 'load' => 'lazy')
        ),
        'login'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['login'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'assignDir'      => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['assignDir'],
            'exclude'   => true,
            'inputType' => 'checkbox',
            'eval'      => array('submitOnChange' => true),
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'homeDir'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['homeDir'],
            'exclude'   => true,
            'inputType' => 'fileTree',
            'eval'      => array('fieldType' => 'radio', 'tl_class' => 'clr'),
            'sql'       => "binary(16) NULL"
        ),
        'disable'        => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['disable'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ),
        'start'          => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['start'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'       => "varchar(10) NOT NULL default ''"
        ),
        'stop'           => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['stop'],
            'exclude'   => true,
            'inputType' => 'text',
            'eval'      => array('rgxp' => 'datim', 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'sql'       => "varchar(10) NOT NULL default ''"
        ),
        'disable_import' => array
        (
            'label'     => &$GLOBALS['TL_LANG']['tl_member_import']['disable_import'],
            'exclude'   => true,
            'filter'    => true,
            'inputType' => 'checkbox',
            'sql'       => "char(1) NOT NULL default ''"
        ),
    )
);
