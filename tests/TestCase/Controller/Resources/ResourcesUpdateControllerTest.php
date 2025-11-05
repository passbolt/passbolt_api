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

namespace App\Test\TestCase\Controller\Resources;

use App\Model\Entity\Permission;
use App\Service\Resources\ResourcesUpdateService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\SecretRevisionsPlugin;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

/**
 * @covers \App\Controller\Resources\ResourcesUpdateController
 */
class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SecretRevisionsPlugin::class);
    }

    public function testUpdateResourcesController_Success_UpdateResourceMeta(): void
    {
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
        RoleFactory::make()->guest()->persist();
        // UserA aka Ada is CREATOR of resource R1
        // UserB aka Betty is OWNER of resource R1
        // ---
        // R1 (Ada:C, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r1 = ResourceFactory::make()
            ->withCreatorAndPermission($userA)
            ->withPermissionsFor([$userB])
            ->withSecretsFor([$userA, $userB])
            ->withSecretRevisions()
            ->persist();

        $this->logInAs($userB);

        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals($userA->id, $resource->created_by);
        $this->assertEquals($userB->id, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($userA->id, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($userB->id, $resource->modifier->id);

        // Check the secrets attribute
        // Only the logged-in user secrets should be returned.
        $this->assertObjectHasAttribute('secrets', $resource);
        $this->assertCount(1, $resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);

        // Secret revision checks
        // Assert no new secrets were persisted
        $this->assertSame(2, SecretFactory::count());
        // Assert that the previous secrets were not soft deleted
        foreach ($r1->secrets as $secret) {
            $secret = SecretFactory::get($secret->id);
            $this->assertNull($secret->deleted);
        }
        // Assert that no new revision is persisted
        $this->assertSame(1, SecretRevisionFactory::count());

        // assert event
        $this->assertEventFiredWith(
            ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME,
            'isV5',
            false
        );
    }

    public function testUpdateResourcesController_Success_UpdateResourceSecrets(): void
    {
        RoleFactory::make()->guest()->persist();
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        $g1 = GroupFactory::make()->withGroupsManagersFor([$userA, $userC])->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r1 = ResourceFactory::make()
            ->withCreatorAndPermission($userA)
            ->withPermissionsFor([$userB, $g1])
            ->withSecretsFor([$userA])
            ->withSecretRevisions()
            ->persist();

        $initialSecretRevision = $r1->secret_revisions[0];

        $this->logInAs($userB);
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
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertSuccess();

        // Check the server response.
        $resourceUpdated = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resourceUpdated);
        $this->assertEquals($r1->name, $resourceUpdated->name);
        $this->assertEquals($r1->username, $resourceUpdated->username);
        $this->assertEquals($r1->uri, $resourceUpdated->uri);
        $this->assertEquals($r1->description, $resourceUpdated->description);
        $this->assertEquals($userA->id, $resourceUpdated->created_by);
        $this->assertEquals($userB->id, $resourceUpdated->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resourceUpdated->creator);
        $this->assertUserAttributes($resourceUpdated->creator);
        $this->assertEquals($userA->id, $resourceUpdated->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resourceUpdated->modifier);
        $this->assertUserAttributes($resourceUpdated->modifier);
        $this->assertEquals($userB->id, $resourceUpdated->modifier->id);

        // Check the secrets attribute
        // Only the logged-in user secrets should be returned.
        $this->assertObjectHasAttribute('secrets', $resourceUpdated);
        $this->assertCount(1, $resourceUpdated->secrets);
        $this->assertSecretAttributes($resourceUpdated->secrets[0]);
        $this->assertEquals($r1EncryptedSecretB, $resourceUpdated->secrets[0]->data);
        $this->assertSame($userB->id, $resourceUpdated->secrets[0]->created_by);
        $this->assertSame($userB->id, $resourceUpdated->secrets[0]->modified_by);

        // Assert that one secret has been added to revision, and not hard deleted
        // while two new secrets for userB and userC were created
        $this->assertSame(4, SecretFactory::count());
        // Assert secret records in the database
        /** @var \App\Model\Entity\Secret[] $expectedSecrets */
        $expectedSecrets = SecretFactory::find('notDeleted')->where(['resource_id' => $r1->id])->all()->toArray();
        $this->assertCount(3, $expectedSecrets);
        foreach ($expectedSecrets as $expectedSecret) {
            if ($expectedSecret->user_id === $userA->id) {
                $this->assertSame($r1EncryptedSecretA, $expectedSecret->data);
                $this->assertSame($userB->id, $expectedSecret->created_by);
                $this->assertSame($userB->id, $expectedSecret->modified_by);
            } elseif ($expectedSecret->user_id === $userB->id) {
                $this->assertSame($r1EncryptedSecretB, $expectedSecret->data);
                $this->assertSame($userB->id, $expectedSecret->created_by);
                $this->assertSame($userB->id, $expectedSecret->modified_by);
            } elseif ($expectedSecret->user_id === $userC->id) {
                $this->assertSame($r1EncryptedSecretC, $expectedSecret->data);
                $this->assertSame($userB->id, $expectedSecret->created_by);
                $this->assertSame($userB->id, $expectedSecret->modified_by);
            }
        }

        // Secret revision checks
        // Assert that the previous secrets were soft deleted
        foreach ($r1->secrets as $secret) {
            $secret = SecretFactory::get($secret->id);
            $this->assertNotNull($secret->deleted);
        }
        // Assert that a new revision is persisted
        $this->assertSame(2, SecretRevisionFactory::count());
        // Assert that the previous secret revision is soft deleted
        $this->assertNotNull(SecretRevisionFactory::get($initialSecretRevision->id));
    }

    public function testUpdateResourcesController_Error_NotValidId(): void
    {
        $this->logInAsUser();
        $resourceId = 'invalid-id';
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource identifier should be a valid UUID.');
    }

    public function testUpdateResourcesController_Error_ValidationErrors(): void
    {
        // UserA aka Ada is OWNER of resource R1
        // UserB aka Betty is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA, $userB])
            ->withSecretsFor([$userA, $userB])
            ->persist();

        $this->logInAs($userA);

        $data = [
            'name' => '',
        ];
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertError(400, 'Could not validate resource data.');
    }

    public function testUpdateResourcesController_Error_CsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->put("/resources/$resourceId.json");
        $this->assertResponseCode(403);
    }

    public function testUpdateResourcesController_Error_InsufficientPermission(): void
    {
        // Ada is OWNER of resource R1
        // Betty has READ on resource R1
        // ---
        // R1 (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->withPermissionsFor([$userB], Permission::READ)
            ->persist();

        $data = [
            'name' => ['Updated name'],
        ];
        $this->logInAs($userB);
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertError(403, 'You are not allowed to update this resource.');
    }

    public function testUpdateResourcesController_Error_NotAuthenticated(): void
    {
        $resourceId = UuidFactory::uuid();
        $this->putJson("/resources/$resourceId.json", []);
        $this->assertAuthenticationError();
    }

    public function testUpdateResourcesController_Error_ResourceDoesNotExist(): void
    {
        $this->logInAsUser();
        $resourceId = UuidFactory::uuid();
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateResourcesController_Error_NoAccessToResource(): void
    {
        // Ada is OWNER of resource R1
        // ---
        // R1 (Ada:O)
        $userA = UserFactory::make()->user()->persist();
        ResourceTypeFactory::make()->default()->persist();
        $r1 = ResourceFactory::make()
            ->withPermissionsFor([$userA])
            ->persist();

        $this->logInAsUser();
        $this->putJson("/resources/$r1->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateResourcesController_Error_ResourceIsSoftDeleted(): void
    {
        $this->logInAsUser();
        $resourceId = ResourceFactory::make()->deleted()->persist()->id;
        $this->putJson("/resources/$resourceId.json", []);
        $this->assertError(404, 'The resource does not exist.');
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testUpdateResourcesController_Error_NotJson(): void
    {
        $this->logInAsUser();
        $id = UuidFactory::uuid();
        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];
        $this->put("/resources/$id", $data);
        $this->assertResponseCode(404);
    }
}
