<?php
return [
    'passbolt' => [
        'plugins' => [
            'locale' => [
                'version' => '3.2.0',
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'options',
                    ],
                    'whiteList' => [
                        'options',
                    ],
                ],
                'options' => [
                    [
                        'locale' => 'en-UK',
                        'label' => 'English',
                    ],
                    [
                        'locale' => 'fr-FR',
                        'label' => 'Français',
                    ],
                ],
            ],
        ],
    ],
];
