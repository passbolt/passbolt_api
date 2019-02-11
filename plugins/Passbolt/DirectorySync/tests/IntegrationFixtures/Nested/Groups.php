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
    // A new directory group that does not exist in passbolt
    [
        'id' => UuidFactory::uuid('ldap.group.id.administration'),
        'directory_name' => 'CN=Administration,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'group' => [
            'name' => 'Administration',
            'members' => [
                'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local'
            ],
        ],
    ],

    [
        'id' => UuidFactory::uuid('ldap.group.id.managers'),
        'directory_name' => 'CN=Managers,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'group' => [
            'name' => 'Managers',
            'members' => [
                'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local',
                'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
                'CN=User2,OU=PassboltUsers,DC=passbolt,DC=local',
            ],
        ],
    ],

    [
        'id' => UuidFactory::uuid('ldap.group.id.clevel'),
        'directory_name' => 'CN=CLevel,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'group' => [
            'name' => 'C Level',
            'members' => [
                'CN=User1,OU=PassboltUsers,DC=passbolt,DC=local',
            ],
        ],
    ],

    [
        'id' => UuidFactory::uuid('ldap.group.id.developers'),
        'directory_name' => 'CN=Developers,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'group' => [
            'name' => 'Developers',
            'members' => [
                'CN=User3,OU=PassboltUsers,DC=passbolt,DC=local',
            ],
        ],
    ],
];
