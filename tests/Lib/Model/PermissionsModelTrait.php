<?php
declare(strict_types=1);

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
use Cake\ORM\TableRegistry;

trait PermissionsModelTrait
{
    /**
     * Get a dummy permission with test data.
     * The comment returned passes a default validation.
     *
     * @param array|null $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyPermission(?array $data = [])
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
     *
     * @param string $aco Aco
     * @param string $acoForeignKey Target aco
     * @param string $aro Aro
     * @param string $aroForeignKey Target aro
     * @param int $type The type of permissions
     * @return Permission
     */
    public function addPermission(string $aco, string $acoForeignKey, ?string $aro = null, string $aroForeignKey, int $type = Permission::OWNER)
    {
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $saveOptions = [
            'validate' => 'default',
            'accessibleFields' => [
                '*' => true,
            ],
        ];
        // If aro is not given, then try to determine it.
        if (is_null($aro)) {
            $groupsTable = TableRegistry::getTableLocator()->get('Groups');
            $aro = $groupsTable->exists(['id' => $aroForeignKey]) ? 'Group' : 'User';
        }
        $data = [
            'id' => UuidFactory::uuid("permission.id.{$acoForeignKey}-{$aroForeignKey}"),
            'aco' => $aco,
            'aco_foreign_key' => $acoForeignKey,
            'aro' => $aro,
            'aro_foreign_key' => $aroForeignKey,
            'type' => $type,
        ];
        $permission = $permissionsTable->newEntity($data, $saveOptions);
        $permissionsTable->saveOrFail($permission, ['checkRules' => false]);

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
     *
     * @param string $acoForeignKey
     * @param string $aroForeignKey
     * @param string $type
     */
    protected function assertPermission($acoForeignKey, $aroForeignKey, $type)
    {
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $permission = $permissionsTable->find()->where([
            'aco_foreign_key' => $acoForeignKey,
            'aro_foreign_key' => $aroForeignKey,
            'type' => $type,
        ])->first();
        $this->assertNotEmpty($permission);
    }

    /**
     * Assert a permission does not exist
     *
     * @param $acoForeignKey
     * @param $aroForeignKey
     */
    protected function assertPermissionNotExist($acoForeignKey, $aroForeignKey)
    {
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $permission = $permissionsTable->find()->where(['aco_foreign_key' => $acoForeignKey, 'aro_foreign_key' => $aroForeignKey])->first();
        $this->assertEmpty($permission);
    }

    /**
     * Assert that an aro has an expected computed access.
     *
     * @param string $aco
     * @param string $acoForeignKey
     * @param string $aroForeignKey
     * @param string $type
     */
    protected function assertComputedAccess($aco, $acoForeignKey, $aroForeignKey, $type)
    {
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $this->assertTrue($permissionsTable->hasAccess($aco, $acoForeignKey, $aroForeignKey, $type));
    }
}
