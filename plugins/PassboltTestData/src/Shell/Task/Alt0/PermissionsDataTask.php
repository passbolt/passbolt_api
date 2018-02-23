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
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.dame'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.bower'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.frances'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.centos'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.canjs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.cakephp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.chai'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.composer'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.debian'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.docker'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.freelancer'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.enlightenment'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fosdem'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.developer'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.fsfe'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.ftp'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grogle'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.grunt'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergnomom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.gnupg'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergnomom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.git'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.creative'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.inkscape'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'type' => Permission::UPDATE,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.ergonom'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.kde'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];
        $permissions[] = [
            'id' => UuidFactory::uuid(),
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.kde'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.board'),
            'type' => Permission::READ,
            'created' => '2017-11-17 12:37:04',
            'modified' => '2017-11-17 12:37:04'
        ];

        return $permissions;
    }
}
