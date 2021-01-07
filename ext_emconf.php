<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Teaser Manager',
    'description' => 'Manage teasers in one place and use them wherever you want to.',
    'category' => 'plugin',
    'author' => 'Christian Fries',
    'author_email' => 'christian.fries@lst.team',
    'state' => 'stable',
    'clearCacheOnLoad' => 1,
    'version' => '3.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '9.5.17-10.4.99',
            'backend_module' => '2.0.0-2.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'LST\\TeaserManager\\' => 'Classes',
        ],
    ],
];
