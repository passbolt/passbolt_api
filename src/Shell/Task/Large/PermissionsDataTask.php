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
namespace PassboltTestData\Shell\Task\Large;

use App\Model\Entity\Role;
use App\Utility\UuidFactory;
use PassboltTestData\Lib\DataTask;
use PassboltTestData\Lib\PermissionMatrix;

class PermissionsDataTask extends DataTask
{
    public $entityName = 'Permissions';

    /**
     * Get the permissions data
     *
     * @return array
     */
    public function getData()
    {
        $permissions = [];
        $permissions = array_merge($permissions, $this->getUsersResourcesPermissions());

        return $permissions;
    }

    /**
     * Get the resource user permissions association data
     *
     * @return array
     */
    private function getUsersResourcesPermissions()
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $permissions = [];

        $userId = UuidFactory::uuid('user.id.user_0');
        $resources = $this->Resources->find()->all();
        foreach ($resources as $resource) {
            $acoId = $resource->id;
            $aroId = $userId;
            $permissions[] = [
                'id' => UuidFactory::uuid("permission.id.$acoId-$aroId"),
                'aco' => 'Resource',
                'aco_foreign_key' => $acoId,
                'aro' => 'User',
                'aro_foreign_key' => $aroId,
                'type' => 15,
                'created_by' => $userId,
                'modified_by' => $userId
            ];
        }

        return $permissions;
    }
}
