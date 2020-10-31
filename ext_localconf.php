<?php
defined('TYPO3_MODE') or die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:teaser_manager/Configuration/TsConfig/Page/ContentElementWizard.tsconfig">');

    /*
     * Register backend preview
     */
    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['teaser_manager'] = 'LST\TeaserManager\Hook\PluginPreview';
})();
