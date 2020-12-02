<?php
declare(strict_types = 1);

/***
 *
 * This file is part of the "LST Management" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2017 Christian Fries <christian.fries@lst.team>, LST AG
 *
 ***/

namespace CHF\TeaserManager\Mvc\View;

use TYPO3\CMS\Extbase\Mvc\View\JsonView as ExtbaseJsonView;

class JsonView extends ExtbaseJsonView
{
    /**
     * @var array
     */
    protected $configuration = [
        'teasers' => [
            '_descendAll' => [
                '_exclude' => ['pid', 'hidden', 'color', 'icon', 'linkText', 'name', 'person', 'selectedIcon', 'size', 'style', 'image', 'text'],
            ]
        ]
    ];
}
