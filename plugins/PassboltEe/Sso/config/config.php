<?php

return [
    'passbolt' => [
        'plugins' => [
            'sso' => [
                'version' => '1.0.0',
                //'enabled' => true // see default.php
                'settingsVisibility' => [
                    'whiteListPublic' => [
                        'enabled',
                    ],
                ],
                'security' => [
                    // if supported by provider
                    // force authentication with SSO provider even if user is logged in
                    // @deprecated Since v4.1.0 not used. It is recommended to set the prompt value from the SSO settings page.
                    'prompt' => filter_var(env('PASSBOLT_PLUGINS_SSO_SECURITY_PROMPT', true), FILTER_VALIDATE_BOOLEAN),
                    // Disable CSRF protection on provider redirect
                    // CSRF protection is then handled via the state parameter
                    'csrfProtection' => [
                        'unlockedActions' => [
                            'SsoAzureStage2' => 'triage',
                        ],
                    ],
                    /**
                     * When checking nbf, iat or expiration times, we want to provide some extra leeway time(in seconds) to account for clock skew.
                     */
                    'jwtLeeway' => filter_var(env('PASSBOLT_PLUGINS_SSO_JWT_LEEWAY', '0'), FILTER_VALIDATE_INT),
                ],
                // 'providers' => [], // see default.php
            ],
        ],
    ],
];
