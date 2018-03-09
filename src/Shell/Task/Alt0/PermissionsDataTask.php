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
namespace PassboltTestData\Shell\Task\Alt0;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;

class PermissionsDataTask extends \PassboltTestData\Shell\Task\Base\PermissionsDataTask
{
    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $permissions = [];

        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.apache'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:05',
            'modified' => '2017-11-17 12:37:05'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:06',
            'modified' => '2017-11-17 12:37:06'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:07',
            'modified' => '2017-11-17 12:37:07'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.dame'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:08',
            'modified' => '2017-11-17 12:37:08'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.frances'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:09',
            'modified' => '2017-11-17 12:37:09'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:10',
            'modified' => '2017-11-17 12:37:10'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:11',
            'modified' => '2017-11-17 12:37:11'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:12',
            'modified' => '2017-11-17 12:37:12'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:13',
            'modified' => '2017-11-17 12:37:13'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:14',
            'modified' => '2017-11-17 12:37:14'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.cakephp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.chai'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:15',
            'modified' => '2017-11-17 12:37:15'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.composer'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:16',
            'modified' => '2017-11-17 12:37:16'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.debian'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:17',
            'modified' => '2017-11-17 12:37:17'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:18',
            'modified' => '2017-11-17 12:37:18'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.freelancer'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:19',
            'modified' => '2017-11-17 12:37:19'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:20',
            'modified' => '2017-11-17 12:37:20'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:21',
            'modified' => '2017-11-17 12:37:21'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:22',
            'modified' => '2017-11-17 12:37:22'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:23',
            'modified' => '2017-11-17 12:37:23'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:24',
            'modified' => '2017-11-17 12:37:24'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:25',
            'modified' => '2017-11-17 12:37:25'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:26',
            'modified' => '2017-11-17 12:37:26'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:27',
            'modified' => '2017-11-17 12:37:27'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:28',
            'modified' => '2017-11-17 12:37:28'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:29',
            'modified' => '2017-11-17 12:37:29'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:30',
            'modified' => '2017-11-17 12:37:30'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:31',
            'modified' => '2017-11-17 12:37:31'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:32',
            'modified' => '2017-11-17 12:37:32'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:34',
            'modified' => '2017-11-17 12:37:34'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:35',
            'modified' => '2017-11-17 12:37:35'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:36',
            'modified' => '2017-11-17 12:37:36'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:37',
            'modified' => '2017-11-17 12:37:37'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:38',
            'modified' => '2017-11-17 12:37:38'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:39',
            'modified' => '2017-11-17 12:37:39'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:40',
            'modified' => '2017-11-17 12:37:40'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:41',
            'modified' => '2017-11-17 12:37:41'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:42',
            'modified' => '2017-11-17 12:37:42'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:43',
            'modified' => '2017-11-17 12:37:43'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:44',
            'modified' => '2017-11-17 12:37:44'
        ];

        return $permissions;
    }
}
