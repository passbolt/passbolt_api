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

use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;

return [
    // A user that does not exist in passbolt and is valid
    [
        'id' => UuidFactory::uuid('ldap.user.id.neil'),
        'directory_name' => 'CN=Neil Amstrong,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'username' => 'neil@passbolt.com',
            'profile' => [
                'first_name' => 'Neil',
                'last_name' => 'Amstrong'
            ]
        ],
    ],
    // A user that already exist
    [
        'id' => UuidFactory::uuid('ldap.user.id.ada'),
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
    [
        'id' => UuidFactory::uuid('ldap.user.id.betty'),
        'directory_name' => 'CN=Betty Holberton,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'user' => [
            'username' => 'betty@passbolt.com',
            'profile' => [
                'first_name' => 'Betty',
                'last_name' => 'Holberton'
            ]
        ]
    ],
    // A user that does not exist and is not valid
//    [
//        'id' => UuidFactory::uuid('ldap.user.id.nope'),
//        'directory_name' => 'CN=No First Name,OU=PassboltUsers,DC=passbolt,DC=local',
//        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'user' => [
//            'username' => 'nope@passbolt.com',
//            'profile' => [
//                'first_name' => null,
//                'last_name' => 'No first name'
//            ]
//        ]
//    ],
    // A user that does not exist and is not valid
//    [
//        'id' => UuidFactory::uuid('ldap.user.id.nope2'),
//        'directory_name' => 'CN=No Email,OU=PassboltUsers,DC=passbolt,DC=local',
//        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'user' => [
//            'username' => null,
//            'profile' => [
//                'first_name' => 'No',
//                'last_name' => 'Email'
//            ]
//        ]
//    ],
    // A user that exist with a different name
//    [
//        'id' => UuidFactory::uuid('ldap.user.id.steve'),
//        'directory_name' => 'CN=Steve Shirley,OU=PassboltUsers,DC=passbolt,DC=local',
//        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'user' => [
//            'username' => 'dame@passbolt.com',
//            'profile' => [
//                'first_name' => 'Steve',
//                'last_name' => 'Shirley'
//            ]
//        ]
//    ],
    // A user that exist but have been deleted in passbolt
    [
        'id' => UuidFactory::uuid('ldap.user.id.sofia'),
        'directory_name' => 'CN=Sofia,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'user' => [
            'username' => 'sofia@passbolt.com',
            'profile' => [
                'first_name' => 'Sofia',
                'last_name' => 'Kovalevskaya'
            ]
        ]
    ],
];
