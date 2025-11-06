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

namespace App\Test\TestCase\Model\Table\Secrets;

use App\Model\Entity\Permission;
use App\Test\Factory\SecretFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\PermissionsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;
    use PermissionsModelTrait;

    public array $fixtures = [
        'app.Base/Resources', 'app.Base/Secrets', 'app.Base/Permissions',
        'app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers',
    ];

    /**
     * @var \App\Model\Table\SecretsTable
     */
    public $Secrets;

    public function setUp(): void
    {
        parent::setUp();

        $this->Secrets = TableRegistry::getTableLocator()->get('Secrets');
    }

    public function tearDown(): void
    {
        unset($this->Secrets);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                // Make it accessible for the test, in practice a secret is saved along with a resource.
                // See the validationSaveResource.
                'resource_id' => true,
                'user_id' => true,
                'data' => true,
                'secret_revision_id' => true,
            ],
        ];
    }

    /* FORMAT VALIDATION TESTS */

    public function testSecretsSaveValidationUserId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'user_id', self::getDummySecretData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testSecretsSaveValidationResourceId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'resource_id', self::getDummySecretData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testSecretsSaveValidationSecretRevisionId()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'secret_revision_id', self::getDummySecretData(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testSecretsSaveValidationData()
    {
        $testCases = [
            'isValidOpenPGPMessage' => self::getGpgMessageTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Secrets, 'data', self::getDummySecretData(), self::getEntityDefaultOptions(), $testCases);
    }

    /* LOGIC VALIDATION TESTS */

    public function testSecretsSaveSuccess()
    {
        $data = self::getDummySecretData();
        $options = self::getEntityDefaultOptions();
        $data['secret_revision_id'] = SecretRevisionFactory::make()->persist()->id;

        // Contextual data change: give access to the resource to the user
        $this->addPermission('Resource', $data['resource_id'], 'User', $data['user_id'], Permission::OWNER);

        $entity = $this->Secrets->newEntity($data, $options);
        $save = $this->Secrets->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The secret save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $secret = $this->Secrets->find()
            ->where(['Secrets.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertSecretAttributes($secret);
        $this->assertEquals($data['user_id'], $secret->user_id);
        $this->assertEquals($data['resource_id'], $secret->resource_id);
        $this->assertEquals($data['data'], $secret->data);
    }

    public function testErrorRuleSecretUnique()
    {
        $data = self::getDummySecretData();
        $data['user_id'] = UuidFactory::uuid('user.id.ada');
        $data['resource_id'] = UuidFactory::uuid('resource.id.apache');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['secret_unique']);
    }

    public function testErrorRuleUserExists()
    {
        $data = self::getDummySecretData();
        $data['user_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_exists']);
    }

    public function testErrorRuleUserIsNotSoftDeleted()
    {
        $data = self::getDummySecretData();
        $data['user_id'] = UuidFactory::uuid('user.id.sofia');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['user_id']['user_is_not_soft_deleted']);
    }

    public function testErrorRuleResourceExists()
    {
        $data = self::getDummySecretData();
        $data['resource_id'] = UuidFactory::uuid();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_id']['resource_exists']);
    }

    public function testErrorRuleResourceIsNotSoftDeleted()
    {
        $data = self::getDummySecretData();
        $data['resource_id'] = UuidFactory::uuid('resource.id.jquery');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_id']['resource_is_not_soft_deleted']);
    }

    public function testErrorRuleHasAccess()
    {
        $data = self::getDummySecretData();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Secrets->newEntity($data, $options);

        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['resource_id']['has_resource_access']);
    }

    public function testSecretsSaveError_On_Soft_Deleted_Secret_Revision()
    {
        $data = self::getDummySecretData();
        $options = self::getEntityDefaultOptions();
        $data['secret_revision_id'] = SecretRevisionFactory::make()->deleted()->persist()->id;

        // Contextual data change: give access to the resource to the user
        $this->addPermission('Resource', $data['resource_id'], 'User', $data['user_id'], Permission::OWNER);

        $entity = $this->Secrets->newEntity($data, $options);
        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertSame('The secret revision does not exist or is deleted.', $errors['secret_revision_id']['secret_revision_is_not_soft_deleted']);
    }

    public function testSecretsSaveError_On_Duplicate_Resource_User_Revision_Deleted()
    {
        $existingSecret = SecretFactory::make()
            ->with('Users')
            ->with('Resources')
            ->withSecretRevision()
            ->deleted()
            ->persist();

        $data = self::getDummySecretData();
        $options = self::getEntityDefaultOptions();
        $data['secret_revision_id'] = $existingSecret->secret_revision_id;
        $data['user_id'] = $existingSecret->user_id;
        $data['resource_id'] = $existingSecret->resource_id;

        // Contextual data change: give access to the resource to the user
        $this->addPermission('Resource', $data['resource_id'], 'User', $data['user_id'], Permission::OWNER);

        $entity = $this->Secrets->newEntity($data, $options);
        $save = $this->Secrets->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertSame('A secret already exists for the given user, resource and secret revision.', $errors['user_id']['secret_revision_unique']);
    }

    public function testSecretsSaveSuccess_On_Duplicate_Resource_User_Secret_Deleted()
    {
        $existingSecret = SecretFactory::make()
            ->with('Users')
            ->with('Resources')
            ->deleted()
            ->persist();

        $data = self::getDummySecretData();
        $options = self::getEntityDefaultOptions();
        $data['secret_revision_id'] = SecretRevisionFactory::make()->persist()->id;
        $data['user_id'] = $existingSecret->user_id;
        $data['resource_id'] = $existingSecret->resource_id;

        // Contextual data change: give access to the resource to the user
        $this->addPermission('Resource', $data['resource_id'], 'User', $data['user_id'], Permission::OWNER);

        $entity = $this->Secrets->newEntity($data, $options);
        $this->Secrets->save($entity);
        $this->assertEmpty($entity->getErrors());
    }
}
