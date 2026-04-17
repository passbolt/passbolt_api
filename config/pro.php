<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.12.0
 */

use App\Utility\AuthToken\AuthTokenExpiryConfigValidator;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;

$authTokenExpiryConfigValidator = new AuthTokenExpiryConfigValidator();

return [
    /*
     * Passbolt application PRO configuration.
     *
     */
    'passbolt' => [
        // Authentication & Authorisation.
        'auth' => [
            'token' => [
                SsoState::TYPE_SSO_SET_SETTINGS => [
                    'expiry' => filter_var(env('PASSBOLT_AUTH_SSO_SET_SETTINGS', '10 minutes'), FILTER_CALLBACK, ['options' => $authTokenExpiryConfigValidator]),
                ],
                SsoState::TYPE_SSO_GET_KEY => [
                    'expiry' => filter_var(env('PASSBOLT_AUTH_SSO_GET_KEY', '10 minutes'), FILTER_CALLBACK, ['options' => $authTokenExpiryConfigValidator]),
                ],
                SsoState::TYPE_SSO_STATE => [
                    'expiry' => filter_var(env('PASSBOLT_AUTH_SSO_STATE', '10 minutes'), FILTER_CALLBACK, ['options' => $authTokenExpiryConfigValidator]),
                ],
            ],
        ],

        // Email settings
        'email' => [
            'send' => [
                'accountRecovery' => [
                    'request' => [
                        'user' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_REQUEST_USER', true), FILTER_VALIDATE_BOOLEAN),
                        'admin' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_REQUEST_ADMIN', true), FILTER_VALIDATE_BOOLEAN),
                        'guessing' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_REQUEST_GUESSING', true), FILTER_VALIDATE_BOOLEAN),
                    ],
                    'response' => [
                        'user' => [
                            'approved' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_RESPONSE_USER_APPROVED', true), FILTER_VALIDATE_BOOLEAN),
                            'rejected' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_RESPONSE_USER_REJECTED', true), FILTER_VALIDATE_BOOLEAN),
                        ],
                        'created' => [
                            'admin' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_RESPONSE_CREATED_ADMIN', true), FILTER_VALIDATE_BOOLEAN),
                            'allAdmins' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_RESPONSE_CREATED_ALL_ADMINS', true), FILTER_VALIDATE_BOOLEAN),
                        ],
                    ],
                    'policy' => [
                        'update' => filter_var(env('PASSBOLT_EMAIL_SEND_ACCOUNT_RECOVERY_POLICY_UPDATE', true), FILTER_VALIDATE_BOOLEAN),
                    ],
                ],
            ],
        ],

        // Which plugins are enabled
        'plugins' => [
            'passwordExpiryPolicies' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_PASSWORD_EXPIRY_POLICIES_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],

            'accountRecovery' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_ACCOUNT_RECOVERY_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],
            'subscription' => [
                'enabled' => true,
            ],
            'sso' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_SSO_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
                'providers' => [
                    SsoSetting::PROVIDER_AZURE => filter_var(
                        env('PASSBOLT_PLUGINS_SSO_PROVIDER_AZURE_ENABLED', true),
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    SsoSetting::PROVIDER_GOOGLE => filter_var(
                        env('PASSBOLT_PLUGINS_SSO_PROVIDER_GOOGLE_ENABLED', true),
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    // Generic OAuth2/OIDC provider
                    SsoSetting::PROVIDER_OAUTH2 => filter_var(
                    // PASSBOLT_PLUGINS_SSO_PROVIDER_OAUHT2_ENABLED env is @deprecated due to typo, kept if for BC. To be removed in v6.0.
                        env('PASSBOLT_PLUGINS_SSO_PROVIDER_OAUTH2_ENABLED', env('PASSBOLT_PLUGINS_SSO_PROVIDER_OAUHT2_ENABLED', true)),
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    SsoSetting::PROVIDER_ADFS => filter_var(
                        env('PASSBOLT_PLUGINS_SSO_PROVIDER_ADFS_ENABLED', true),
                        FILTER_VALIDATE_BOOLEAN
                    ),
                    SsoSetting::PROVIDER_PINGONE => filter_var(
                        env('PASSBOLT_PLUGINS_SSO_PROVIDER_PINGONE_ENABLED', true),
                        FILTER_VALIDATE_BOOLEAN
                    ),
                ],
                'security' => [
                    'jwks' => ['defaultAlg' => env('PASSBOLT_PLUGINS_SSO_SECURITY_JWKS_DEFAULT_ALG', null)],
                ],
            ],
            'mfaPolicies' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_MFA_POLICIES_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],
            'ssoRecover' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_SSO_RECOVER_ENABLED', true), FILTER_VALIDATE_BOOLEAN)
            ],
            'directorySync' => [
                'caseSensitiveFilters' => filter_var(env('PASSBOLT_PLUGINS_DIRECTORY_SYNC_CASE_SENSITIVE_FILTERS', false), FILTER_VALIDATE_BOOLEAN),
                'security' => [
                    // This config is part of beta support for this feature, and will be removed or changed in the future.
                    'sslCustomOptions' => [
                        // False by default to keep BC with existing installations.
                        'enabled' => filter_var(env('PASSBOLT_PLUGINS_DIRECTORY_SYNC_SECURITY_SSL_CUSTOM_OPTIONS_ENABLED', false), FILTER_VALIDATE_BOOLEAN),
                        // LDAP_OPT_X_TLS_REQUIRE_CERT - false=LDAP_OPT_X_TLS_NEVER, true=not set.
                        // Setting this to `false` is discouraged and can open up multiple attack vectors.
                        'verifyPeer' => filter_var(env('PASSBOLT_PLUGINS_DIRECTORY_SYNC_SECURITY_SSL_CUSTOM_OPTIONS_VERIFY_PEER', true), FILTER_VALIDATE_BOOLEAN),
                        // LDAP_OPT_X_TLS_CACERTDIR
                        'cadir' => env('PASSBOLT_PLUGINS_DIRECTORY_SYNC_SECURITY_SSL_CUSTOM_OPTIONS_CADIR', null),
                        // LDAP_OPT_X_TLS_CACERTFILE
                        'cafile' => env('PASSBOLT_PLUGINS_DIRECTORY_SYNC_SECURITY_SSL_CUSTOM_OPTIONS_CAFILE', null),
                    ],
                ],
            ],
            'passwordPoliciesUpdate' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_PASSWORD_POLICIES_UPDATE_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],
            'userPassphrasePolicies' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_USER_PASSPHRASE_POLICIES_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],
            'tags' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_TAGS_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
                'readOnlyMode' => filter_var(env('PASSBOLT_PLUGINS_TAGS_READ_ONLY_MODE', false), FILTER_VALIDATE_BOOLEAN),
                'backupMode' => filter_var(env('PASSBOLT_PLUGINS_TAGS_BACKUP_MODE', false), FILTER_VALIDATE_BOOLEAN),
            ],
            'scim' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_SCIM_ENABLED', true), FILTER_VALIDATE_BOOLEAN),
            ],
        ],

        // Selenium
        'selenium' => [
            'sso' => [
                'active' => filter_var(env('PASSBOLT_SELENIUM_SSO_ACTIVE', false)),
                'azure' => [
                    'url' => env('PASSBOLT_SELENIUM_SSO_AZURE_URL', 'https://login.microsoftonline.com'),
                    'tenantId' => env('PASSBOLT_SELENIUM_SSO_AZURE_TENANT_ID', ''),
                    'clientId' => env('PASSBOLT_SELENIUM_SSO_AZURE_CLIENT_ID', ''),
                    'secretId' => env('PASSBOLT_SELENIUM_SSO_AZURE_SECRET_ID', ''),
                    'secretExpiry' => env('PASSBOLT_SELENIUM_SSO_AZURE_SECRET_EXPIRY', ''),
                ],
                'google' => [
                    'clientId' => env('PASSBOLT_SELENIUM_SSO_GOOGLE_CLIENT_ID', ''),
                    'secretId' => env('PASSBOLT_SELENIUM_SSO_GOOGLE_SECRET_ID', ''),
                ],
                'oauth2' => [
                    'url' => env('PASSBOLT_SELENIUM_SSO_OAUTH2_URL', ''),
                    'clientId' => env('PASSBOLT_SELENIUM_SSO_OAUTH2_CLIENT_ID', ''),
                    'secretId' => env('PASSBOLT_SELENIUM_SSO_OAUTH2_SECRET_ID', ''),
                    'scope' => env('PASSBOLT_SELENIUM_SSO_OAUTH2_SCOPE', 'openid'),
                    'openIdConfigurationPath' => env('PASSBOLT_SELENIUM_SSO_OAUTH2_OPENID_CONFIGURATION_PATH', ''),
                ],
            ],
        ],

        // Security.
        'security' => [
            // Disable SCIM settings endpoints
            'scim' => [
                'settings' => [
                    'endpointsDisabled' => filter_var(env('PASSBOLT_SECURITY_SCIM_SETTINGS_ENDPOINTS_DISABLED', false), FILTER_VALIDATE_BOOLEAN),
                ],
            ],
            'directorySync' => [
                'forbiddenFields' => [
                    'active' => filter_var(env('PASSBOLT_SECURITY_DIRECTORY_SYNC_FORBIDDEN_FIELDS_ACTIVE', true), FILTER_VALIDATE_BOOLEAN),
                    // Disallow certain sensitive fields that should not be queried from LDAP.
                    // The blocked fields from the passbolt.php get added to this list are added at the end of this list. A merge strategy is applied on the configurations.
                    'fieldNames' => [
                        'userPassword',
                        'User-Password',
                        'uniqueUserPassword',
                        'password',
                        'unixUserPassword',
                        'msPKIAccountCredentials',
                        'ms-PKI-AccountCredentials',
                        'msPKI-CredentialRoamingTokens',
                        'unicodePwd',
                        'Unicode-Pwd',
                        'dBCSPwd',
                        'DBCS-Pwd',
                        'lmPwdHistory',
                        'Lm-Pwd-History',
                        'ntPwdHistory',
                        'Nt-Pwd-History',
                        'pwdProperties',
                        'Pwd-Properties',
                        'msDS-ManagedPassword',
                        'ms-DS-ManagedPassword',
                        'ms-FVE-RecoveryPassword',
                        'msFVE-RecoveryPassword',
                        'supplementalCredentials',
                        'Supplemental-Credentials',
                    ],
                ],
                'endpointsDisabled' => filter_var(env('PASSBOLT_SECURITY_DIRECTORY_SYNC_ENDPOINTS_DISABLED', false), FILTER_VALIDATE_BOOLEAN),
            ],
            'sso' => [
                /**
                 * Useful for OAuth 2.0 and AD FS providers when using self-signed certificate.
                 *
                 * Accepted values:
                 * - `true` - Default. It will verify SSL certification and SSL certificate against the host name
                 * - `false` - Disable SSL certification verification (not recommended).
                 */
                'sslVerify' => filter_var(
                    env('PASSBOLT_SECURITY_SSO_SSL_VERIFY', true),
                    FILTER_VALIDATE_BOOLEAN
                ),
                /**
                 * Accepted values:
                 * - `null` - Default. Uses built in cafile.
                 * - `'/path/to/rootCA.crt'` (string) - Path to custom root CA certificate.
                 */
                'sslCafile' => env('PASSBOLT_SECURITY_SSO_SSL_CAFILE', null),
                'settings' => [
                    'editionDisabled' => filter_var(env('PASSBOLT_SECURITY_SSO_SETTINGS_EDITION_DISABLED', false), FILTER_VALIDATE_BOOLEAN),
                ],
            ],
        ],
    ],
];
