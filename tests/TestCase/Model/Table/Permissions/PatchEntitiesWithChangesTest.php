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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class PatchEntitiesWithChangesTest extends AppTestCase
{
    public $Resources;
    public $Permissions;

    public $fixtures = ['app.Base/Permissions', 'app.Base/Resources'];

    public function setUp()
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
    }

    public function tearDown()
    {
        unset($this->Resources);
        unset($this->Permissions);

        parent::tearDown();
    }

    public function testValidationSuccessOnUpdatePermission()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $data = [
            ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'type' => Permission::READ],
            ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'type' => Permission::OWNER]
        ];

        // Retrieve the resource and its permissions to share.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions']]);

        // Patch the resource permissions.
        try {
            $resource->permissions = $this->Permissions->patchEntitiesWithChanges($resource->permissions, $data, $resource->id);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $resource->getErrors();
        $this->assertEmpty($errors);

        // The permission of ada is well updated.
        $extract = Hash::extract($resource->permissions, "{n}[id={$data[0]['id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals('Resource', $extract[0]['aco']);
        $this->assertEquals($resource->id, $extract[0]['aco_foreign_key']);
        $this->assertEquals(UuidFactory::uuid('user.id.ada'), $extract[0]['aro_foreign_key']);
        $this->assertEquals('User', $extract[0]['aro']);
        $this->assertEquals($data[0]['type'], $extract[0]['type']);

        // The permission of betty is well updated.
        $extract = Hash::extract($resource->permissions, "{n}[id={$data[1]['id']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals('Resource', $extract[0]['aco']);
        $this->assertEquals($resource->id, $extract[0]['aco_foreign_key']);
        $this->assertEquals(UuidFactory::uuid('user.id.betty'), $extract[0]['aro_foreign_key']);
        $this->assertEquals('User', $extract[0]['aro']);
        $this->assertEquals($data[1]['type'], $extract[0]['type']);
    }

    public function testValidationSuccessOnDeletePermission()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $data = [
            ['id' => UuidFactory::uuid("permission.id.$resourceId-$userAId"), 'delete' => true],
            ['id' => UuidFactory::uuid("permission.id.$resourceId-$userBId"), 'delete' => true]
        ];

        // Retrieve the resource and its permissions to share.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions']]);

        // Patch the resource permissions.
        try {
            $resource->permissions = $this->Permissions->patchEntitiesWithChanges($resource->permissions, $data, $resource->id);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $resource->getErrors();
        $this->assertEmpty($errors);

        // The permission of ada is well updated.
        $extract = Hash::extract($resource->permissions, "{n}[id={$data[0]['id']}]");
        $this->assertEmpty($extract);

        // The permission of betty is well updated.
        $extract = Hash::extract($resource->permissions, "{n}[id={$data[1]['id']}]");
        $this->assertEmpty($extract);
    }

    public function testValidationSuccessOnAddPermission()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userEId = UuidFactory::uuid('user.id.edith');
        $groupFId = UuidFactory::uuid('group.id.freelancer');
        $data = [
            ['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER],
            ['aro' => 'Group', 'aro_foreign_key' => $groupFId, 'type' => Permission::READ]
        ];

        // Retrieve the resource and its permissions to share.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions']]);

        // Patch the resource permissions.
        try {
            $resource->permissions = $this->Permissions->patchEntitiesWithChanges($resource->permissions, $data, $resource->id);
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertEmpty($errors, 'Expect no error ' . json_encode($errors));
        }

        // Assert there is no error, and the changes are well applied.
        $errors = $resource->getErrors();
        $this->assertEmpty($errors);

        // The permission of edith is there and correct.
        $extract = Hash::extract($resource->permissions, "{n}[aro_foreign_key={$data[0]['aro_foreign_key']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals('Resource', $extract[0]['aco']);
        $this->assertEquals($resource->id, $extract[0]['aco_foreign_key']);
        $this->assertEquals(UuidFactory::uuid('user.id.edith'), $extract[0]['aro_foreign_key']);
        $this->assertEquals('User', $extract[0]['aro']);
        $this->assertEquals($data[0]['type'], $extract[0]['type']);

        // The permission of freelancer is there and correct.
        $extract = Hash::extract($resource->permissions, "{n}[aro_foreign_key={$data[1]['aro_foreign_key']}]");
        $this->assertNotEmpty($extract);
        $this->assertEquals('Resource', $extract[0]['aco']);
        $this->assertEquals($resource->id, $extract[0]['aco_foreign_key']);
        $this->assertEquals(UuidFactory::uuid('group.id.freelancer'), $extract[0]['aro_foreign_key']);
        $this->assertEquals('Group', $extract[0]['aro']);
        $this->assertEquals($data[1]['type'], $extract[0]['type']);
    }

    public function testValidationErrorOnUpdatePermission()
    {
        $resourceApacheId = UuidFactory::uuid('resource.id.apache');
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $testCases = [
            'permission does not exist' => [
                'errorField' => '0.id.permission_exists',
                'data' => [['id' => UuidFactory::uuid()]]
            ],
            'permission relative to another resource' => [
                'errorField' => '0.id.permission_exists',
                'data' => [['id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userBId")]]
            ],
            'permission type cannot be empty' => [
                'errorField' => '0.type._empty',
                'data' => [[
                    'id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"),
                    'type' => null]]
            ],
            'permission type is invalid' => [
                'errorField' => '0.type.inList',
                'data' => [[
                    'id' => UuidFactory::uuid("permission.id.$resourceApacheId-$userAId"),
                    'type' => 42]]
            ],
        ];

        $this->_executeErrorCases($testCases, $resourceApacheId);
    }

    public function testValidationErrorOnDeletePermission()
    {
        $resourceAprilId = UuidFactory::uuid('resource.id.april');
        $resourceApacheId = UuidFactory::uuid('resource.id.apache');
        $userBId = UuidFactory::uuid('user.id.betty');
        $testCases = [
            'permission does not exist' => [
                'errorField' => '0.id.permission_exists',
                'data' => [['id' => UuidFactory::uuid(), 'delete' => true]]
            ],
            'permission relative to another resource' => [
                'errorField' => '0.id.permission_exists',
                'data' => [['id' => UuidFactory::uuid("permission.id.$resourceAprilId-$userBId"), 'delete' => true]]
            ],
        ];

        $this->_executeErrorCases($testCases, $resourceApacheId);
    }

    public function testValidationErrorOnAddPermission()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $userEId = UuidFactory::uuid('user.id.edith');
        $testCases = [
            'aro is required' => [
                'errorField' => '0.aro._required',
                'data' => [['aro_foreign_key' => $userEId, 'type' => Permission::OWNER]]
            ],
            'aro cannot be empty' => [
                'errorField' => '0.aro._empty',
                'data' => [['aro' => null, 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER]]
            ],
            'aro is invalid' => [
                'errorField' => '0.aro.inList',
                'data' => [['aro' => 'BADARO', 'aro_foreign_key' => $userEId, 'type' => Permission::OWNER]]
            ],
            'aro_foreign_key is required' => [
                'errorField' => '0.aro_foreign_key._required',
                'data' => [['aro' => 'User', 'type' => Permission::OWNER]]
            ],
            'aro_foreign_key cannot be empty' => [
                'errorField' => '0.aro_foreign_key._empty',
                'data' => [['aro' => 'User', 'aro_foreign_key' => null, 'type' => Permission::OWNER]]
            ],
            'aro_foreign_key is invalid' => [
                'errorField' => '0.aro_foreign_key.uuid',
                'data' => [['aro' => 'User', 'aro_foreign_key' => 'invalid-uuid', 'type' => Permission::OWNER]]
            ],
            'permission type is required' => [
                'errorField' => '0.type._required',
                'data' => [['aro' => 'User', 'aro_foreign_key' => $userEId]]
            ],
            'permission type cannot be empty' => [
                'errorField' => '0.type._empty',
                'data' => [['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => null]]
            ],
            'permission type is invalid' => [
                'errorField' => '0.type.inList',
                'data' => [['aro' => 'User', 'aro_foreign_key' => $userEId, 'type' => 42]]
            ],
        ];

        $this->_executeErrorCases($testCases, $resourceId);
    }

    protected function _executeErrorCases($testCases, $resourceId)
    {
        foreach ($testCases as $caseLabel => $case) {
            $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions']]);
            try {
                $resource->permissions = $this->Permissions->patchEntitiesWithChanges($resource->permissions, $case['data'], $resource->id);
                $this->assertFalse(false, 'Expect an exception');
            } catch (CustomValidationException $e) {
                $this->assertEntityError($e, $case['errorField']);
            }
        }
    }
}
