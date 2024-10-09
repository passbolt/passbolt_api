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
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public function testUpdateResourcesController_Success_UpdateResourceMeta(): void
    {
        // enable event tracking
        EventManager::instance()->setEventList(new EventList());
        RoleFactory::make()->guest()->persist();
        [$r1, $userA, $userB] = $this->insertFixture_UpdateResourceMeta();
        $this->authenticateAs($userB);

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
        // assert event
        $this->assertEventFiredWith(
            ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME,
            'isV5',
            false
        );
    }

    private function insertFixture_UpdateResourceMeta(): array
    {
        // UserA aka Ada is OWNER of resource R1
        // UserB aka Betty is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();

        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userA->id => Permission::OWNER, $userB->id => Permission::OWNER]);

        return [$r1, $userA, $userB];
    }

    public function testUpdateResourcesController_Success_UpdateResourceSecrets(): void
    {
        RoleFactory::make()->guest()->persist();
        [$r1, $g1, $userA, $userB, $userC] = $this->insertFixture_UpdateResourceSecrets();
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
    }

    private function insertFixture_UpdateResourceSecrets(): array
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // G1 is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O, G1:O)
        [$userA, $userB, $userC] = UserFactory::make(3)->withValidGpgKey()->persist();
        $g1 = $this->addGroup(['name' => 'G1', 'groups_users' => [
            ['user_id' => $userA->id, 'is_admin' => true],
            ['user_id' => $userC->id, 'is_admin' => true],
        ]]);
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userA->id => Permission::OWNER, $userB->id => Permission::OWNER], [$g1->id => Permission::OWNER]);

        return [$r1, $g1, $userA, $userB, $userC];
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
        [$r1, $userA, $userB] = $this->insertFixture_UpdateResourceMeta();
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
        [$r1, $userA, $userB] = $this->insertFixture_InsufficientPermission();
        $data = [
            'name' => ['Updated name'],
        ];
        $this->logInAs($userB);
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertError(403, 'You are not allowed to update this resource.');
    }

    private function insertFixture_InsufficientPermission(): array
    {
        // Ada is OWNER of resource R1
        // Betty has READ on resource R1
        // ---
        // R1 (Ada:O, Betty:R)
        [$userA, $userB] = UserFactory::make(2)->user()->persist();

        $r1 = $this->addResourceFor(['name' => 'R1'], [$userA->id => Permission::OWNER, $userB->id => Permission::READ]);

        return [$r1, $userA, $userB];
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
        [$r1, $userA, $userB] = $this->insertFixture_InsufficientPermission();
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
