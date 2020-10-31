<?php
defined('TYPO3_MODE') or die();

(function ($extKey) {
    /*
     * Load extension configuration
     */
    $extensionConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(
        \TYPO3\CMS\Core\Configuration\ExtensionConfiguration::class
    )->get($extKey);

    /*
     * Register modules
     */
    $navigationComponent = (!$extensionConfiguration['globalStoragePid']) ? 'typo3-pagetree' : '';

    if ($extensionConfiguration['showAdministrationModule']) {
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

    /*
     * Configure tables
     */
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_teasermanager_domain_model_teaser');
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_teasermanager_domain_model_teasertype');

    /*
     * Set global storage pid if defined
     */
    if (!empty($extensionConfiguration['globalStoragePid'])) {
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
            'module.tx_teasermanager_web_teasermanageradmin.persistence.storagePid = ' . $extensionConfiguration['globalStoragePid'] . '
            plugin.tx_teasermanager.persistence.storagePid = ' . $extensionConfiguration['globalStoragePid']
        );
    }
})('teaser_manager');
