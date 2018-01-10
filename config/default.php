<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
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

        // Authentication & Authorisation.
        'auth' => [
            'tokenExpiry' => env('PASSBOLT_AUTH_TOKEN_EXPIRY', '3 days')
        ],

        // Email notification settings.
        // Email delivery settings such as credentials are in app.php.
        'email' => [
            // Allow to disable displaying the armored secret in the email.
            // WARNING: make sure you have backups in place if you disable these.
            // See. https://www.passbolt.com/help/tech/backup
            'show' => [
                'comment' => env('PASSBOLT_EMAIL_SHOW_COMMENT', true),
                'description' => env('PASSBOLT_EMAIL_SHOW_DESCRIPTION', true),
                'secret' => env('PASSBOLT_EMAIL_SHOW_SECRET', true),
                'uri' => env('PASSBOLT_EMAIL_SHOW_URI', true),
                'username' => env('PASSBOLT_EMAIL_SHOW_USERNAME', true),
            ],
            // Choose which emails are sent system wide.
            'send' => [
                'comment' => [
                    'add' => env('PASSBOLT_EMAIL_SEND_COMMENT_ADD', true)
                ],
                'password' => [
                    'create' => env('PASSBOLT_EMAIL_SEND_PASSWORD_CREATE', true),
                    'share' => env('PASSBOLT_EMAIL_SEND_PASSWORD_SHARE', true),
                    'update' => env('PASSBOLT_EMAIL_SEND_PASSWORD_UPDATE', true),
                    'delete' => env('PASSBOLT_EMAIL_SEND_PASSWORD_DELETE', true),
                ],
                'user' => [
                    // WARNING: disabling these will prevent user from signing up.
                    'create' => env('PASSBOLT_EMAIL_SEND_USER_CREATE', true),
                    'recover' => env('PASSBOLT_EMAIL_SEND_USER_RECOVER', true),
                ],
                'group' => [
                    // Notify all members that a group was deleted.
                    'delete' => env('PASSBOLT_EMAIL_SEND_GROUP_DELETE', true),
                    'user' => [ // notify user group membership changes.
                        'add' => env('PASSBOLT_EMAIL_SEND_GROUP_USER_ADD', true),
                        'delete' => env('PASSBOLT_EMAIL_SEND_GROUP_USER_DELETE', true),
                        'update' => env('PASSBOLT_EMAIL_SEND_GROUP_USER_UPDATE', true),
                    ],
                    'manager' => [
                        // Notify manager when a group user is updated / deleted.
                        'update' => env('PASSBOLT_EMAIL_SEND_GROUP_MANAGER_UPDATE', true),
                        'delete' => env('PASSBOLT_EMAIL_SEND_GROUP_MANAGER_DELETE', true),
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
            // Tell GPG where to find the keyring.
            // Needs to be available by the webserver user, for example:
            // Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // Apache on Debian it would be in '/home/www-data/.gnupg'
            // Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
            // etc.
            'keyring' => env('PASSBOLT_GPG_KEYRING', '/home/www-data/.gnupg'),

            // Replace GNUPGHOME with above value even if it is set.
            'putenv' => true,

            // Main server key.
            'serverKey' => [
                // Server public / private key location and fingerprint.
                'fingerprint' => env('PASSBOLT_GPG_SERVER_KEY_FINGERPRINT', '2FC8945833C51946E937F9FED47B0811573EE67E'),
                'public' => env('PASSBOLT_GPG_SERVER_KEY_PUBLIC', ROOT . DS . 'config' . DS . 'gpg' . DS . 'serverkey.asc'),
                'private' => env('PASSBOLT_GPG_SERVER_KEY_PRIVATE', ROOT . DS . 'config' . DS . 'gpg' . DS . 'serverkey_private.asc'),

                // PHP Gnupg module currently does not support passphrase, please leave blank.
                'passphrase' => ''
            ],
        ],

        // Is public registration allowed.
        'registration' => [
            'public' => env('PASSBOLT_REGISTRATION_PUBLIC', false)
        ],

        // Activate specific entry points for selenium testing.
        // true will render your installation insecure.
        'selenium' => [
            'active' => env('PASSBOLT_SELENIUM_ACTIVE', false)
        ],

        // Security.
        'security' => [
            'setHeaders' => env('PASSBOLT_SECURITY_SET_HEADERS', true)
        ],

        // Should the app be SSL / HTTPS only.
        // false will render your installation insecure.
        'ssl' => [
            'force' => env('PASSBOLT_SSL_FORCE', true),
        ]
    ],
    // Override the Cake ExceptionRenderer.
    'Error' => [
        'exceptionRenderer' => 'App\Error\AppExceptionRenderer',
    ]
];
