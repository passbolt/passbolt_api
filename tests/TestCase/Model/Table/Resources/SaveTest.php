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

use App\Model\Entity\Permission;
use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Resources;

    public $fixtures = ['app.Base/groups', 'app.Base/groups_users', 'app.Base/users', 'app.Base/permissions', 'app.Base/resources', 'app.Base/secrets'];

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
                'permissions' => true
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
            'maxLength' => self::getMaxLengthTestCases(64),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'name', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUsername()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(64),
            'maxLength' => self::getMaxLengthTestCases(64),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'username', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationUri()
    {
        $testCases = [
            'utf8' => self::getUtf8TestCases(1024),
            'maxLength' => self::getMaxLengthTestCases(1024),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'uri', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationDescription()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(10000),
            'maxLength' => self::getMaxLengthTestCases(10000),
            'allowEmpty' => self::getAllowEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'description', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
    }

    public function testValidationPermissions()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Resources, 'permissions', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
        $this->markTestIncomplete('test the count rule');
    }

    public function testValidationSecrets()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases()
        ];
        $this->assertFieldFormatValidation($this->Resources, 'secrets', self::getDummyResource(), self::getEntityDefaultOptions(), $testCases);
        $this->markTestIncomplete('test the count rule');
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
        $this->assertNotEmpty($resource->permissions);
        $this->assertCount(1, $resource->permissions);
        $permission = $resource->permissions[0];
        $this->assertPermissionAttributes($permission);
        $this->assertEquals($data['permissions'][0]['aco'], $permission->aco);
        $this->assertEquals($save->id, $permission->aco_foreign_key);
        $this->assertEquals($data['permissions'][0]['aro'], $permission->aro);
        $this->assertEquals($userId, $permission->aro_foreign_key);
        $this->assertEquals($data['permissions'][0]['type'], $permission->type);

        // Check the secret attribute
        $this->assertNotEmpty($resource->secrets);
        $this->assertSecretAttributes($resource->secrets[0]);
        $this->assertCount(1, $resource->secrets);
        $this->assertEquals($data['secrets'][0]['user_id'], $resource->secrets[0]->user_id);
        $this->assertEquals($data['secrets'][0]['data'], $resource->secrets[0]->data);
    }

    public function testErrorRulAtLeastOneOwner()
    {
        $data = self::getDummyResource();
        $data['permissions'][0]['type'] = Permission::UPDATE;
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['permissions']['owner_permission_provided']);
    }

    public function testErrorRuleOwnerSecretProvided()
    {
        $data = self::getDummyResource();
        $data['secrets'][0]['user_id'] = UuidFactory::uuid('user.id.betty');
        $options = self::getEntityDefaultOptions();
        $entity = $this->Resources->newEntity($data, $options);
        $save = $this->Resources->save($entity);
        $this->assertFalse($save, 'The resource save operation should fail.');
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['secrets']['owner_secret_provided']);
    }
}
