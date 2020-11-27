<?php

namespace CHF\TeaserManager\Matcher;

/***
 *
 * This file is part of the "Teaser Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Christian Fries <hello@christian-fries.ch>, CF Webworks
 *
 ***/

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Decide whether or not to display a field based on the selected teaser type
 */
class DisplayConditionMatcher
{
    /**
     * @var Connection
     */
    protected $connection;

    public function __construct()
    {
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $this->connection = $connectionPool->getConnectionForTable('tx_teasermanager_domain_model_teasertype');
    }

    /**
     * @param array $params The parameters provided by the displayCond evaluation class
     * @param Object $ref
     * @return bool
     */
    public function checkTeaserField($params, $ref)
    {
        $fieldName = $params['conditionParameters'][0];
        $teaserTypeId = (int) $params['record']['type'][0];

        $queryBuilder = $this->connection->createQueryBuilder();
        $result = $queryBuilder
            ->select('fields')
            ->from('tx_teasermanager_domain_model_teasertype')
            ->where($queryBuilder->expr()->eq('uid', $teaserTypeId))
            ->execute()
            ->fetch()
        ;

        $fieldNamesToDisplay = explode(',', $result['fields']);
        if (in_array($fieldName, $fieldNamesToDisplay)) {
            return true;
        }
        return false;
    }
}
