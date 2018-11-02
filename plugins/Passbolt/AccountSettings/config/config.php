<?php
return [
    'passbolt' => [
        'plugins' => [
            'accountSettings' => [
                'version' => '1.0.0',
                'settingsVisibility' => [
                    'whiteList' => [
                        'themes',
                    ],
                ],
                'themes' => [
                    'css' => 'api_main.min.css'
                ]
            ],
        ],
    ]
];
