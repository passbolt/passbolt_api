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
 * @since         2.0.0
 */
return [
    /*
     * Passbolt application default configuration.
     * In alphabetical order:
     * - Authentication
     * - Email notifications
     * - Javascript application config
     * - Meta HTML tags
     * - Gpg
     * - Registration settings
     * - Selenium mode
     * - Security settings
     * - SSL
     *
     * Pick a section and place it in your passbolt.php file to replace default settings.
     * Do not modify directly the values below as it will break passbolt update process.
     *
     */
    'passbolt' => [
        // Edition.
        'edition' => 'ce',

        // Authentication & Authorisation.
        'auth' => [
            'tokenExpiry' => env('PASSBOLT_AUTH_TOKEN_EXPIRY', '3 days')
        ],

        // Email settings
        'email' => [
            // Additional email validation settings
            'validate' => [
                'mx' => filter_var(env('PASSBOLT_EMAIL_VALIDATE_MX', false), FILTER_VALIDATE_BOOLEAN),
            ],

            // Email delivery settings such as credentials are in app.php.
            // Allow to disable displaying the armored secret in the email.
            // WARNING: make sure you have backups in place if you disable these.
            // See. https://www.passbolt.com/help/tech/backup
            'show' => [
                'comment' => filter_var(env('PASSBOLT_EMAIL_SHOW_COMMENT', true), FILTER_VALIDATE_BOOLEAN),
                'description' => filter_var(env('PASSBOLT_EMAIL_SHOW_DESCRIPTION', true), FILTER_VALIDATE_BOOLEAN),
                'secret' => filter_var(env('PASSBOLT_EMAIL_SHOW_SECRET', true), FILTER_VALIDATE_BOOLEAN),
                'uri' => filter_var(env('PASSBOLT_EMAIL_SHOW_URI', true), FILTER_VALIDATE_BOOLEAN),
                'username' => filter_var(env('PASSBOLT_EMAIL_SHOW_USERNAME', true), FILTER_VALIDATE_BOOLEAN),
            ],
            // Choose which emails are sent system wide.
            'send' => [
                'comment' => [
                    'add' => filter_var(env('PASSBOLT_EMAIL_SEND_COMMENT_ADD', true), FILTER_VALIDATE_BOOLEAN)
                ],
                'password' => [
                    'create' => filter_var(env('PASSBOLT_EMAIL_SEND_PASSWORD_CREATE', true), FILTER_VALIDATE_BOOLEAN),
                    'share' => filter_var(env('PASSBOLT_EMAIL_SEND_PASSWORD_SHARE', true), FILTER_VALIDATE_BOOLEAN),
                    'update' => filter_var(env('PASSBOLT_EMAIL_SEND_PASSWORD_UPDATE', true), FILTER_VALIDATE_BOOLEAN),
                    'delete' => filter_var(env('PASSBOLT_EMAIL_SEND_PASSWORD_DELETE', true), FILTER_VALIDATE_BOOLEAN),
                ],
                'user' => [
                    // WARNING: disabling these will prevent user from signing up.
                    'create' => filter_var(env('PASSBOLT_EMAIL_SEND_USER_CREATE', true), FILTER_VALIDATE_BOOLEAN),
                    'recover' => filter_var(env('PASSBOLT_EMAIL_SEND_USER_RECOVER', true), FILTER_VALIDATE_BOOLEAN),
                ],
                'group' => [
                    // Notify all members that a group was deleted.
                    'delete' => filter_var(env('PASSBOLT_EMAIL_SEND_GROUP_DELETE', true), FILTER_VALIDATE_BOOLEAN),
                    'user' => [ // notify user group membership changes.
                        'add' => filter_var(env('PASSBOLT_EMAIL_SEND_GROUP_USER_ADD', true), FILTER_VALIDATE_BOOLEAN),
                        'delete' => filter_var(env('PASSBOLT_EMAIL_SEND_GROUP_USER_DELETE', true), FILTER_VALIDATE_BOOLEAN),
                        'update' => filter_var(env('PASSBOLT_EMAIL_SEND_GROUP_USER_UPDATE', true), FILTER_VALIDATE_BOOLEAN),
                    ],
                    'manager' => [
                        // Notify managers when group membership changes.
                        'update' => filter_var(env('PASSBOLT_EMAIL_SEND_GROUP_MANAGER_UPDATE', true), FILTER_VALIDATE_BOOLEAN),
                    ]
                ]
            ]
        ],

        // build | options : development or production.
        // development will load the non compiled version.
        // production will load the compiled passbolt.js file.
        'js' => [
            'build' => env('PASSBOLT_JS_BUILD', 'production')
        ],

        // Html meta information.
        'meta' => [
            'title' => env('PASSBOLT_META_TITLE', 'Passbolt'),
            'description' => env('PASSBOLT_META_DESCRIPTION', 'Open source password manager for teams'),
            // Do you want search engine robots to index your site.
            // Default is set to false.
            'robots' => env('PASSBOLT_META_ROBOTS', 'noindex, nofollow')
        ],

        // GPG Configuration.
        'gpg' => [
            // Tell passbolt which OpenPGP backend to use
            // Default is PHP-GNUPG with some help from OpenPGP-PHP
            'backend' => env('PASSBOLT_GPG_BACKEND', 'gnupg'),

            // Tell passbolt where to find the GnuPG keyring.
            // If putenv is set to false, gnupg will use the default path ~/.gnupg.
            // For example :
            // - Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // - Apache on Debian it would be in '/var/www/.gnupg'
            // - Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
            // - etc.
            'keyring' => getenv("HOME") . DS . '.gnupg',

            // Replace GNUPGHOME with above value even if it is set.
            'putenv' => false,

            // Main server key.
            'serverKey' => [
                // Server public / private key location and fingerprint.
                'fingerprint' => env('PASSBOLT_GPG_SERVER_KEY_FINGERPRINT', null),
                'public' => env('PASSBOLT_GPG_SERVER_KEY_PUBLIC', CONFIG . 'gpg' . DS . 'serverkey.asc'),
                'private' => env('PASSBOLT_GPG_SERVER_KEY_PRIVATE', CONFIG . 'gpg' . DS . 'serverkey_private.asc'),

                // PHP Gnupg module currently does not support passphrase, please leave blank.
                'passphrase' => ''
            ]
        ],

        // Legal
        'legal' => [
            'privacy_policy' => [
                'url' => env('PASSBOLT_LEGAL_PRIVACYPOLICYURL', '')
            ]
        ],

        // Wich plugins are enabled
        'plugins' => [
            'import' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_IMPORT_ENABLED', true), FILTER_VALIDATE_BOOLEAN)
            ],
            'export' => [
                'enabled' => filter_var(env('PASSBOLT_PLUGINS_EXPORT_ENABLED', true), FILTER_VALIDATE_BOOLEAN)
            ],
        ],

        // Is public registration allowed.
        'registration' => [
            'public' => filter_var(env('PASSBOLT_REGISTRATION_PUBLIC', false), FILTER_VALIDATE_BOOLEAN)
        ],

        // Activate specific entry points for selenium testing.
        // true will render your installation insecure.
        'selenium' => [
            'active' => filter_var(env('PASSBOLT_SELENIUM_ACTIVE', false), FILTER_VALIDATE_BOOLEAN)
        ],

        // Security.
        'security' => [
            'setHeaders' => filter_var(env('PASSBOLT_SECURITY_SET_HEADERS', true), FILTER_VALIDATE_BOOLEAN),
            'csrfProtection' => [
                'active' => true,
                'unlockedActions' => [
                    'AuthLogin' => ['loginPost'],
                    'RecoverComplete' => ['complete'],
                    'SetupComplete' => ['complete'],
                ]
            ],
            'csp' => env('PASSBOLT_SECURITY_CSP', true)
        ],

        // Should the app be SSL / HTTPS only.
        // false will render your installation insecure.
        'ssl' => [
            'force' => filter_var(env('PASSBOLT_SSL_FORCE', true), FILTER_VALIDATE_BOOLEAN)
        ]
    ],
    // Override the Cake ExceptionRenderer.
    'Error' => [
        'exceptionRenderer' => 'App\Error\AppExceptionRenderer',
    ]
];
