<?php

if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:teaser_manager/Configuration/TsConfig/Page/ContentElementWizard.tsconfig">');

/**
 * Hook to show PluginInformation under a tt_content element in page module of type teaser_manager
 */
$cmsLayout = 'cms/layout/class.tx_cms_layout.php';
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS'][$cmsLayout]['tt_content_drawItem']['teaser_manager'] = 'LST\TeaserManager\Hook\PluginPreview';
