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

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Profiles', 'app.Base/Roles', 'app.Base/Groups', 'app.Base/GroupsUsers',
        'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Favorites', 'app.Base/Permissions', 'app.Base/EmailQueue', 'app.Base/Avatars'];

    public function setUp()
    {
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->gpg = OpenPGPBackendFactory::get();
        parent::setUp();
    }

    protected function getValidSecret()
    {
        return '-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----';
    }

    protected function _getDummyPostdata($resource = null, $data = [])
    {
        // Build the default data
        $defaultData = [
            'name' => 'Resource name updated by test',
            'username' => 'username_updated@by.test',
            'uri' => 'https://uri.updated.by.test',
            'description' => 'Resource description updated'
        ];

        // If secrets provided update them all.
        if (isset($resource->secrets)) {
            foreach ($resource->secrets as $secret) {
                $defaultData['secrets'][] = [
                    'id' => $secret->id,
                    'user_id' => $secret->user_id,
                    'data' => $this->getValidSecret()
                ];
            }
        }

        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testResourcesUpdateWithoutSecret()
    {
        $ownerId = UuidFactory::uuid('user.id.ada');
        $modifierId = UuidFactory::uuid('user.id.betty');
        $this->authenticateAs('betty');

        // Cases.
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        $success = [
            'chinese' => $this->_getDummyPostData($resource, [
                'name' => '新的專用資源名稱',
                'username' => 'username@domain.com',
                'uri' => 'https://www.域.com',
                'description' => '新的資源描述'
            ]),
            'slavic' => $this->_getDummyPostData($resource, [
                'name' => 'Новое имя частного ресурса',
                'username' => 'username@domain.com',
                'uri' => 'https://www.домен.com',
                'description' => 'Новое описание частного ресурса'
            ]),
            'french' => $this->_getDummyPostData($resource, [
                'name' => 'Nouveau nom de resource privée',
                'username' => 'username@domain.com',
                'uri' => 'https://www.mon-domain.com',
                'description' => 'Nouvelle description de resource privée'
            ]),
            'emoticon' => $this->_getDummyPostData($resource, [
                'name' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}",
                'username' => 'username@domain.com',
                'uri' => 'https://www.domain.com',
                'description' => "\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}\u{1F61C}"
            ]),
        ];

        foreach ($success as $case => $data) {
            $this->putJson("/resources/$resourceId.json?api-version=2", $data);
            $this->assertSuccess();

            // Check the server response.
            $resource = $this->_responseJsonBody;

            // Check the resource attributes.
            $this->assertResourceAttributes($resource);
            $this->assertEquals($data['name'], $resource->name);
            $this->assertEquals($data['username'], $resource->username);
            $this->assertEquals($data['uri'], $resource->uri);
            $this->assertEquals($data['description'], $resource->description);
            $this->assertEquals($ownerId, $resource->created_by);
            $this->assertEquals($modifierId, $resource->modified_by);

            // Check the creator attribute
            $this->assertNotNull($resource->creator);
            $this->assertUserAttributes($resource->creator);
            $this->assertEquals($ownerId, $resource->creator->id);

            // Check the modifier attribute
            $this->assertNotNull($resource->modifier);
            $this->assertUserAttributes($resource->modifier);
            $this->assertEquals($modifierId, $resource->modifier->id);

            // Check the secrets attribute
            // Only the logged-in user should be returned.
            $this->assertObjectHasAttribute('secrets', $resource);
            $this->assertCount(1, $resource->secrets);
            $this->assertSecretAttributes($resource->secrets[0]);
        }
    }

    public function testResourcesUpdateWithSecret_SharedWithUsers()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('betty');

        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getDummyPostData($resource);
        $this->assertNotEmpty($data['secrets']);
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // Check the secrets are updated in database
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $this->assertEquals(count($data['secrets']), count($resource->secrets));
        foreach ($resource->secrets as $secret) {
            $dataSecret = Hash::extract($data['secrets'], "{n}[user_id={$secret->user_id}]");
            $this->assertCount(1, $dataSecret, "No secret found for the user {$secret->user_id}");
            $this->assertEquals($resourceId, $secret->resource_id);
            $this->assertEquals($dataSecret[0]['data'], $secret->data);
        }
    }

    public function testResourcesUpdateWithSecret_SharedWithGroups()
    {
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->authenticateAs('grace');

        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getDummyPostData($resource);
        $this->assertNotEmpty($data['secrets']);
        $this->putJson("/resources/$resourceId.json?api-version=2", $data);
        $this->assertSuccess();

        // Check the secrets are updated in database
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $this->assertEquals(count($data['secrets']), count($resource->secrets));
        foreach ($resource->secrets as $secret) {
            $dataSecret = Hash::extract($data['secrets'], "{n}[user_id={$secret->user_id}]");
            $this->assertCount(1, $dataSecret, "No secret found for the user {$secret->user_id}");
            $this->assertEquals($resourceId, $secret->resource_id);
            $this->assertEquals($dataSecret[0]['data'], $secret->data);
        }
    }

    public function testResourcesUpdateApiV1()
    {
        $ownerId = UuidFactory::uuid('user.id.ada');
        $modifierId = UuidFactory::uuid('user.id.betty');
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $this->authenticateAs('betty');

        // Build the data to update.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $dataV2 = $this->_getDummyPostData($resource);
        $data['Resource'] = $dataV2;
        $data['Secret'] = $dataV2['secrets'];
        unset($data['Resource']['secrets']);
        $this->putJson("/resources/$resourceId.json", $data);
        $this->assertSuccess();

        // Check the server response.
        $resource = $this->_responseJsonBody;

        // Check the resource attributes.
        $this->assertResourceAttributes($resource->Resource);
        $this->assertEquals($data['Resource']['name'], $resource->Resource->name);
        $this->assertEquals($data['Resource']['username'], $resource->Resource->username);
        $this->assertEquals($data['Resource']['uri'], $resource->Resource->uri);
        $this->assertEquals($data['Resource']['description'], $resource->Resource->description);
        $this->assertEquals($ownerId, $resource->Resource->created_by);
        $this->assertEquals($modifierId, $resource->Resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->Creator);
        $this->assertUserAttributes($resource->Creator);
        $this->assertEquals($ownerId, $resource->Creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->Modifier);
        $this->assertUserAttributes($resource->Modifier);
        $this->assertEquals($modifierId, $resource->Modifier->id);

        // Check the secrets attribute
        // Only the logged-in user should be returned.
        $this->assertObjectHasAttribute('Secret', $resource);
        $this->assertCount(1, $resource->Secret);
        $this->assertSecretAttributes($resource->Secret[0]);

        // Check the secrets are updated in database
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $this->assertEquals(count($data['Secret']), count($resource->secrets));
        foreach ($resource->secrets as $secret) {
            $dataSecret = Hash::extract($data['Secret'], "{n}[user_id={$secret->user_id}]");
            $this->assertCount(1, $dataSecret, "No secret found for the user {$secret->user_id}");
            $this->assertEquals($resourceId, $secret->resource_id);
            $this->assertEquals($dataSecret[0]['data'], $secret->data);
        }
    }

    public function testErrorCsrfToken()
    {
        $this->disableCsrfToken();
        $this->authenticateAs('grace');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $this->put("/resources/$resourceId.json?api-version=2");
        $this->assertResponseCode(403);
    }

    public function testResourcesUpdateValidationErrors()
    {
        $this->markTestIncomplete();
    }

    public function testResourcesUpdateCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testResourcesUpdateErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testResourcesUpdateErrorDoesNotExistResource()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesUpdateErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $resource = $this->Resources->get($resourceId);
        $data = $this->_getDummyPostData($resource);
        $this->putJson("/resources/$resourceId.json", $data);
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testResourcesUpdateErrorAccessDenied()
    {
        $testCases = [
            'Cannot update a resource if no permission' => [
                'userAlias' => 'ada', 'resourceId' => UuidFactory::uuid('resource.id.april')],
            'Cannot update a resource with only read access' => [
                'userAlias' => 'ada', 'resourceId' => UuidFactory::uuid('resource.id.bower')],
        ];

        foreach ($testCases as $testCase) {
            $this->authenticateAs($testCase['userAlias']);
            $resourceId = $testCase['resourceId'];
            $this->putJson("/resources/$resourceId.json");
            $this->assertError(404, 'The resource does not exist.');
        }
    }

    public function testResourcesUpdateErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        $data = $this->_getDummyPostData($resource);
        $this->putJson("/resources/$resourceId.json", $data);
        $this->assertAuthenticationError();
    }
}
