<?php
declare(strict_types = 1);

namespace LST\TeaserManager\Mvc\View;

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
