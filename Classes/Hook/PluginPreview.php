<?php
declare(strict_types = 1);

/***
 *
 * This file is part of the "Teaser Manager" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2016 Christian Fries <christian.fries@lst.team>
 *
 ***/

namespace LST\TeaserManager\Hook;

use TYPO3\CMS\Backend\View\PageLayoutView;
use TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;

/**
 * Preview rendering for the teaser_manager page module
 */
class PluginPreview implements PageLayoutViewDrawItemHookInterface
{
    /**
     * @var array
     */
    protected $row = [];

    /**
     * @var string
     */
    protected $templatePathAndFile = 'EXT:teaser_manager/Resources/Private/Templates/Hook/PluginPreview.html';

    /**
     * Preprocesses the preview rendering of a content element
     *
     * @param PageLayoutView $parentObject Calling parent object
     * @param bool $drawItem Whether to draw the item using the default functionality
     * @param string $headerContent Header content
     * @param string $itemContent Item content
     * @param array $row Record row of tt_content
     */
    public function preProcess(
        PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        if ($row['CType'] === 'teasermanager_teaser') {
            $headerContent = '';
            $drawItem = false;
            $itemContent = $parentObject->linkEditContent($this->getPluginInformation($row), $row);
        }
    }

    /**
     * @param array $row
     * @return string
     */
    protected function getPluginInformation($row)
    {
        $standaloneView = new StandaloneView;
        $standaloneView->setTemplatePathAndFilename(GeneralUtility::getFileAbsFileName($this->templatePathAndFile));
        $standaloneView->assignMultiple(
            [
                'teaserType' => $this->readTeaserType($row['teaser_type']),
                'selectedTeasers' => $this->readSelectedTeasers($row['uid']),
            ]
        );
        return $standaloneView->render();
    }

    /**
     * @param int $teaserType
     * @return mixed
     */
    private function readTeaserType($teaserType)
    {
        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_teasermanager_domain_model_teasertype');
        $result = $queryBuilder
            ->select('title')
            ->from('tx_teasermanager_domain_model_teasertype')
            ->where($queryBuilder->expr()->eq('uid', $teaserType))
            ->execute()
            ->fetch();

        return $result['title'];
    }

    /**
     * @param int $contentElement
     * @return array
     */
    private function readSelectedTeasers($contentElement)
    {
        $teasers = [];

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_teasermanager_domain_model_teasertype');
        $statement = $queryBuilder
            ->select('title', 'name')
            ->from('tx_teasermanager_domain_model_teaser', 'teaser')
            ->join(
                'teaser',
                'tx_teasermanager_ttcontent_teaser_mm',
                'mm',
                $queryBuilder->expr()->eq(
                    'mm.uid_foreign',
                    $queryBuilder->quoteIdentifier('teaser.uid')
                )
            )
            ->where($queryBuilder->expr()->eq('mm.uid_local', $contentElement))
            ->execute()
        ;

        while ($row = $statement->fetch()) {
            $teasers[] = [
                'title' => $row['title'],
                'name' => $row['name'],
            ];
        }

        return $teasers;
    }
}
