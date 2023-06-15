<?php
return [
    'passbolt' => [
        'plugins' => [
            'multiFactorAuthentication' => [
                'version' => '1.1.0',
                'enabled' => true,
                'providers' => [
                    'totp' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_TOTP', false), FILTER_VALIDATE_BOOLEAN),
                    'duo' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_DUO', false), FILTER_VALIDATE_BOOLEAN),
                    'yubikey' => filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_YUBIKEY', false), FILTER_VALIDATE_BOOLEAN), //phpcs:ignore
                ],
                'totp' => [
                    'secretLength' => env('PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH', null),
                ],
                'yubikey' => [
                    'clientId' => env('PASSBOLT_PLUGINS_MFA_YUBIKEY_CLIENTID', null),
                    'secretKey' => env('PASSBOLT_PLUGINS_MFA_YUBIKEY_SECRETKEY', null),
                ],
                'duo' => [
                    // @deprecated since v3.10 with Duo v4 support: PASSBOLT_PLUGINS_MFA_DUO_INTEGRATIONKEY, PASSBOLT_PLUGINS_MFA_DUO_SECRETKEY, PASSBOLT_PLUGINS_MFA_DUO_HOST
                    'clientId' => env(
                        'PASSBOLT_PLUGINS_MFA_DUO_CLIENT_ID',
                        env('PASSBOLT_PLUGINS_MFA_DUO_INTEGRATIONKEY', null)
                    ),
                    'clientSecret' => env(
                        'PASSBOLT_PLUGINS_MFA_DUO_CLIENT_SECRET',
                        env('PASSBOLT_PLUGINS_MFA_DUO_SECRETKEY', null)
                    ),
                    'apiHostName' => env(
                        'PASSBOLT_PLUGINS_MFA_DUO_API_HOSTNAME',
                        env('PASSBOLT_PLUGINS_MFA_DUO_HOST', null)
                    ),
                ],
                'sortProvidersByLastUsage' => filter_var(env('PASSBOLT_PLUGINS_MFA_SORT_PROVIDERS_BY_LAST_USAGE', true), FILTER_VALIDATE_BOOLEAN), //phpcs:ignore
            ],
        ],
    ],
];
