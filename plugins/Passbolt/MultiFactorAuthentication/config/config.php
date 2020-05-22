<?php
return [
    'passbolt' => [
        'plugins' => [
            'multiFactorAuthentication' => [
                'version' => '1.1.0',
                'enabled' => true,
                'providers' => [
                    'totp' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_TOTP', true), FILTER_VALIDATE_BOOLEAN),
                    'duo' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_DUO', false), FILTER_VALIDATE_BOOLEAN),
                    'yubikey' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_YUBIKEY', false), FILTER_VALIDATE_BOOLEAN),
                ],
                'yubikey' => [
                    'clientId' => env('PASSBOLT_PLUGINS_MFA_YUBIKEY_CLIENTID', null),
                    'secretKey' => env('PASSBOLT_PLUGINS_MFA_YUBIKEY_SECRETKEY', null),
                ],
                'duo' => [
                    'salt' => env('PASSBOLT_PLUGINS_MFA_DUO_SALT', null),
                    'integrationKey' => env('PASSBOLT_PLUGINS_MFA_DUO_INTEGRATIONKEY', null),
                    'secretKey' => env('PASSBOLT_PLUGINS_MFA_DUO_SECRETKEY', null),
                    'hostName' => env('PASSBOLT_PLUGINS_MFA_DUO_HOST', null),
                ],
            ],
        ],
    ],
];
