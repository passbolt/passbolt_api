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
namespace App\Test\Lib\Model;

use App\Model\Entity\Permission;
use App\Utility\UuidFactory;

trait PermissionsModelTrait
{

    /**
     * Get a dummy permission with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyPermission($data = [])
    {
        $entityContent = [
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'type' => Permission::OWNER,
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Add permission.
     * @param string $aco Aco
     * @param string $aco_foreign_key Target aco
     * @param string $aro Aro
     * @param string $aro_foreign_key Target aro
     * @param int $type The type of permissions
     */
    public function addPermission($aco, $aco_foreign_key, $aro, $aro_foreign_key, $type = Permission::OWNER)
    {
        $saveOptions = [
            'validate' => 'default',
            'accessibleFields' => [
                '*' => true
            ],
        ];
        $data = [
            'aco' => $aco,
            'aco_foreign_key' => $aco_foreign_key,
            'aro' => $aro,
            'aro_foreign_key' => $aro_foreign_key,
            'type' => $type
        ];
        $permission = $this->Permissions->newEntity($data, $saveOptions);
        $this->Permissions->save($permission);

        return $permission;
    }

    /**
     * Asserts that an object has all the attributes a permission should have.
     *
     * @param object $permission
     */
    protected function assertPermissionAttributes($permission)
    {
        $attributes = ['id', 'aro', 'aro_foreign_key', 'aco', 'aco_foreign_key', 'type', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $permission);
    }

    /**
     * Assert an aro has the expected permission for a given aco
     * @param string $acoForeignKey
     * @param string $aroForeignKey
     * @param string $type
     */
    protected function assertPermission($acoForeignKey, $aroForeignKey, $type)
    {
        $permission = $this->Permissions->find()->where([
            'aco_foreign_key' => $acoForeignKey,
            'aro_foreign_key' => $aroForeignKey,
            'type' => $type,
        ])->first();
        $this->assertNotEmpty($permission);
    }

    /**
     * Assert a permission does not exist
     * @param $acoForeignKey
     * @param $aroForeignKey
     */
    protected function assertPermissionNotExist($acoForeignKey, $aroForeignKey)
    {
        $permission = $this->Permissions->find()->where(['aco_foreign_key' => $acoForeignKey, 'aro_foreign_key' => $aroForeignKey])->first();
        $this->assertEmpty($permission);
    }
}
