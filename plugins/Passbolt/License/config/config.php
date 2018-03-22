<?php
return [
    'passbolt' => [
        'plugins' => [
            'license' => [
                'version' => '2.0.0',
                'license' => CONFIG . 'license',
                'licenseKey' => [
                    'public' => __DIR__ . DS . 'license_public.key'
                ]
            ]
        ]
    ]
];
