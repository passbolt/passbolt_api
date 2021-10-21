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
                        'locale' => 'de-DE',
                        'label' => 'Deutsch',
                    ],
                    [
                        'locale' => 'fr-FR',
                        'label' => 'Français',
                    ],
                    [
                        'locale' => 'sv-SE',
                        'label' => 'Svenska',
                    ],
                ],
            ],
        ],
    ],
];
