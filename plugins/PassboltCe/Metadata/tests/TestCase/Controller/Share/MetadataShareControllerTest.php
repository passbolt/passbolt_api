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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Controller\Share;

use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCaseV5;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Metadata\Test\Utility\GpgMetadataKeysTestTrait;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \App\Controller\Share\ShareController
 */
class MetadataShareControllerTest extends AppIntegrationTestCaseV5
{
    use GpgMetadataKeysTestTrait;

    public function testMetadataShareController_Error_InvalidMetadataKeyType(): void
    {
        $user = UserFactory::make()->user()->persist();
        $user2 = UserFactory::make()->user()->persist();
        $user3 = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields()
            ->withCreatorAndPermission($user)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->persist();
        PermissionFactory::make()->acoResource($resource)->typeRead()->aroUser($user2)->persist();
        $this->logInAs($user);

        $resourceId = $resource->id;
        $data = [
            'permissions' => [
                ['id' => $resource->permission->id, 'type' => Permission::READ],
                ['aro' => 'User', 'aro_foreign_key' => $user3->get('id'), 'type' => Permission::OWNER],
            ],
        ];
        $this->putJson("/share/resource/{$resourceId}.json", $data);

        $this->assertBadRequestError('Resource metadata key type is invalid');
    }

    public function testMetadataShareController_Success(): void
    {
        RoleFactory::make()->guest()->persist();
        $user = UserFactory::make()->user()->persist();
        $user2 = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->withCreatorAndPermission($user)
            ->with('ResourceTypes', ResourceTypeFactory::make()->v5Default())
            ->persist();
        $this->logInAs($user);

        $resourceId = $resource->id;
        $data = [
            'permissions' => [
                ['aro' => 'User', 'aro_foreign_key' => $user2->get('id'), 'type' => Permission::OWNER],
            ],
            'secrets' => [
                ['user_id' => $user2->get('id'), 'data' => $this->getDummyPrivateKeyOpenPGPMessage()],
            ],
        ];
        $this->putJson("/share/resource/{$resourceId}.json", $data);

        $this->assertSuccess();
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $hasAccessUsers = $usersTable->findIndex(Role::USER, ['filter' => ['has-access' => [$resourceId]]])->all()->toArray();
        $hasAccessUsersIds = Hash::extract($hasAccessUsers, '{n}.id');
        $this->assertCount(2, $hasAccessUsersIds);
        $this->assertTrue(in_array($user->get('id'), $hasAccessUsersIds));
        $this->assertTrue(in_array($user2->get('id'), $hasAccessUsersIds));
    }
}
