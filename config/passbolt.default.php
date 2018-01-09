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

/**
 * Passbolt configuration options.
 *
 * To see all available options, you can refer to the default.php file, and modify this file accordingly.
 *
 * Read more about how to install passbolt: https://www.passbolt.com/help/tech/install
 * Any issue, check out our FAQ: https://www.passbolt.com/faq
 * A question? Ask the community: https://community.passbolt.com/
 */
return [

    ///////////////////////////////////////////////////////////////////////////////////////////
    // DEFAULT CONFIGURATION
    // This is the default configuration.
    // It enforces the use of ssl, and doesn't provide a default gpg key.
    // If your objective is to try passbolt quickly for demo purpose, you can use the demo config example provided below.
    ///////////////////////////////////////////////////////////////////////////////////////////
    'passbolt' => [
        // Define if users can register by themselves.
        'registration' => [
            'public' => false,
        ],
        // Enforce the use of ssl (requires a https confguration and certificate on your web server).
        'ssl' => [
            'force' => true,
        ],
        // GPG Configuration.
        'gpg' => [
            // Tell GPG where to find the keyring.
            // Needs to be available by the webserver user, for example:
            // Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // Apache on Debian it would be in '/home/www-data/.gnupg'
            // Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
            // etc.
            'keyring' => '/var/lib/nginx/.gnupg',

            // Replace GNUPGHOME with above value even if it is set.
            'putenv' => true,

            // Main server key.
            'serverKey' => [
                // Server private key fingerprint.
                'fingerprint' => '',
                // Server public / private key file location.
                'public' => '',
                'private' => '',
            ],
        ],
    ],

    ///////////////////////////////////////////////////////////////////////////////////////////
    // DEMO CONFIGURATION EXAMPLE.
    // Uncomment the lines below if you want to try passbolt quickly.
    // and if you are not concerned about the security of your installation.
    // (Don't forget to comment the default config above).
    ///////////////////////////////////////////////////////////////////////////////////////////
//  // Activate debug mode.
//  'debug' => true,
//	'passbolt' => [
//		'registration' => [
//			'public' => true
//		],
//		'ssl' => [
//			'force' => false,
//		],
//		'gpg' => [
//			// Tell GPG where to find the keyring
//			// Needs to be available by the webserver user, for example:
//			// Apache on Centos it would be in '/usr/share/httpd/.gnupg'
//			// Apache on Debian it would be in '/home/www-data/.gnupg'
//			// Nginx on Centos it would be in '/var/lib/nginx/.gnupg'
//			// etc.
//			'keyring' => '/var/lib/nginx/.gnupg',
//			'putenv' => true,
//			'serverKey' => [
//				'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
//				'public' => ROOT . DS . 'config' . DS . 'gpg' . DS . 'unsecure.key',
//				'private' => ROOT . DS . 'config' . DS . 'gpg' . DS . 'unsecure_private.key',
//			],
//		],
//	],
    //////////////////////////// END DEMO CONFIGURATION EXAMPLE ////////////////////////////

    // App configuration.
    'App' => [
        // A base URL to use for absolute links.
        // Enter here the url where your passbolt will be reachable.
        'fullBaseUrl' => 'https://enter-here-your-own-passbolt-url.com',
    ],

    // Database configuration.
    'Datasources' => [
        'default' => [
            'host' => 'localhost',
            //'port' => 'non_standard_port_number',
            'username' => 'user',
            'password' => 'secret',
            'database' => 'passbolt',
        ],
    ],

    // Email configuration.
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'timeout' => 30,
            'username' => 'user',
            'password' => 'secret',
            'client' => null,
            'tls' => null,
            'url' => null,
        ],
    ],
];
