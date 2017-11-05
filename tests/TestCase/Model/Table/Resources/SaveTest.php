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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = ['app.groups', 'app.groups_users', 'app.users', 'app.permissions', 'app.resources', 'app.secrets'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
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
                'created_by' => true,
                'modified_by' => true,
                'secrets' => true,
                'permission' => true
            ],
            'associated' => [
                'Permissions' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'aco' => true,
                        'aro' => true,
                        'aro_foreign_key' => true,
                        'type' => true
                    ]
                ],
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'user_id' => true,
                        'data' => true
                    ]
                ]
            ]
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(64),
            'lengthBetween' => self::getLengthBetweenTestCases(3, 64),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'name', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUsername()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(64),
            'lengthBetween' => self::getLengthBetweenTestCases(3, 64),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'username', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUri()
    {
        $testCases = [
            'utf8' => self::getUtf8TestCases(255),
            'lengthBetween' => self::getLengthBetweenTestCases(3, 255),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'uri', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationDescription()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(10000),
            'lengthBetween' => self::getLengthBetweenTestCases(3, 10000),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'description', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationPermission()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'permission', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationSecrets()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases()
        ];
        $this->assertFieldFormatValidation($this->Resources, 'secrets', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
        $this->markTestIncomplete('The rule checking the size of the array cannot be tested');
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSave()
    {
        $userId = UuidFactory::uuid('user.id.ada');
        $data = self::getDummyResource();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The resource save operation failed.');

        // Check that the resource and its sub-models are saved as expected.
        $resource = $this->Resources->find()
            ->contain('Creator')
            ->contain('Modifier')
            ->contain('Secrets')
            ->contain('Permissions')
            ->where(['Resources.id' => $save->id])
            ->first();

        // Check the resource attributes.
        $this->assertResourceAttributes($resource);
        $this->assertEquals($data['name'], $resource->name);
        $this->assertEquals($data['username'], $resource->username);
        $this->assertEquals($data['uri'], $resource->uri);
        $this->assertEquals($data['description'], $resource->description);
        $this->assertEquals(false, $resource->deleted);
        $this->assertEquals($userId, $resource->created_by);
        $this->assertEquals($userId, $resource->modified_by);

        // Check the creator attribute
        $this->assertNotNull($resource->creator);
        $this->assertUserAttributes($resource->creator);
        $this->assertEquals($userId, $resource->creator->id);

        // Check the modifier attribute
        $this->assertNotNull($resource->modifier);
        $this->assertUserAttributes($resource->modifier);
        $this->assertEquals($userId, $resource->modifier->id);

        // Check the permission attribute
        $this->assertNotNull($resource->permission);
        $this->assertPermissionAttributes($resource->permission);
        $this->assertEquals($data['permission']['aco'], $resource->permission->aco);
        $this->assertEquals($save->id, $resource->permission->aco_foreign_key);
        $this->assertEquals($data['permission']['aro'], $resource->permission->aro);
        $this->assertEquals($userId, $resource->permission->aro_foreign_key);
        $this->assertEquals($data['permission']['type'], $resource->permission->type);

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
        $this->assertCount(1, $resource->secrets);
        $this->assertEquals($data['secrets'][0]['user_id'], $resource->secrets[0]->user_id);
        $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);
    }

    public function testErrorRuleCreatorPermissionProvided()
    {
        $data = self::getDummyResource();
        $data['permission']['aro_foreign_key'] = UuidFactory::uuid('user.id.betty');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['permission']['creator_permission_provided']);
    }

    public function testErrorRuleCreatorSecretProvided()
    {
        $data = self::getDummyResource();
        $data['secrets'][0]['user_id'] = UuidFactory::uuid('user.id.betty');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['secrets']['creator_secret_provided']);
    }
}
