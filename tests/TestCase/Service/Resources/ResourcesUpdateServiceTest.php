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
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Exception;
use Passbolt\Metadata\Model\Dto\MetadataResourceDto;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsGetService;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionsSettingsFactory;

/**
 * \App\Service\Resources\ResourcesUpdateService Test Case
 *
 * @covers \App\Service\Resources\ResourcesUpdateService
 */
class ResourcesUpdateServiceTest extends AppTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    /**
     * @var \App\Model\Table\ResourcesTable
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
        SecretRevisionsSettingsGetService::clear();
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
        SecretRevisionsSettingsFactory::make()->setMaxRevisions(2)->persist();
        $users = [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        RoleFactory::make()->guest()->persist();
        [$r1, $r2] = ResourceFactory::make(2)
            ->with('ResourceTypes', ResourceTypeFactory::make()->default())
            ->withSecretsFor($users)
            ->withPermissionsFor($users)
            ->withSecretRevisions()
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

        $this->loadPlugins([SecretRevisionsPlugin::class => []]);
        $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));

        // Assert that the secrets have been added to revision, and not hard deleted
        $this->assertSame(9, SecretFactory::count());
        // Assert R1 secrets have been updated
        $r1SecretA = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userA->id)->find('notDeleted')->first();
        $this->assertEquals($r1EncryptedSecretA, $r1SecretA->data);
        $r1SecretB = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userB->id)->find('notDeleted')->first();
        $this->assertEquals($r1EncryptedSecretB, $r1SecretB->data);
        $r1SecretC = $this->secretsTable->findByResourceIdAndUserId($r1->id, $userC->id)->find('notDeleted')->first();
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

        // Assert that a new revision is persisted
        $this->assertSame(3, SecretRevisionFactory::count());
    }

    public function testUpdateResourcesError_UpdateResourceMeta_ValidationError()
    {
        // Ada is OWNER of resource R1
        // ---
        // R1 (Ada:O)
        $userA = UserFactory::make()->withValidGpgKey()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userA])->withSecretsFor([$userA])->persist();

        $data = [
            'name' => '',
        ];

        try {
            $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate resource data.', $e->getMessage());
            $this->assertSame([
                'name' => [
                    '_empty' => 'The name should not be empty.',
                ],
            ], $e->getErrors());
        }
    }

    public function testUpdateResourcesError_UpdateResourceSecrets_ValidationError()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // Group is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        RoleFactory::make()->guest()->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$userA,$userC])->persist();
        $r1 = ResourceFactory::make()
            ->with('ResourceTypes', ResourceTypeFactory::make()->default())
            ->withPermissionsFor([$userA,$userB,$group])
            ->withSecretsFor([$userA,$userB,$group])
            ->persist();

        $r1EncryptedSecretA = $this->encryptMessageFor($userA->id, 'R1 secret updated');
        $data = [
            'secrets' => [
                ['user_id' => $userA->id, 'data' => $r1EncryptedSecretA],
            ],
        ];

        try {
            $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertEquals('Could not validate resource data.', $e->getMessage());
            $this->assertSame([
                'secrets' => [
                    'secrets_provided' => 'The secrets should contain the secrets of all the users having access to the resource.',
                ],
            ], $e->getErrors());
        }
    }

    public function testUpdateResourcesError_InsufficientPermission()
    {
        // Ada has READ on resource R1
        // Betty is OWNER of resource R1
        // ---
        // R1 (Ada:R, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->withValidGpgKey()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$userB])->withSecretsFor([$userB])->persist();
        PermissionFactory::make()->acoResource($r1)->aroUser($userA)->typeRead()->persist();

        $data = [
            'name' => 'R1 updated',
        ];

        try {
            $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ForbiddenException $e) {
            $this->assertEquals('You are not allowed to update this resource.', $e->getMessage());
        }
    }

    public function testUpdateResourcesError_ResourceDoesNotExist()
    {
        [$userA, $userNotFound] = UserFactory::make(2)->withValidGpgKey()->persist();

        try {
            $this->service->update($this->makeUac($userA), $userNotFound->id, new MetadataResourceDto());
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (NotFoundException $e) {
            $this->assertEquals('The resource does not exist.', $e->getMessage());
        }
    }

    public function testUpdateResources_Error_ResourceTypeDeleted(): void
    {
        $user = UserFactory::make()->persist();
        $r1 = ResourceFactory::make()
            ->with('ResourceTypes', ResourceTypeFactory::make()->passwordAndDescription()->deleted())
            ->withPermissionsFor([$user])
            ->persist();

        try {
            $this->service->update($this->makeUac($user), $r1->id, new MetadataResourceDto([
                'name' => 'R1 name updated',
                'username' => 'R1 username updated',
                'uri' => 'https://r1-updated.com',
                'description' => 'R1 description updated',
            ]));
        } catch (Exception $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertArrayHasKey('resource_type_id', $e->getErrors());
            $this->assertArrayHasKey('resource_type_is_not_soft_deleted', $e->getErrors()['resource_type_id']);
        }
    }

    public function testUpdateResourcesError_UpdateSecrets_ValidationExceptions_InvalidGpgMessage()
    {
        $userA = UserFactory::make()->persist();
        $r1 = ResourceFactory::make()
            ->with('ResourceTypes', ResourceTypeFactory::make()->default())
            ->withPermissionsFor([$userA])
            ->withSecretsFor([$userA])->persist();

        $data = [
            'secrets' => [['user_id' => $userA->id, 'data' => 'invalid-message']],
        ];

        try {
            $this->service->update($this->makeUac($userA), $r1->id, new MetadataResourceDto($data));
            $this->assertFalse(true, 'The test should catch an exception');
        } catch (ValidationException $e) {
            $this->assertSame(['secrets' => [[
                'data' => ['isValidOpenPGPMessage' => 'The message should be a valid ASCII-armored OpenPGP message.'],
            ]]], $e->getErrors());
        }
    }
}
