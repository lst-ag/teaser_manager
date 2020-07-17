<?php

namespace LST\TeaserManager\DataProcessing;

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

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentDataProcessor;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\ContentObject\DataProcessorInterface;

/**
 * Class for data processing for the content element "Teaser"
 */
class TeaserProcessor implements DataProcessorInterface
{
    /**
     * @var ContentDataProcessor
     */
    protected $contentDataProcessor;

    /**
     * @var array
     */
    protected $extensionSettings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contentDataProcessor = GeneralUtility::makeInstance(ContentDataProcessor::class);
        $this->extensionSettings = GeneralUtility::makeInstance(ExtensionConfiguration::class)->get('teaser_manager');
    }

    /**
     * Process data for the content element "Teaser"
     *
     * @param ContentObjectRenderer $cObj The data of the content element or page
     * @param array $contentObjectConfiguration The configuration of Content Object
     * @param array $processorConfiguration The configuration of this processor
     * @param array $processedData Key/value store of processed data (e.g. to be passed to a Fluid View)
     * @return array the processed data as key/value store
     */
    public function process(
        ContentObjectRenderer $cObj,
        array $contentObjectConfiguration,
        array $processorConfiguration,
        array $processedData
    ) {
        $objectManager = GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
        $teaserRepository = $objectManager->get('LST\TeaserManager\Domain\Repository\TeaserRepository');

        // Attach teaser data
        $records = $cObj->getRecords('tx_teasermanager_domain_model_teaser', [
            'selectFields' => 'tx_teasermanager_domain_model_teaser.uid',
            'pidInList' => $this->extensionSettings['globalStoragePid'],
            'join' => 'tx_teasermanager_ttcontent_teaser_mm ON tx_teasermanager_ttcontent_teaser_mm.uid_foreign = tx_teasermanager_domain_model_teaser.uid',
            'where.' => [
                'data' => 'field:uid',
                'intval' => 1,
                'wrap' => 'tx_teasermanager_ttcontent_teaser_mm.uid_local=|'
            ],
            'orderBy' => 'tx_teasermanager_ttcontent_teaser_mm.sorting'
        ]);

        $teasers = [];
        foreach ($records as $key => $record) {
            /** @var ContentObjectRenderer $recordContentObjectRenderer */
            $recordContentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
            $recordContentObjectRenderer->start($record, 'tx_teasermanager_domain_model_teaser');

            $teaser = $teaserRepository->findByUid($record['uid']);
            $teasers[$key] = $teaser;
        }
        $processedData['teasers'] = $teasers;

        // Attach teaser type data
        $teaserTypeRecords = $cObj->getRecords('tx_teasermanager_domain_model_teasertype', [
            'selectFields' => 'tx_teasermanager_domain_model_teasertype.fields',
            'pidInList' => $this->extensionSettings['globalStoragePid'],
            'where.' => [
                'data' => 'field:teaser_type',
                'intval' => 1,
                'wrap' => 'tx_teasermanager_domain_model_teasertype.uid=|'
            ]
        ]);

        $teaserFields = [];
        if (count($teaserTypeRecords) === 1) {
            $teaserFieldsList = $teaserTypeRecords[0]['fields'];
            foreach (explode(',', $teaserFieldsList) as $field) {
                $teaserFields[$field] = 1;
            }
        }
        $processedData['teaserFields'] = $teaserFields;

        return $processedData;
    }
}
