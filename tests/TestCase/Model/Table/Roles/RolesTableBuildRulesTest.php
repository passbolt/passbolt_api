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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Model\Table\Roles;

use App\Model\Entity\Role;
use App\Model\Table\RolesTable;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class RolesTableBuildRulesTest extends AppTestCase
{
    public ?RolesTable $Roles;

    public function setUp(): void
    {
        parent::setUp();
        $this->Roles = TableRegistry::getTableLocator()->get('Roles');
    }

    public function tearDown(): void
    {
        unset($this->Roles);
        parent::tearDown();
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInCreatedBy_Fail(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $data = [
            'name' => 'sales',
            'description' => null,
            'created_by' => UuidFactory::uuid(),
            'modified_by' => $user->id,
        ];

        $role = $this->Roles->newEntity($data, ['accessibleFields' => [
            'name' => true,
            'description' => true,
            'created_by' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertFalse($result);
        $this->assertTrue(Hash::check($role->getErrors(), 'created_by.creator_exists'));
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInCreatedBy_Pass(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->active()->persist();
        $data = [
            'name' => 'sales',
            'description' => null,
            'created_by' => $user->id,
            'modified_by' => $user->id,
        ];

        $role = $this->Roles->newEntity($data, ['accessibleFields' => [
            'name' => true,
            'description' => true,
            'created_by' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertInstanceOf(Role::class, $result);
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInCreatedBy_Pass_DisabledUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->persist();
        $data = [
            'name' => 'sales',
            'description' => null,
            'created_by' => $user->id,
            'modified_by' => $user->id,
        ];

        $role = $this->Roles->newEntity($data, ['accessibleFields' => [
            'name' => true,
            'description' => true,
            'created_by' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertInstanceOf(Role::class, $result);
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInModifiedBy_FailOnCreate(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $data = [
            'name' => 'sales',
            'description' => null,
            'created_by' => $user->id,
            'modified_by' => UuidFactory::uuid(),
        ];

        $role = $this->Roles->newEntity($data, ['accessibleFields' => [
            'name' => true,
            'description' => true,
            'created_by' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertFalse($result);
        $this->assertTrue(Hash::check($role->getErrors(), 'modified_by.modifier_exists'));
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInModifiedBy_FailOnUpdate(): void
    {
        $role = RoleFactory::make()->persist();
        $data = [
            'name' => 'sales',
            'modified_by' => UuidFactory::uuid(),
        ];

        $role = $this->Roles->patchEntity($role, $data, ['accessibleFields' => [
            'name' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertFalse($result);
        $this->assertTrue(Hash::check($role->getErrors(), 'modified_by.modifier_exists'));
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInModifiedBy_Pass(): void
    {
        $role = RoleFactory::make()->persist();
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->persist();
        $data = [
            'name' => 'sales',
            'modified_by' => $user->id,
        ];

        $role = $this->Roles->patchEntity($role, $data, ['accessibleFields' => [
            'name' => true,
            'modified_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertInstanceOf(Role::class, $result);
    }

    /**
     * @covers \App\Model\Table\RolesTable::buildRules()
     * @return void
     */
    public function testRolesTableBuildRules_ExistsInDeletedBy_Fail(): void
    {
        $role = RoleFactory::make()->persist();
        $data = [
            'deleted' => DateTime::now(),
            'deleted_by' => UuidFactory::uuid(),
        ];

        $role = $this->Roles->patchEntity($role, $data, ['accessibleFields' => [
            'deleted' => true,
            'deleted_by' => true,
        ]]);
        $result = $this->Roles->save($role);

        $this->assertFalse($result);
    }
}
