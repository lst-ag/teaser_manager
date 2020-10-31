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

namespace LST\TeaserManager\Domain\Dto;

class Filter
{
    /**
     * @var int
     */
    protected $type = 0;

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }
}
