<?php
defined('TYPO3_MODE') or die();

(function () {
    $columns = [
        'teaser_type' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.teaser_type',
            'onChange' => 'reload',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.teaser_type.choose', 0]
                ],
                'foreign_table' => 'tx_teasermanager_domain_model_teasertype',
                'minitems' => 1,
                'maxitems' => 1,
                'eval' => 'required,int',
                'disableNoMatchingValueElement' => 1,
            ],
        ],
        'teaser_layout' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.teaser_layout',
            'displayCond' => 'FIELD:teaser_type:REQ:true',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.teaser_layout.default', 0]
                ],
                'foreign_table' => 'tx_teasermanager_domain_model_teaserlayout',
                'foreign_table_where' => ' AND tx_teasermanager_domain_model_teaserlayout.uid IN ' .
                    '(SELECT uid_foreign FROM tx_teasermanager_teasertype_teaserlayout_mm WHERE uid_local=###REC_FIELD_teaser_type###) ',
                'minitems' => 1,
                'maxitems' => 1,
                'eval' => 'required,int',
                'default' => 0,
                'disableNoMatchingValueElement' => 1,
            ],
        ],
        'teaser_items' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.teaser_items',
            'displayCond' => 'FIELD:teaser_type:REQ:true',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectMultipleSideBySide',
                'foreign_table' => 'tx_teasermanager_domain_model_teaser',
                'foreign_table_where' => ' AND tx_teasermanager_domain_model_teaser.type = ###REC_FIELD_teaser_type### AND tx_teasermanager_domain_model_teaser.sys_language_uid = 0',
                'MM' => 'tx_teasermanager_ttcontent_teaser_mm',
                'size' => 10,
                'autoSizeMax' => 30,
                'maxitems' => 9999,
                'multiple' => 0,
                'eval' => 'int',
                'default' => 0,
                'enableMultiSelectFilterTextfield' => true
            ],
        ],
    ];

    $GLOBALS['TCA']['tt_content']['palettes']['teaser_type_palette'] = [
        'showitem' => 'teaser_type, teaser_layout, --linebreak--, teaser_items',
        'canNotCollapse' => 1
    ];

    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('tt_content', $columns);

    // Request update when changing teaser_type
    $requestUpdate = $GLOBALS['TCA']['tt_content']['ctrl']['requestUpdate'];
    $GLOBALS['TCA']['tt_content']['ctrl']['requestUpdate'] = !empty($requestUpdate) ? $GLOBALS['TCA']['tt_content']['ctrl']['requestUpdate'] . ',teaser_type' : '';

    // Add icon displayed in page module
    $GLOBALS['TCA']['tt_content']['ctrl']['typeicon_classes']['teasermanager_teaser'] = 'content-table';

    // Adds the content element to the "Type" dropdown
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
        [
            'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:tt_content.ce.title',
            'teasermanager_teaser',
            'content-table'
        ],
        'CType',
        'teaser_manager'
    );

    // Define fields to show
    $GLOBALS['TCA']['tt_content']['types']['teasermanager_teaser'] = $GLOBALS['TCA']['tt_content']['types'][1];
    $GLOBALS['TCA']['tt_content']['types']['teasermanager_teaser']['showitem'] = '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                        --palette--;LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser;teaser_type_palette,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                        --palette--;;language,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        --palette--;;hidden,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                        categories,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                        rowDescription';

    if (\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded('lst_fluid_styled_content')) {
        $GLOBALS['TCA']['tt_content']['types']['teasermanager_teaser']['showitem'] = '
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.general;general,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.headers;headers,
                        --palette--;LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaser;teaser_type_palette,
                    --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.appearance,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.frames;frames,
                        --palette--;LLL:EXT:lst_fluid_styled_content/Resources/Private/Language/locallang_db.xlf:palette.background_settings;background_settings,
                        --palette--;LLL:EXT:lst_fluid_styled_content/Resources/Private/Language/locallang_db.xlf:palette.dimension_settings;dimension_settings,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.appearanceLinks;appearanceLinks,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                        --palette--;;language,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                        --palette--;;hidden,
                        --palette--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:palette.access;access,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:categories,
                        categories,
                    --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:notes,
                        rowDescription';
    }
})();
