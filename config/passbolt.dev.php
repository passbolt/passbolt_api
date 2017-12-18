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
    'passbolt' => [
        'registration' => [
            'public' => true
        ],
        'selenium' => [
            'active' => true
        ],
        'ssl' => [
            'force' => false,
        ],
        // GPG Configuration
        'gpg' => [
            // Tell GPG where to find the keyring
            // Needs to be available by the webserver user, for example:
            // Apache on Centos it would be in '/usr/share/httpd/.gnupg'
            // Apache on Debian it would be in '/var/www/.gnupg/.gnupg'
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
    ]
];
