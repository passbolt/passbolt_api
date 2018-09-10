<?php
return [
    'passbolt' => [
        'plugins' => [
            'rememberMe' => [
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'options',
                    ],
                    'whiteList' => [
                        'options',
                    ],
                ],
                'version' => '2.0.0',
                'options' => [
                    '300' => __('5 minutes'),
                    '900' => __('15 minutes'),
                    '1800' => __('30 minutes'),
                    '3600' => __('1 hour'),
                    '-1' => __('until I log out')
                ]
            ]
        ]
    ]
];
