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
    // A new directory group that only contains a user that does not exist in passbolt
    // A new directory group that only contains a user that is not active in passbolt
    // A new directory group that only contains a user that is deleted in passbolt
    // A new directory group that contains one less user than in passbolt
    // A directory group that has been sync, that contains a user that does not exist in the directory
    // A directory group that has been sync, that has a user removed in passbolt but not in directory

    // An existing passbolt group that has not been sync and is empty in ldap
    // An existing passbolt group that has been sync and is empty in ldap
    // An existing passbolt group that has been sync and has empty in ldap

    // etc.

    // A new directory with no name


    // A new directory group that does not exist in passbolt
    [
        'id' => UuidFactory::uuid('ldap.group.id.new4'),
        'directory_name' => 'CN=New4,OU=PassboltUsers,DC=passbolt,DC=local',
        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
        'group' => [
            'name' => 'New4',
            'groups' => [],
            'users' => [
                'CN=Ada Lovelace,OU=PassboltUsers,DC=passbolt,DC=local',
                'CN=Betty Holberton,OU=PassboltUsers,DC=passbolt,DC=local',
                'CN=Steve Shirley,OU=PassboltUsers,DC=passbolt,DC=local',
            ],
        ],
    ],
    // A new directory group that already exist in passbolt
//    [
//        'id' => UuidFactory::uuid('ldap.group.id.developer'),
//        'directory_name' => 'CN=Developer,OU=PassboltUsers,DC=passbolt,DC=local',
//        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'group' => [
//            'name' => 'Developer',
//            'groups' => [],
//            'users' => [
//                'CN=Ada Lovelace,OU=PassboltUsers,DC=passbolt,DC=local',
//            ],
//        ],
//    ],
//
//    // A new directory group that does not exist but is empty
//    [
//        'id' => UuidFactory::uuid('ldap.group.id.empty'),
//        'directory_name' => 'CN=Empty,OU=PassboltUsers,DC=passbolt,DC=local',
//        'directory_created' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'directory_modified' => new FrozenTime('2018-07-09 03:56:42.000000'),
//        'group' => [
//            'name' => 'Empty',
//            'groups' => [],
//            'users' => [],
//        ],
//    ],
];
