<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Teaser Manager',
    'description' => 'Manage teasers in one place and use them wherever you want to.',
    'category' => 'plugin',
    'author' => 'Christian Fries',
    'author_email' => 'hello@christian-fries.ch',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '2.1.1',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
            'backend_module' => '1.0.0-1.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'CHF\\TeaserManager\\' => 'Classes',
        ],
    ],
];
