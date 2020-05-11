<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace PassboltTestData\Shell\Task\Pro;

use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;

class PermissionsDataTask extends DataTask
{
    protected $_truncate = false;
    public $entityName = 'Permissions';

    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $permissions = [];

        foreach ($this->_getData() as $row) {
            $adminUserId = UuidFactory::uuid('user.id.admin');
            $permissions[] = [
                'id' => UuidFactory::uuid("permission.id.{$row['aco_foreign_key']}-{$row['aro_foreign_key']}"),
                'aco' => $row['aco'],
                'aco_foreign_key' => $row['aco_foreign_key'],
                'aro' => $row['aro'],
                'aro_foreign_key' => $row['aro_foreign_key'],
                'type' => $row['type'],
                'created_by' => $adminUserId,
                'modified_by' => $adminUserId
            ];
        }

        return $permissions;
    }

    private function _getData()
    {
        // Admin
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.accounting'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.bank'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.credit-cards'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.vat'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.communication'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.blogs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 1
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.social-networks'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.human-resources'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.it'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.certificates'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.continuous-integration'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.licenses'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.private-admin'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.production'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.staging'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.marketing'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.sales'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.travel'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.admin'),
            'type' => 15
        ];

        // Ada
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.accounting'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 1
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.bank'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 1
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.credit-cards'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 7
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.vat'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.communication'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.blogs'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 1
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.social-networks'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.human-resources'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.it'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.certificates'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.continuous-integration'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.licenses'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.private-ada'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.production'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.staging'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.marketing'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.sales'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];
        $permissions[] = [
            'aco' => 'Folder',
            'aco_foreign_key' => UuidFactory::uuid('folder.id.travel'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => 15
        ];

        return $permissions;
    }
}
