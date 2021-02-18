<?php
declare(strict_types=1);

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
    [
        'id' => UuidFactory::uuid('ldap.user.id.user1'),
        'directory_name' => 'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'username' => 'user1@passbolt.com',
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'one',
            ],
        ],
    ],
    [
        'id' => UuidFactory::uuid('ldap.user.id.user2'),
        'directory_name' => 'CN=User2,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'username' => 'user2@passbolt.com',
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'two',
            ],
        ],
    ],
    [
        'id' => UuidFactory::uuid('ldap.user.id.user3'),
        'directory_name' => 'CN=User3,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'username' => 'user3@passbolt.com',
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'three',
            ],
        ],
    ],
    [
        'id' => UuidFactory::uuid('ldap.user.id.user4'),
        'directory_name' => 'CN=User4,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'username' => 'user4@passbolt.com',
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'four',
            ],
        ],
    ],
    // These 2 last users are invalid due to a wrong email.
    [
        'id' => UuidFactory::uuid('ldap.user.id.user5'),
        'directory_name' => 'CN=User5,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'five',
            ],
        ],
    ],
    [
        'id' => UuidFactory::uuid('ldap.user.id.user6'),
        'directory_name' => 'CN=User6,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-08-12 03:56:42.000000'),
        'user' => [
            'profile' => [
                'first_name' => 'user',
                'last_name' => 'six',
            ],
        ],
    ],
];
