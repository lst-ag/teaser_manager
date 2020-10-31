<?php
defined('TYPO3_MODE') or die();

return [
    'ctrl' => [
        'title'	=> 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaserlayout',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'searchFields' => 'title,',
        'iconfile' => 'EXT:teaser_manager/Resources/Public/Icons/teaserlayout.svg'
    ],
    'types' => [
        '1' => ['showitem' => 'title, '],
    ],
    'columns' => [
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:teaser_manager/Resources/Private/Language/locallang_db.xlf:teaserlayout.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ],

        ],
    ],
];
