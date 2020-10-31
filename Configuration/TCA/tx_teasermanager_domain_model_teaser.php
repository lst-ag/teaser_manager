<?php
defined('TYPO3_MODE') or die();

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser',
        'label' => 'name',
        'label_alt' => 'title, subtitle',
        'label_alt_force' => true,
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'name, title,subtitle,link,text,date,icon,selected_icon,style,image,type,',
        'iconfile' => 'EXT:teaser_manager/Resources/Public/Icons/teaser.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'type, name, title, subtitle, link_text, link, text, date, icon, selected_icon, style, image, images, size, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, sys_language_uid, l10n_parent, l10n_diffsource, hidden, starttime, endtime'],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'special' => 'languages',
                'default' => 0,
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_teasermanager_domain_model_teaser',
                'foreign_table_where' => 'AND tx_teasermanager_domain_model_teaser.pid=###CURRENT_PID### AND tx_teasermanager_domain_model_teaser.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'default' => 0,
            ],
        ],
        'starttime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:starttime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:endtime_formlabel',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'name' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.name',
            'displayCond' => 'FIELD:type:REQ:true',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],

        ],

        'title' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.title',
            'displayCond' => 'FIELD:type:REQ:true',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],

        ],
        'subtitle' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.subtitle',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:subtitle',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],

        ],
        'link_text' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.link_text',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:link_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],

        ],
        'link' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.link',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:link',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputLink',
                'size' => 30,
                'eval' => 'trim',
                'softref' => 'typolink',
            ],
        ],
        'text' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.text',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:text',
            'config' => [
                'cols' => 40,
                'enableRichtext' => 1,
                'eval' => 'trim',
                'richtextConfiguration' => 'default',
                'rows' => 15,
                'type' => 'text',
            ],
        ],
        'date' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.date',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 10,
                'eval' => 'datetime',
                'checkbox' => 1,
                'default' => time()
            ],
        ],
        'icon' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.icon',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:icon',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],

        ],
        'selected_icon' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.selected_icon',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:selected_icon',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', '']
                ],
                'itemsProcFunc' => 'LST\TeaserManager\Hook\ItemsProcFunc->icons'
            ],

        ],
        'style' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.style',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:style',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', '']
                ],
                'itemsProcFunc' => 'LST\TeaserManager\Hook\ItemsProcFunc->style'
            ],

        ],
        'image' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.image',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:image',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('image', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ]
                    ],
                    'maxitems' => 1,
                ],
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']),
        ],
        'images' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.images',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:images',
            'config' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig('images', [
                'appearance' => [
                    'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
                ],
                // custom configuration for displaying fields in the overlay/reference table
                // to use the imageoverlayPalette instead of the basicoverlayPalette
                'overrideChildTca' => [
                    'types' => [
                        '0' => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                            'showitem' => '
                                --palette--;;audioOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                            'showitem' => '
                                --palette--;;videoOverlayPalette,
                                --palette--;;filePalette'
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                            'showitem' => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette'
                        ]
                    ],
                ],
            ], $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'])

        ],
        'size' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.size',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:size',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [],
                'itemsProcFunc' => 'LST\TeaserManager\Hook\ItemsProcFunc->sizes'
            ],

        ],
        'type' => [
            'exclude' => 0,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.type',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.type.choose', '']
                ],
                'foreign_table' => 'tx_teasermanager_domain_model_teasertype',
                'minitems' => 1,
                'maxitems' => 1,
                'eval' => 'required',
            ],
        ],

    ],
];
