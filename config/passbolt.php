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
        'auth' => [
            'tokenExpiry' => '3 days'
        ],
        'meta' => [
            'description' => 'Open source password manager for teams',
            'robots' => 'noindex, nofollow'
        ],
        'gpg' => [
            'putenv' => true, // replace GNUPGHOME even if it is set
            'keyring' => '/var/lib/nginx/.gnupg',
            'serverKey' => [
                'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
                'public' => CONFIG . 'gpg' . DS . 'unsecure.key',
                'private' => CONFIG . 'gpg' . DS . 'unsecure_private.key',
                'passphrase' => '', // not supported - leave empty
            ]
        ],
        'registration' => [
            'public' => true
        ]
    ]
];
