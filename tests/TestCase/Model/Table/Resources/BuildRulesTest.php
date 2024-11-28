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
 * @since         4.11.0
 */

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Entity\Permission;
use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \App\Model\Table\ResourcesTable
 */
class BuildRulesTest extends AppTestCase
{
    use GpgMetadataKeysTestTrait;

    private $encryptedSecret;

    private function getDefaultNewEntityOptions(): array
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'username' => true,
                'uri' => true,
                'description' => true,
                'created_by' => true,
                'modified_by' => true,
                'secrets' => true,
                'permissions' => true,
                'resource_type_id' => true,
            ],
            'associated' => [
                'Permissions' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'aco' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true,
                    ],
                ],
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'user_id' => true,
                        'data' => true,
                    ],
                ],
            ],
        ];
    }

    private function getDefaultResource(User $user): array
    {
        return [
            'name' => 'New resource name',
            'username' => 'username@domain.com',
            'uri' => 'https://www.domain.com',
            'description' => 'New resource description',
            'resource_type_id' => UuidFactory::uuid5('resource-types.id.password-string'),
            'created_by' => $user->get('id'),
            'modified_by' => $user->get('id'),
        ];
    }

    private function getDefaultPermission(User $user): array
    {
        return [[
            'aco' => 'Resource',
            'aro' => 'User',
            'aro_foreign_key' => $user->get('id'),
            'type' => Permission::OWNER,
        ]];
    }

    private function getDefaultSecret(User $user, $turbo = true): array
    {
        if (!isset($this->encryptedSecret) || !$turbo) {
            $clearTextSecret = json_encode('password string');
            $encryptedSecret = $this->encryptForUser($clearTextSecret, $user, $this->getAdaNoPassphraseKeyInfo());

            $this->encryptedSecret = [[
                'user_id' => $user->get('id'),
                'data' => $encryptedSecret,
            ]];
        }

        return $this->encryptedSecret;
    }

    public function testResourcesTable_BuildRules_Success()
    {
        ResourceTypeFactory::make()->passwordString()->persist();
        $user = UserFactory::make()->withAdaKey()->user()->active()->persist();
        $userId = $user->get('id');
        $data = array_merge($this->getDefaultResource($user), [
            'permissions' => $this->getDefaultPermission($user),
            'secrets' => $this->getDefaultSecret($user),
        ]);

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $entity = $table->newEntity($data, $this->getDefaultNewEntityOptions());
        $save = $table->save($entity);
        $this->assertEquals([], $save->getErrors());

        // Check that the resource and its sub-models are saved as expected.
        $resource = $table->find()
            ->contain('Creator')
            ->contain('Modifier')
            ->contain('Secrets')
            ->contain('Permissions')
            ->where(['Resources.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals(false, $resource->deleted);
        $this->assertEquals($userId, $resource->created_by);
        $this->assertEquals($userId, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($userId, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($userId, $resource->modifier->id);

        // Check the permission attribute
        $this->assertNotEmpty($resource->permissions);
        $this->assertCount(1, $resource->permissions);
        $permission = $resource->permissions[0];
        $this->assertPermissionAttributes($permission);
        $this->assertEquals($data['permissions'][0]['aco'], $permission->aco);
        $this->assertEquals($save->id, $permission->aco_foreign_key);
        $this->assertEquals($data['permissions'][0]['aro'], $permission->aro);
        $this->assertEquals($userId, $permission->aro_foreign_key);
        $this->assertEquals($data['permissions'][0]['type'], $permission->type);

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
        $this->assertCount(1, $resource->secrets);
        $this->assertEquals($data['secrets'][0]['user_id'], $resource->secrets[0]->user_id);
        $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);
    }

    public function testResourcesTable_BuildRules_Error_ResourceTypeDoesNotExist()
    {
        $user = UserFactory::make()->withAdaKey()->user()->active()->persist();
        $data = array_merge($this->getDefaultResource($user), [
            'permissions' => $this->getDefaultPermission($user),
            'secrets' => $this->getDefaultSecret($user),
        ]);

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $entity = $table->newEntity($data, $this->getDefaultNewEntityOptions());
        $save = $table->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_type_id']['resource_type_exists']);
    }

    public function testResourcesTable_BuildRules_Error_OwnerSecretProvided()
    {
        ResourceTypeFactory::make()->passwordString()->persist();
        $user = UserFactory::make()->withAdaKey()->user()->active()->persist();
        $secrets = $this->getDefaultSecret($user);
        $secrets[0]['user_id'] = UuidFactory::uuid5('user.id.betty');
        $data = array_merge($this->getDefaultResource($user), [
            'permissions' => $this->getDefaultPermission($user),
            'secrets' => $secrets,
        ]);

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $entity = $table->newEntity($data, $this->getDefaultNewEntityOptions());
        $save = $table->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['secrets']['owner_secret_provided']);
    }

    public function testResourcesTable_BuildRules_Error_OwnerPermissionProvided()
    {
        ResourceTypeFactory::make()->passwordString()->persist();
        $user = UserFactory::make()->withAdaKey()->user()->active()->persist();
        $data = array_merge($this->getDefaultResource($user), [
            'permissions' => [[
                'aco' => 'Resource',
                'aro' => 'User',
                'aro_foreign_key' => $user->get('id'),
                'type' => Permission::READ,
            ]],
            'secrets' => $this->getDefaultSecret($user),
        ]);

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $entity = $table->newEntity($data, $this->getDefaultNewEntityOptions());
        $save = $table->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['permissions']['owner_permission_provided']);
    }

    public function testResourcesTable_BuildRules_Error_OtherPermissionsProvided()
    {
        ResourceTypeFactory::make()->passwordString()->persist();
        $user = UserFactory::make()->withAdaKey()->user()->active()->persist();
        $secret = $this->getDefaultSecret($user);
        $data = array_merge($this->getDefaultResource($user), [
            'permissions' => [[
                'aco' => 'Resource',
                'aro' => 'User',
                'aro_foreign_key' => $user->get('id'),
                'type' => Permission::OWNER,
            ],[
                'aco' => 'Resource',
                'aro' => 'User',
                'aro_foreign_key' => UuidFactory::uuid5('user.id.betty'),
                'type' => Permission::OWNER,
            ]],
            'secrets' => [$secret, $secret],
        ]);

        /** @var \App\Model\Table\ResourcesTable $table */
        $table = TableRegistry::getTableLocator()->get('Resources');
        $entity = $table->newEntity($data, $this->getDefaultNewEntityOptions());
        $save = $table->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['permissions']['hasAtMost']);
        $this->assertNotNull($errors['secrets']['hasAtMost']);
    }
}
