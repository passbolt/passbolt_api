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
use App\Test\Fixture\Base\AvatarsFixture;
use App\Test\Fixture\Base\FavoritesFixture;
use App\Test\Fixture\Base\GpgkeysFixture;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\GroupsUsersFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ProfilesFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\RolesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\GroupsModelTrait;
use App\Test\Lib\Model\SecretsModelTrait;
use App\Utility\UuidFactory;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    use GroupsModelTrait;
    use SecretsModelTrait;

    public $fixtures = [
        AvatarsFixture::class,
        FavoritesFixture::class,
        GpgkeysFixture::class,
        GroupsFixture::class,
        GroupsUsersFixture::class,
        PermissionsFixture::class,
        ProfilesFixture::class,
        ResourcesFixture::class,
        RolesFixture::class,
        SecretsFixture::class,
        UsersFixture::class,
    ];

    public function testUpdateResourcesSuccess_UpdateResourceMeta()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_UpdateResourceMeta();
        $this->authenticateAs('betty');

        $data = [
            'name' => 'R1 name updated',
            'username' => 'R1 username updated',
            'uri' => 'https://r1-updated.com',
            'description' => 'R1 description updated',
        ];
        $this->putJson("/resources/$r1->id.json?api-version=2", $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals($userAId, $resource->created_by);
        $this->assertEquals($userBId, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($userAId, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($userBId, $resource->modifier->id);

        // Check the secrets attribute
        // Only the logged-in user secrets should be returned.
        $this->assertObjectHasAttribute('secrets', $resource);
        $this->assertCount(1, $resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
    }

    private function insertFixture_UpdateResourceMeta()
    {
        // Ada is OWNER of resource R1
        // Betty is OWNER of resource R1
        // ---
        // R1 (Ada:O, Betty:O)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1', 'username' => 'R1 username', 'uri' => 'https://r1.com', 'description' => 'R1 description'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$r1, $userAId, $userBId];
    }

    public function testUpdateResourcesSuccess_UpdateResourceSecrets()
    {
        [$r1, $g1, $userAId, $userBId, $userCId] = $this->insertFixture_UpdateResourceSecrets();
        $this->authenticateAs('betty');

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
        $this->putJson("/resources/$r1->id.json?api-version=2", $data);
        $this->assertSuccess();

        // Check the server response.
        $resourceUpdated = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resourceUpdated);
        $this->assertEquals($r1->name, $resourceUpdated->name);
        $this->assertEquals($r1->username, $resourceUpdated->username);
        $this->assertEquals($r1->uri, $resourceUpdated->uri);
        $this->assertEquals($r1->description, $resourceUpdated->description);
        $this->assertEquals($userAId, $resourceUpdated->created_by);
        $this->assertEquals($userBId, $resourceUpdated->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resourceUpdated->creator);
        $this->assertUserAttributes($resourceUpdated->creator);
        $this->assertEquals($userAId, $resourceUpdated->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resourceUpdated->modifier);
        $this->assertUserAttributes($resourceUpdated->modifier);
        $this->assertEquals($userBId, $resourceUpdated->modifier->id);

        // Check the secrets attribute
        // Only the logged-in user secrets should be returned.
        $this->assertObjectHasAttribute('secrets', $resourceUpdated);
        $this->assertCount(1, $resourceUpdated->secrets);
        $this->assertSecretAttributes($resourceUpdated->secrets[0]);
        $this->assertEquals($r1EncryptedSecretB, $resourceUpdated->secrets[0]->data);
    }

    private function insertFixture_UpdateResourceSecrets()
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

    public function testUpdateResourcesError_NotValidId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/resources/$resourceId.json?api-version=v2");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testUpdateResourcesError_ValidationErrors()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_UpdateResourceMeta();
        $this->authenticateAs('ada');

        $data = [
            'name' => '',
        ];
        $this->putJson("/resources/$r1->id.json?api-version=2", $data);
        $this->assertError(400, 'Could not validate resource data.');
    }

    public function testUpdateResourcesError_CsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->put("/resources/$resourceId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testResourcesUpdateError_InsufficientPermission()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $data = [
            'name' => ['Updated name'],
        ];
        $this->authenticateAs('betty');
        $this->putJson("/resources/$r1->id.json", $data);
        $this->assertError(403, 'You are not allowed to update this resource.');
    }

    private function insertFixture_InsufficientPermission()
    {
        // Ada is OWNER of resource R1
        // Betty has READ on resource R1
        // ---
        // R1 (Ada:O, Betty:R)
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $r1 = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::READ]);

        return [$r1, $userAId, $userBId];
    }

    public function testUpdateResourcesError_NotAuthenticated()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $this->putJson("/resources/$r1->id.json", []);
        $this->assertAuthenticationError();
    }

    public function testUpdateResourcesError_ResourceDoesNotExist()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->putJson("/resources/$resourceId.json?api-version=v2");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateResourcesError_NoAccessToResource()
    {
        [$r1, $userAId, $userBId] = $this->insertFixture_InsufficientPermission();
        $this->authenticateAs('dame');
        $this->putJson("/resources/$r1->id.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateResourcesError_ResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $this->putJson("/resources/$resourceId.json", []);
        $this->assertError(404, 'The resource does not exist.');
    }
}
