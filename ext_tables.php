<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function ($extKey) {
        // Load extension configuration
        $settings = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class)->get('teaser_manager');
        $navigationComponent = (!$settings['globalStoragePid']) ? 'typo3-pagetree' : '';

        if (TYPO3_MODE === 'BE') {
            if ($settings['showAdministrationModule']) {
                \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
                    'LST.TeaserManager',
                    'web', // Make module a submodule of 'web'
                    'admin', // Submodule key
                    '', // Position
                    [
                        'Admin' => 'listTeaser, listTeaserType, listTeaserLayout',
                    ],
                    [
                        'access' => 'user,group',
                        'icon'   => 'EXT:' . $extKey . '/Resources/Public/Icons/teaser_manager.svg',
                        'labels' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_admin.xlf',
                        'navigationComponentId' => $navigationComponent,
                        'inheritNavigationComponentFromMainModule' => false,
                    ]
                );
            }
        }

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($extKey, 'Configuration/TypoScript', 'Teaser Manager');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_teasermanager_domain_model_teaser', 'EXT:teaser_manager/Resources/Private/Language/locallang_csh_tx_teasermanager_domain_model_teaser.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_teasermanager_domain_model_teaser');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_teasermanager_domain_model_teasertype', 'EXT:teaser_manager/Resources/Private/Language/locallang_csh_tx_teasermanager_domain_model_teasertype.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_teasermanager_domain_model_teasertype');

        if (!empty($settings['globalStoragePid'])) {
            \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
                'module.tx_teasermanager_web_teasermanageradmin.persistence.storagePid = ' . $settings['globalStoragePid'] . '
                plugin.tx_teasermanager.persistence.storagePid = ' . $settings['globalStoragePid']
            );
        }
    },
    'teaser_manager'
);
