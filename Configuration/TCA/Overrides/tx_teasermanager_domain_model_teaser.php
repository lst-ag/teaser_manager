<?php

// Define TCA modifications only for TYPO3 8+ instances
if (\TYPO3\CMS\Core\Utility\VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 8007000) {
    // DateTime render type
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['starttime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['endtime']['config']['renderType'] = 'inputDateTime';
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['date']['config']['renderType'] = 'inputDateTime';

    // Link render type
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['link']['config']['renderType'] = 'inputLink';
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['link']['config']['wizards'] = [];
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['link']['config']['fieldControl'] = [
        'linkPopup' => [
            'options' => [
                'title' => 'LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.link',
                'windowOpenParameters' => 'width=800,height=600,status=0,menubar=0,scrollbars=1'
            ]
        ]
    ];

    // Add correct file palette to images field
    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['images']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'images',
        [
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
            ],
            'overrideChildTca' => [
                'types' => [
                    '0' => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ]
                ],
            ]
        ],
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
    );

    $GLOBALS['TCA']['tx_teasermanager_domain_model_teaser']['columns']['image']['config'] = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::getFileFieldTCAConfig(
        'image',
        [
            'appearance' => [
                'createNewRelationLinkTitle' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference'
            ],
            'maxitems' => 1,
            'overrideChildTca' => [
                'types' => [
                    '0' => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_TEXT => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_AUDIO => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.audioOverlayPalette;audioOverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_VIDEO => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.videoOverlayPalette;videoOverlayPalette,
                                    --palette--;;filePalette'
                    ],
                    \TYPO3\CMS\Core\Resource\File::FILETYPE_APPLICATION => [
                        'showitem' => '
                                    --palette--;LLL:EXT:lang/Resources/Private/Language/locallang_tca.xlf:sys_file_reference.imageoverlayPalette;imageoverlayPalette,
                                    --palette--;;filePalette'
                    ]
                ],
            ]
        ],
        $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext']
    );
}

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('color_manager')) {
    $colorField = [
        'color' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.color',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:color',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.color.choose', '']
                ],
                'foreign_table' => 'tx_colormanager_domain_model_color',
                'default' => ''
            ]
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_teasermanager_domain_model_teaser', $colorField);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_teasermanager_domain_model_teaser', 'color', '', 'after:date');
}

if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('people')) {
    $personFields = [
        'person' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.person',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:person',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.person.choose', '0']
                ],
                'foreign_table' => 'tx_people_domain_model_person',
                'foreign_table_where' => 'AND tx_people_domain_model_person.sys_language_uid IN (-1,0)',
                'maxitems' => 1
            ]
        ],
        'persons' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser.persons',
            'displayCond' => 'USER:LST\TeaserManager\Matcher\DisplayConditionMatcher->checkTeaserField:persons',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_people_domain_model_person',
                'foreign_table_where' => 'AND tx_people_domain_model_person.sys_language_uid IN (-1,0)',
                'MM' => 'tx_teasermanager_teasertype_teaserlayout_mm',
                'default' => '0'
            ]
        ]
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tx_teasermanager_domain_model_teaser', $personFields);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('tx_teasermanager_domain_model_teaser', 'person, persons', '', 'after:selected_icon');
}
