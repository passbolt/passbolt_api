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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Service\Resources;

use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * \App\Service\Resources\ResourcesUpdateService Test Case
 *
 * @covers \App\Service\Resources\ResourcesUpdateService
 */
class ResourcesUpdateServiceTest extends AppTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public array $fixtures = [
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    /**
     * @var ResourcesTable
     */
    private $resourcesTable;

    /**
     * @var SecretsTable
     */
    private $secretsTable;

    /**
     * @var ResourcesUpdateService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->resourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $this->secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $this->service = new ResourcesUpdateService();
    }

    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->resourcesTable);
        unset($this->secretsTable);
        unset($this->service);
    }

    public function testUpdateResourcesSuccess_UpdateResourceMeta()
    {
        $user = UserFactory::make()->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->with('ResourceTypes', ResourceTypeFactory::make()->default())
            ->withPermissionsFor([$user])
            ->persist();

        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];
        $this->service->update($this->makeUac($user), $r1->id, new MetadataResourceDto($data));

        // Assert R1 meta have been updated
        $r1Updated = $this->resourcesTable->findById($r1->id)->first();
        $this->assertEquals('R1 name updated', $r1Updated->name);
        $this->assertEquals('R1 username updated', $r1Updated->username);
        $this->assertEquals('https://r1-updated.com', $r1Updated->uri);
        $this->assertEquals('R1 description updated', $r1Updated->description);
        $this->assertGreaterThan($r1->modified, $r1Updated->modified);

        // Assert R2 have not been updated
        $r2Retrieved = $this->resourcesTable->findById($r2->id)->first();
        $this->assertEquals($r2->name, $r2Retrieved->name);
    }

    public function testUpdateResourcesSuccess_UpdateResourceSecrets()
    {
        $users = [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->with('ResourceTypes', ResourceTypeFactory::make()->default())
            ->withSecretsFor($users)
            ->withPermissionsFor($users)
            ->persist();

        $r2SecretA = $r2->secrets[0];

        $r1EncryptedSecretA = $this->encryptMessageFor($userA->id, 'R1 secret updated');
        $r1EncryptedSecretB = $this->encryptMessageFor($userB->id, 'R1 secret updated');
        $r1EncryptedSecretC = $this->encryptMessageFor($userC->id, 'R1 secret updated');
        $data = [
            'secrets' => [
                ['user_id' => $userA->id, 'data' => $r1EncryptedSecretA],
                ['user_id' => $userB->id, 'data' => $r1EncryptedSecretB],
                ['user_id' => $userC->id, 'data' => $r1EncryptedSecretC],
            ],
        ];

        $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));

        // Assert R1 secrets have been updated
        $r1SecretA = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userA->id)->first();
        $this->assertEquals($r1EncryptedSecretA, $r1SecretA->data);
        $r1SecretB = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userB->id)->first();
        $this->assertEquals($r1EncryptedSecretB, $r1SecretB->data);
        $r1SecretC = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userC->id)->first();
        $this->assertEquals($r1EncryptedSecretC, $r1SecretC->data);
        // Assert R1 meta has not been updated except for the modified field.
        $r1Updated = $this->resourcesTable->findById($r1->id)->first();
        $this->assertEquals($r1->name, $r1Updated->name);
        $this->assertEquals($r1->username, $r1Updated->username);
        $this->assertEquals($r1->uri, $r1Updated->uri);
        $this->assertEquals($r1->description, $r1Updated->description);
        $this->assertGreaterThan($r1->modified, $r1Updated->modified);
        // Assert R2 secrets have not been updated
        $r2AfterUpdateSecretA = $this->secretsTable->findByResourceIdAndUserId($r2->id, $userA->id)->first();
        $this->assertEquals($r2SecretA->data, $r2AfterUpdateSecretA->data);
    }

    public function testUpdateResourcesError_UpdateResourceMeta_ValidationError()
    {
        [$r1, $userAId] = $this->insertFixture_UpdateResourceMeta_ValidationError();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data = [
            'name' => '',
        ];

        try {
            $this->service->update($uac, $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate resource data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'name._empty'));
        }
    }

    private function insertFixture_UpdateResourceMeta_ValidationError()
    {
        // Ada is OWNER of resource R1
        // ---
        // R1 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userAId => Permission::OWNER]);

        return [$r1, $userAId];
    }

    public function testUpdateResourcesError_UpdateResourceSecrets_ValidationError()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_UpdateResourceSecrets_ValidationError();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $r1EncryptedSecretA = $this->encryptMessageFor($userAId, 'R1 secret updated');
        $data = [
            'secrets' => [
                ['user_id' => $userAId, 'data' => $r1EncryptedSecretA],
            ],
        ];

        try {
            $this->service->update($uac, $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate resource data.', $e->getMessage());
            $this->assertNotEmpty(Hash::get($e->getErrors(), 'secrets.secrets_provided'));
        }
    }

    private function insertFixture_UpdateResourceSecrets_ValidationError()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userAId, $userBId, $userCId];
    }

    public function testUpdateResourcesError_InsufficientPermission()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_UpdateResourcesError_InsufficientPermission();
        $uac = new UserAccessControl(Role::USER, $userAId);
        $data = [
            'name' => 'R1 updated',
        ];

        try {
            $this->service->update($uac, $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ForbiddenException $e) {
            $this->assertEquals('You are not allowed to update this resource.', $e->getMessage());
        }
    }

    private function insertFixture_UpdateResourcesError_InsufficientPermission()
    {
        // Ada has READ on resource R1
        // Betty is OWNER of resource R1
        // ---
        // R1 (Ada:R, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::READ, $userBId => Permission::OWNER]);

        return [$r1, $userAId, $userBId];
    }

    public function testUpdateResourcesError_ResourceDoesNotExist()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $uac = new UserAccessControl(Role::USER, $userAId);
        $notFoundId = UuidFactory::uuid();

        try {
            $this->service->update($uac, $notFoundId, new MetadataResourceDto());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (NotFoundException $e) {
            $this->assertEquals('The resource does not exist.', $e->getMessage());
        }
    }
}
