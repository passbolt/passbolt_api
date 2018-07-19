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
 * @since         2.2.0
 */
use Cake\I18n\FrozenTime;

return [
    // A user that does not exist
    [
        'id' => 'aa1eda4d-b539-469e-b4e6-ebd34c190c90',
        'directory_name' => 'CN=Neil Amstrong,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'user' => [
            'username' => 'neil@passbolt.com',
            'profile' => [
                'first_name' => 'Neil',
                'last_name' => 'Amstrong'
            ]
        ],
    ],
    // A user that exist by have been deleted
    // A user that exist but is not active
    // A user that exist and is active
    [
        'id' => '55872084-ed6f-4e96-b401-479dd86ca357',
        'directory_name' => 'CN=Ada Lovelace,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'user' => [
            'username' => 'ada@passbolt.com',
            'profile' => [
                'first_name' => 'Ada',
                'last_name' => 'Lovelace'
            ]
        ]
    ],
    // A user that exist with the same email but different name and is not active
    // A user that exist with the same email but different name and is already active
    // A user that has been marked to be ignored

    // A user for which the sync failed multiple times
    // A user which email does not validate
    // A user with no email
    // A user with no firstname
    // A user with no lastname

];