<?php
defined('TYPO3_MODE') or die();

(function () {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('teaser_manager', 'Configuration/TypoScript', 'Teaser Manager');
})();
