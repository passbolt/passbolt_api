<?php
return [
    'passbolt' => [
        'plugins' => [
            'remember_me' => [
                'version' => '0.0.1',
                'options' => [
                    '5' => __('5 minutes'),
                    '15' => __('15 minutes'),
                    '30' => __('30 minutes'),
                    '60' => __('1 hour'),
                    '-1' => __('until I log out')
                ]
            ]
        ]
    ]
];