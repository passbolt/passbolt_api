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
                        'locale' => 'de-DE',
                        'label' => 'Deutsch',
                    ],
                    [
                        'locale' => 'en-UK',
                        'label' => 'English',
                    ],
                    [
                        'locale' => 'fr-FR',
                        'label' => 'Français',
                    ],
                    [
                        'locale' => 'ja-JP',
                        'label' => '日本語',
                    ],
                    [
                        'locale' => 'nl-NL',
                        'label' => 'Nederlands',
                    ],
                    [
                        'locale' => 'pl-PL',
                        'label' => 'Polski',
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
