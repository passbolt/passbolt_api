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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\GpgkeysTable;
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\OpenPGP\OpenPGPBackend;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class UpdateTest extends AppTestCase
{
    use FormatValidationTrait;

    /** @var ResourcesTable */
    public $Resources;

    /** @var GpgkeysTable */
    public $Gpgkeys;

    /** @var OpenPGPBackend gpg */
    public $gpg;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Users', 'app.Base/Roles', 'app.Base/Gpgkeys',
        'app.Base/Profiles', 'app.Base/Avatars', 'app.Base/Permissions', 'app.Base/Resources', 'app.Base/Secrets'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->Gpgkeys = TableRegistry::getTableLocator()->get('Gpgkeys');
        $this->gpg = OpenPGPBackendFactory::get();
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    protected function _encryptSecret($userId, $text)
    {
        $gpgKey = $this->Gpgkeys->find()->where(['user_id' => $userId])->first();
        $this->gpg->importKeyIntoKeyring($gpgKey->armored_key);
        $this->gpg->setEncryptKeyFromFingerprint($gpgKey->fingerprint);

        return $this->gpg->encrypt($text);
    }

    protected function _getUpdatedDummydata($resource, $data = [])
    {
        // Build the data to update.
        $defaultData = [
            'name' => 'Resource name updated by test',
            'username' => 'username_updated@by.test',
            'uri' => 'https://uri.updated.by.test',
            'description' => 'Resource description updated',
            'modified_by' => $resource->modified_by
        ];

        // If secrets provided update them all.
        if (isset($resource->secrets)) {
            foreach ($resource->secrets as $secret) {
                // Encrypt the secret for the user.
                $encrypted = $this->_encryptSecret($secret->user_id, 'Updated resource secret');
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

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'username' => true,
                'uri' => true,
                'description' => true,
                'modified_by' => true,
                'secrets' => true
            ],
            'associated' => [
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'data' => true,
                        'user_id' => true
                    ]
                ]
            ]
        ];
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testResourceUpdate()
    {
        $ownerId = UuidFactory::uuid('user.id.ada');
        $modifierId = UuidFactory::uuid('user.id.betty');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);

        // Get the dummy resource updated data.
        $data = $this->_getUpdatedDummydata($resource, [
            'modified_by' => $modifierId
        ]);

        // Save the entity.
        $options = self::getEntityDefaultOptions();
        $this->Resources->patchEntity($resource, $data, $options);
        $save = $this->Resources->save($resource);
        $this->assertEmpty($resource->getErrors(), 'Errors occurred while updating the entity: ' . json_encode($resource->getErrors()));
        $this->assertNotFalse($save, 'The resource update operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Creator', 'Modifier', 'Secrets']]);

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals(false, $resource->deleted);
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

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertEquals(count($data['secrets']), count($resource->secrets));
        $this->assertSecretAttributes($resource->secrets[0]);
        foreach ($resource->secrets as $secret) {
            $dataSecret = Hash::extract($data['secrets'], "{n}[user_id={$secret->user_id}]");
            $this->assertCount(1, $dataSecret, "No secret found for the user {$secret->user_id}");
            $this->assertEquals($resourceId, $secret->resource_id);
            $this->assertEquals($dataSecret[0]['data'], $secret->data);
        }
    }

    public function testResourceUpdateWithoutSecrets()
    {
        $modifierId = UuidFactory::uuid('user.id.betty');
        $resourceId = UuidFactory::uuid('resource.id.cakephp');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);

        // Store the secrets before update, and tests their integrity after update.
        $originalSecrets = $resource->secrets;
        unset($resource->secrets);

        // Get the dummy resource updated data.
        $data = $this->_getUpdatedDummydata($resource);
        $data['modified_by'] = $modifierId;

        // Save the entity.
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->patchEntity($resource, $data, $options);
        $save = $this->Resources->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while updating the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The resource update operation failed.');

        // Check that the secrets are not updated.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $this->assertEquals(count($resource->secrets), count($originalSecrets));
        foreach ($resource->secrets as $secret) {
            $originalSecret = Hash::extract($originalSecrets, "{n}[id={$secret->id}]");
            $this->assertNotEmpty($originalSecret);
            $this->assertEquals($originalSecret[0]->user_id, $secret->user_id);
            $this->assertEquals($originalSecret[0]->resource_id, $secret->resource_id);
            $this->assertEquals($originalSecret[0]->data, $secret->data);
        }
    }

    public function testErrorRuleSecretsProvided_SecretMissing()
    {
        // Get the dummy resource updated data.
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
        $data = $this->_getUpdatedDummydata($resource);
        unset($data['secrets'][0]);

        // Save the entity.
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->patchEntity($resource, $data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save);
        $this->assertEntityError($entity, 'secrets.secrets_provided');
    }

    public function testErrorRuleSecretsProvided_InsertAnUnwantedSecret()
    {
        $resourceId = UuidFactory::uuid('resource.id.apache');
        $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);

        // Get the dummy resource updated data.
        $data = $this->_getUpdatedDummydata($resource);
        $userId = UuidFactory::uuid('user.id.edith');
        $data['secrets'][] = [
            'user_id' => $userId,
            'data' => $this->_encryptSecret($userId, 'Update secret data')
        ];

        // Save the entity.
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->patchEntity($resource, $data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save);
    }

    public function testErrorResourceNotSoftDeleted()
    {
        $resourceId = UuidFactory::uuid('resource.id.jquery');
        $resource = $this->Resources->get($resourceId);

        // Get the dummy resource updated data.
        $data = $this->_getUpdatedDummydata($resource);

        // Save the entity.
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->patchEntity($resource, $data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save);
        $this->assertEntityError($entity, 'id.resource_is_not_soft_deleted');
    }
}
