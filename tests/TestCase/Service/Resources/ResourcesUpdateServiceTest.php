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

/**
 * \App\Service\Resources\ResourcesUpdateService Test Case
 *
 * @covers \App\Service\Resources\ResourcesUpdateService
 */
class ResourcesUpdateServiceTest extends AppTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public $fixtures = [
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

    public function testUpdateResourcesSuccess_UpdateResourceMeta()
    {
        [$r1, $r2, $userAId] = $this->insertFixture_UpdateResourceMeta();
        // Wait 1 second in order to test that the modified field is not on the same second.
        sleep(1);
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];
        $this->service->update($uac, $r1->id, $data);

        // Assert R1 meta have been updated
        $r1Updated = $this->resourcesTable->findById($r1->id)->first();
        $this->assertEquals('R1 name updated', $r1Updated->name);
        $this->assertEquals('R1 username updated', $r1Updated->username);
        $this->assertEquals('https://r1-updated.com', $r1Updated->uri);
        $this->assertEquals('R1 description updated', $r1Updated->description);
        $this->assertGreaterThan($r1->modified, $r1Updated->modified);

        // Assert R2 have not been updated
        $r2 = $this->resourcesTable->findById($r2->id)->first();
        $this->assertEquals('R2', $r2->name);
    }

    private function insertFixture_UpdateResourceMeta()
    {
        // Ada is OWNER of resource R1
        // Ada is OWNER of resource R2
        // ---
        // R1 (Ada:O)
        // R2 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userAId => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2', 'username' => 'R2 username', 'uri' => 'https://R2.com', 'description' => 'R2 description'], [$userAId => Permission::OWNER]);

        return [$r1, $r2, $userAId];
    }

    public function testUpdateResourcesSuccess_UpdateResourceSecrets()
    {
        [$r1, $r2, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_UpdateResourceSecrets();
        // Wait 1 second in order to test that the modified field is not on the same second.
        sleep(1);

        $uac = new UserAccessControl(Role::USER, $userAId);
        $r2SecretA = $this->secretsTable->findByResourceIdAndUserId($r2->id, $userAId)->first();

        $r1EncryptedSecretA = $this->encryptMessageFor($userAId, 'R1 secret updated');
        $r1EncryptedSecretB = $this->encryptMessageFor($userBId, 'R1 secret updated');
        $r1EncryptedSecretC = $this->encryptMessageFor($userCId, 'R1 secret updated');
        $data = [
            'secrets' => [
                ['user_id' => $userAId, 'data' => $r1EncryptedSecretA],
                ['user_id' => $userBId, 'data' => $r1EncryptedSecretB],
                ['user_id' => $userCId, 'data' => $r1EncryptedSecretC],
            ],
        ];
        $this->service->update($uac, $r1->id, $data);

        // Assert R1 secrets have been updated
        $r1SecretA = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userAId)->first();
        $this->assertEquals($r1EncryptedSecretA, $r1SecretA->data);
        $r1SecretB = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userBId)->first();
        $this->assertEquals($r1EncryptedSecretB, $r1SecretB->data);
        $r1SecretC = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userCId)->first();
        $this->assertEquals($r1EncryptedSecretC, $r1SecretC->data);

        // Assert R1 meta has not been updated except for the modified field.
        $r1Updated = $this->resourcesTable->findById($r1->id)->first();
        $this->assertEquals('R1', $r1Updated->name);
        $this->assertEquals('R1 username', $r1Updated->username);
        $this->assertEquals('https://r1.com', $r1Updated->uri);
        $this->assertEquals('R1 description', $r1Updated->description);
        $this->assertEquals('R1', $r1Updated->name);
        $this->assertGreaterThan($r1->modified, $r1Updated->modified);

        // Assert R2 secrets have not been updated
        $r2AfterUpdateSecretA = $this->secretsTable->findByResourceIdAndUserId($r2->id, $userAId)->first();
        $this->assertEquals($r2SecretA->data, $r2AfterUpdateSecretA->data);
    }

    private function insertFixture_UpdateResourceSecrets()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // Ada is OWNER of resource R2
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        // R2 (Ada:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $userCId = UuidFactory::uuid('user.id.carol');
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userAId, 'is_admin' => true],
            ['user_id' => $userCId, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER], [$g1->id => Permission::OWNER]);
        $r2 = $this->addResourceFor(['name' => 'R2', 'username' => 'R2 username', 'uri' => 'https://R2.com', 'description' => 'R2 description'], [$userAId => Permission::OWNER]);

        return [$r1, $r2, $g1, $userAId, $userBId, $userCId];
    }

    public function testUpdateResourcesError_UpdateResourceMeta_ValidationError()
    {
        [$r1, $userAId] = $this->insertFixture_UpdateResourceMeta_ValidationError();
        $uac = new UserAccessControl(Role::USER, $userAId);

        $data = [
            'name' => '',
        ];

        try {
            $this->service->update($uac, $r1->id, $data);
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
            $this->service->update($uac, $r1->id, $data);
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
            $this->service->update($uac, $r1->id, $data);
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
            $this->service->update($uac, $notFoundId);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (NotFoundException $e) {
            $this->assertEquals('The resource does not exist.', $e->getMessage());
        }
    }

    public function testUpdateResourcesError_NoAccessToResource()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_UpdateResourcesError_InsufficientPermission();
        $userCId = UuidFactory::uuid('user.id.carol');
        $uac = new UserAccessControl(Role::USER, $userCId);
        $data = [
            'name' => 'R1 updated',
        ];

        try {
            $this->service->update($uac, $r1->id, $data);
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (NotFoundException $e) {
            $this->assertEquals('The resource does not exist.', $e->getMessage());
        }
    }
}
