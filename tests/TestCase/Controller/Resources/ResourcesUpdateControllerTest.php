<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Test\TestCase\Controller\Resources;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\Gpg;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class ResourcesUpdateControllerTest extends AppIntegrationTestCase
{
    public $fixtures = ['app.users', 'app.gpgkeys', 'app.profiles', 'app.roles', 'app.groups', 'app.groups_users', 'app.resources', 'app.secrets', 'app.favorites', 'app.permissions'];

    public function setUp()
    {
        $this->Resources = TableRegistry::get('Resources');
        $this->gpg = new Gpg();
        parent::setUp();
    }

    protected function _getDummyPostdata($resource, $data = [])
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
                // Encrypt the secret for the user.
                $gpgKey = $this->Resources->association('Creator')->association('Gpgkeys')
                    ->find()->where(['user_id' => $secret->user_id])->first();
                $this->gpg->setEncryptKey($gpgKey->armored_key);
                $encrypted = $this->gpg->encrypt('Updated resource secret');
                $defaultData['secrets'][] = [
                    'id' => $secret->id,
                    'user_id' => $secret->user_id,
                    'data' => $encrypted
                ];
            }
        }

        $data = array_merge($defaultData, $data);

        return $data;
    }

    public function testUpdateResourceWithoutSecret()
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

    public function testUpdateResourceWithSecret_SharedWithUsers()
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

    public function testUpdateResourceWithSecret_SharedWithGroups()
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

    public function testUpdateResourceApiV1()
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

    public function testValidationErrors()
    {
        $this->markTestIncomplete();
    }

    public function testUpdateCannotModifyNotAccessibleFields()
    {
        $this->markTestIncomplete();
    }

    public function testUpdateErrorNotValidId()
    {
        $this->authenticateAs('ada');
        $resourceId = 'invalid-id';
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(400, 'The resource id is not valid.');
    }

    public function testAddErrorDoesNotExistResource()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid();
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateErrorResourceIsSoftDeleted()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $resource = $this->Resources->get($resourceId);
        $data = $this->_getDummyPostData($resource);
        $this->putJson("/resources/$resourceId.json", $data);
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateErrorAccessDenied_ReadAccess()
    {
        $this->authenticateAs('ada');
        $resourceId = UuidFactory::uuid('resource.id.bower');
        $this->putJson("/resources/$resourceId.json");
        $this->assertError(404, 'The resource does not exist.');
    }

    public function testUpdateErrorNotAuthenticated()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId);
        $data = $this->_getDummyPostData($resource);
        $this->putJson("/resources/$resourceId.json", $data);
        $this->assertAuthenticationError();
    }
}
