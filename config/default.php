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
     * Passbolt application default configuration
     * In alphabetical order:
     * - Analytics
     * - Authentication
     * - Anonymous Statistics
     * - Email notifications
     * - Javascript application config
     * - Meta HTML tags
     * - Gpg
     * - Registration settings
     * - Selenium mode
     * - SSL
     *
     * Pick a section a place it in your passbolt.php file to replace default settings
     * Do not modify directly the values bellow as it will break passbolt update process.
     *
     */
    'passbolt' => [

        // Analytics configuration
        'analytics' => [
            'piwik' => [
                // Provide this url to activate tracking.
                // 'url' => ''
            ],
        ],

        // Authentication & Authorisation
        'auth' => [
            'tokenExpiry' => '3 days'
        ],

        // Anonymous statistics configuration.
        'anonymousStatistics' => [
            // Url where data will be sent.
            'url' => 'https://www.passbolt.com/statistics/install.json',
            // Help url.
            'help' => 'https://www.passbolt.com/privacy#statistics',
            // Whether to send the anonymous statistics or not.
            'send' => false,
            // Instance ID. (will be populated at installation).
            'instanceId' => '',
        ],

        // Email notification settings
        'emailNotification' => [
            // Allow to disable displaying the armored secret in the email
            // WARNING: make sure you have backups in place if you disable these
            // see. https://www.passbolt.com/help/tech/backup
            'show' => [
                'secret' => true,
                'username' => true
            ],
            // Choose which emails are sent system wide
            'send' => [
                'password' => [
                    'share' => true,
                    'comment' => true,
                    'create' => true,
                    'update' => true,
                    'delete' => true,
                ],
                'user' => [
                    // WARNING: disabling these will prevent user from signing up
                    'create' => true,
                    'recover' => true,
                ],
                'group' => [
                    'user' => [
                        'add' => true,
                        'delete' => true,
                        'update' => true,
                    ],
                    'manager' => [
                        // notify manager when group user is updated / deleted
                        'update' => true,
                        'delete' => true,
                    ]
                ]
            ]
        ],

        // build | options : development or production.
        // development will load the non compiled version,
        // production will load the compiled passbolt.js file.
        'js' => [
            'build' => 'production'
        ],

        // Html meta information
        'meta' => [
            'title' => 'Passbolt',
            'description' => 'Open source password manager for teams',
            // Do you want search engine robots to index your site
            // Default is set to false
            'robots' => 'noindex, nofollow'
        ],

        // GPG Configuration
        'gpg' => [
            // Tell GPG where to find the keyring
            // Needs to be available by the webserver user, for example:
            // Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // Apache on Debian it would be in '/home/www-data/.gnupg'
            // Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
            // etc.
            'keyring' => '/home/www-data/.gnupg',

            // replace GNUPGHOME with above value even if it is set
            'putenv' => true,

            // Main server key
            'serverKey' => [
                // Server private key location and fingerprint
                'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
                'public' => ROOT . DS . 'config' . DS . 'gpg' . DS . 'unsecure.key',
                'private' => ROOT . DS . 'config' . DS . 'gpg' . DS . 'unsecure_private.key',

                // PHP Gnupg module currently does not support passphrase, please leave blank
                'passphrase' => ''
            ],
        ],

        // Is public registration allowed
        'registration' => [
            'public' => false
        ],

        // Activate specific entry points for selenium testing.
        // true will render your installation insecure
        'selenium' => [
            'active' => false
        ],

        // Should the app be SSL / HTTPS only
        // false will render your installation insecure
        'ssl' => [
            'force' => true,
        ],
    ]
];
