<?php
return [
    'passbolt' => [
        'plugins' => [
            'passkeyAuthentication' => [
                'version' => '1.0.0',
                // Passkey login. Users can enrol a security key to sign in without
                // typing their passphrase; the passphrase is recovered locally from a split kit whose
                // server half is released only after a valid login assertion (see the migration).
                'enabled' => filter_var(
                    env('PASSBOLT_PLUGINS_PASSKEY_AUTHENTICATION_ENABLED', true),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ],
        ],
    ],
];
