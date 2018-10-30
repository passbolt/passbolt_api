<?php
return [
    'passbolt' => [
        'plugins' => [
            'multiFactorAuthentication' => [
                'version' => '1.1.0',
                'providers' => [
                    'totp' => true,
//                    'duo' => false,
//                    'yubikey' => false
                ],
//                'yubikey' => [
//                    'clientId' => '',
//                    'secretKey' => '',
//                    'host' => 'cloud.yubico.com'
//                ],
//                'duo' => [
//                    'integrationKey' => '',
//                    'secretKey' => '',
//                    'hostName' => 'api-XXXX.duosecurity.com'
//                ]
            ],
        ],
    ]
];
