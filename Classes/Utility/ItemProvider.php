<?php
declare(strict_types = 1);

/***
 *
 * This file is part of the "Teaser Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2018 Christian Fries <christian.fries@lst.team>
 *
 ***/

namespace LST\TeaserManager\Utility;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ItemProvider implements SingletonInterface
{
    /**
     * Get available items for a certain page for a certain property
     *
     * @param int $pageUid
     * @param string $propertyName The name of the property go get items for
     * @param string $pluginName The name of the list type starting with tx_ and ending with .
     * @return array
     */
    public function getAvailableItems($pageUid, $propertyName, $pluginName)
    {
        $items = [];

        // Check if the property is extended by ext_tables
        if (isset($GLOBALS['TYPO3_CONF_VARS']['EXT']['teaser_manager'][$propertyName]) && is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['teaser_manager'][$propertyName])) {
            $items = $GLOBALS['TYPO3_CONF_VARS']['EXT']['teaser_manager'][$propertyName];
        }

        // Add TsConfig values
        foreach ($this->getItemsFromTsConfig($pageUid, $propertyName, $pluginName) as $key => $title) {
            if (GeneralUtility::isFirstPartOfStr($title, '--div--')) {
                $optGroupParts = GeneralUtility::trimExplode(',', $title, true, 2);
                $title = $optGroupParts[1];
                $key = $optGroupParts[0];
            }
            $items[] = [$title, $key];
        }

        return $items;
    }

    /**
     * Get items defined in TsConfig
     *
     * @param int $pageUid
     * @param string $propertyName The name of the property to get items for
     * @param string $pluginName The name of the list type starting with tx_ and ending with .
     * @return array
     */
    protected function getItemsFromTsConfig($pageUid, $propertyName, $pluginName)
    {
        $items = [];
        $pagesTsConfig = BackendUtility::getPagesTSconfig($pageUid);
        if (isset($pagesTsConfig[$pluginName][$propertyName . '.']) && is_array($pagesTsConfig[$pluginName][$propertyName . '.'])) {
            $items = $pagesTsConfig[$pluginName][$propertyName . '.'];
        }
        return $items;
    }
}
