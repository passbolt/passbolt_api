<?php

return [
    'passbolt' => [
        'plugins' => [
            'sso' => [
                'version' => '1.0.0',
                //'enabled' => true // see default.php
                'debugEnabled' => filter_var(env('PASSBOLT_PLUGINS_SSO_DEBUG_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
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
                    // Preferred method to be used when performing a redirect from SSO provider to Passbolt
                    // Default to GET to avoid cookie session requirement to be set to sameSite=None if redirect is POST
                    'redirectMethod' => env('PASSBOLT_PLUGINS_SSO_SECURITY_REDIRECT_METHOD', null),
                    // Disable CSRF protection on provider redirect via POST
                    // CSRF protection is then handled via the state parameter
                    'csrfProtection' => [
                        'unlockedActions' => [
                            'SsoAzureStage2' => 'triage',
                            'SsoOAuth2Stage2' => 'triage',
                            // No POST for Google.
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
