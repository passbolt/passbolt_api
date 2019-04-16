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

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Table\UsersTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\PassboltText;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    /** @var UsersTable */
    public $Users;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Groups',
        'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions'
    ];

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'username' => true,
                'role_id' => true,
                'deleted' => true,
                'active' => true,
                'profile' => true
            ],
            'associated' => [
                'Profiles' => [
                    'accessibleFields' => [
                        'first_name' => true,
                        'last_name' => true
                    ]
                ]
            ]
        ];
    }

    public function setUp()
    {
        parent::setUp();

        /** @var UsersTable Users */
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function testUsersSaveCreateSuccess()
    {
        $testUser = $this->createTestUser();

        // Fetch the newly created user from DB
        $addedUser = $this->Users->get($testUser->id, [
            'contain' => ['Profiles']
        ]);

        $this->assertNotEmpty($addedUser);

        $this->assertEquals($testUser['username'], $addedUser->username);
        $this->assertEquals($testUser['role_id'], $addedUser->role_id);
        $this->assertEquals($testUser['deleted'], $addedUser->deleted);
        $this->assertEquals($testUser['active'], $addedUser->active);

        $this->assertEquals(PassboltText::ucfirst($testUser['profile']['first_name']), $addedUser->profile->first_name);
        $this->assertEquals(PassboltText::ucfirst($testUser['profile']['last_name']), $addedUser->profile->last_name);
    }

    public function testUsersSaveUpdateSuccess()
    {
        // Create a test user first
        $testUser = $this->createTestUser();

        $updateData = self::getUserUpdateData();

        $this->Users->patchEntity($testUser, $updateData);
        $updatedTestUser = $this->Users->save($testUser);

        $this->assertEmpty($testUser->getErrors(), 'Errors occurred while updating the entity: ' . json_encode($testUser->getErrors()));
        $this->assertNotFalse($updatedTestUser, 'The resource update operation failed.');

        // Fetch the updated User from DB.
        $fetchedUser = $this->Users->get($testUser->id, [
            'contain' => ['Profiles']
        ]);

        $this->assertNotEmpty($fetchedUser);
        $this->assertEquals($updateData['username'], $fetchedUser->username);
        $this->assertEquals($updateData['role_id'], $fetchedUser->role_id);
        $this->assertEquals($updateData['deleted'], $fetchedUser->deleted);
        $this->assertEquals($updateData['active'], $fetchedUser->active);

        $this->assertEquals(PassboltText::ucfirst($updateData['profile']['first_name']), $fetchedUser->profile->first_name);
        $this->assertEquals(PassboltText::ucfirst($updateData['profile']['last_name']), $fetchedUser->profile->last_name);
    }

    public function testUsersSaveValidationEmailError()
    {
        Configure::write('passbolt.email.validate.mx', true);
        $user = self::getDummyUser();
        $testCases = [
            'email' => self::getEmailTestCases(true)
        ];
        $this->assertFieldFormatValidation($this->Users, 'username', $user, self::getEntityDefaultOptions(), $testCases);
    }

    public function testUsersSaveValidationEmailNoMxError()
    {
        Configure::write('passbolt.email.validate.mx', false);
        $user = self::getDummyUser();
        $testCases = [
            'email' => self::getEmailTestCases(false)
        ];
        $this->assertFieldFormatValidation($this->Users, 'username', $user, self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationId()
    {
        $testCases = [
            'allowEmptyString' => self::getAllowEmptyTestCases(),
            'uuid' => self::getUuidTestCases(true),
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'id',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    public function testValidationUsername()
    {
        $checkMx = Configure::read('passbolt.email.validate.mx');

        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'email' => self::getEmailTestCases($checkMx),
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'username',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );

        Configure::write('passbolt.email.validate.mx', !$checkMx);

        $this->_reloadValidationRules($this->Users);

        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'email' => self::getEmailTestCases(!$checkMx),
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'username',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    public function testValidationActive()
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'active',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    public function testValidationRole()
    {
        $testCases = [
            'uuid' => self::getUuidTestCases(),
            'requirePresence' => self::getRequirePresenceTestCases()
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'role_id',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    public function testValidationDeleted()
    {
        $testCases = [
            'boolean' => self::getBooleanTestCases(),
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'deleted',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    public function testValidationProfile()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases()
        ];

        $this->assertFieldFormatValidation(
            $this->Users,
            'profile',
            self::getDummyUser(),
            self::getEntityDefaultOptions(),
            $testCases
        );
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testRuleUsernameIsUnique()
    {
        $data = self::getDummyUser(['username' => 'ada@passbolt.com']);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Users->newEntity($data, $options);
        $save = $this->Users->save($entity);
        $errors = $entity->getErrors();

        $this->assertFalse($save);
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['username']['uniqueUsername']);
    }

    public function testRuleUsernameIsUnique_DeletedUser()
    {
        $data = self::getDummyUser(['username' => 'sofia@passbolt.com']);
        $options = self::getEntityDefaultOptions();
        $entity = $this->Users->newEntity($data, $options);
        $save = $this->Users->save($entity);
        $errors = $entity->getErrors();

        $this->assertTrue(($save !== false));
        $this->assertEmpty($errors);
    }

    public function testRuleRoleIdExists()
    {
        $data = self::getDummyUser([
            'role_id' => self::getNonExistingRoleId()
        ]);

        $options = self::getEntityDefaultOptions();

        $entity = $this->Users->newEntity($data, $options);

        $save = $this->Users->save($entity);

        $this->assertFalse($save);

        $errors = $entity->getErrors();

        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['role_id']['validRole']);
    }
}
