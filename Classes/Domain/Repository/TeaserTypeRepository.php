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

namespace LST\TeaserManager\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

class TeaserTypeRepository extends Repository
{
    protected $defaultOrderings = [
        'title' => QueryInterface::ORDER_ASCENDING
    ];

    /**
     * Initialize Object with predefined settings
     *
     * @return void
     */
    public function initializeObject()
    {
        $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * @param string $mode
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     */
    public function findAll($mode = 'Frontend')
    {
        $query = $this->createQuery();

        if ($mode == 'Backend') {
            $querySettings = $this->objectManager->get(Typo3QuerySettings::class);
            $querySettings->setIgnoreEnableFields(true);
            $querySettings->setRespectStoragePage(false);
            $query->setQuerySettings($querySettings);
        }

        return $query->execute();
    }
}
