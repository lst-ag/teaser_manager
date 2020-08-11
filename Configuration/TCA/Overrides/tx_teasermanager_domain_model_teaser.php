<?php

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
