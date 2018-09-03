<?php
return [
    'passbolt' => [
        'plugins' => [
            'import' => [
                'settingsVisibility' => [
                    'whiteList' => [
                        'config.format',
                    ],
                ],
                'version' => '2.0.0',
                'config' => [
                    'format' => [
                        'kdbx',
                        'csv',
                    ]
                ],
                'security' => [
                    'csrfProtection' => [
                        'unlockedActions' => [
                            'ResourcesAdd' => ['add'],
                            'ResourcesTagsAdd' => ['addPost']
                        ]
                    ]
                ],
            ]
        ]
    ]
];
