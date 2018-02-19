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

namespace App\Test\TestCase\Model\Table\Groups;

use App\Model\Table\GroupsTable;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class SaveTest extends AppTestCase
{
    use FormatValidationTrait;

    public $Groups;

    public $fixtures = [
        'app.Base/groups', 'app.Base/users', 'app.Base/groups_users', 'app.Base/permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Groups') ? [] : ['className' => GroupsTable::class];
        $this->Groups = TableRegistry::get('Groups', $config);
    }

    public function tearDown()
    {
        unset($this->Groups);

        parent::tearDown();
    }

    protected function getEntityDefaultOptions()
    {
        return [
            'validate' => 'default',
            'accessibleFields' => [
                'name' => true,
                'created_by' => true,
                'modified_by' => true,
                'groups_users' => true,
                'deleted' => true
            ],
            'associated' => [
                'GroupsUsers' => [
                    'validate' => 'saveGroup',
                    'accessibleFields' => [
                        'user_id' => true,
                        'is_admin' => true
                    ]
                ],
            ]
        ];
    }

    /* ************************************************************** */
    /* FORMAT VALIDATION TESTS */
    /* ************************************************************** */

    public function testValidationName()
    {
        $testCases = [
            'utf8Extended' => self::getUtf8ExtendedTestCases(255),
            'lengthBetween' => self::getLengthBetweenTestCases(1, 255),
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
        ];
        $this->assertFieldFormatValidation($this->Groups, 'name', self::getDummyGroup(), self::getEntityDefaultOptions(), $testCases);
    }

    /* ************************************************************** */
    /* LOGIC VALIDATION TESTS */
    /* ************************************************************** */

    public function testSuccess()
    {
        $data = self::getDummyGroup();
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The group save operation failed.');

        // Check that the groups and its sub-models are saved as expected.
        $group = $this->Groups->find()
            ->contain('GroupsUsers')
            ->contain('GroupsUsers.Users')
            ->where(['id' => $save->id])
            ->first();
        $this->assertEquals($data['name'], $group->name);
        $this->assertEquals(false, $group->deleted);
        $this->assertCount(2, $group->groups_users);
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $groupUserA = Hash::extract($group->groups_users, "{n}[user_id=$userAId][is_admin=true]");
        $this->assertNotEmpty($groupUserA);
        $groupUserB = Hash::extract($group->groups_users, "{n}[user_id=$userBId]");
        $this->assertNotEmpty($groupUserB);
    }

    public function testSuccessRuleGroupUnique()
    {
        $group = $this->Groups->findById(UuidFactory::uuid('group.id.freelancer'))->first();
        $this->Groups->softDelete($group);
        $data = self::getDummyGroup();
        $data['name'] = 'Freelancer';
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertEmpty($entity->getErrors(), 'Errors occurred while saving the entity: ' . json_encode($entity->getErrors()));
        $this->assertNotFalse($save, 'The group save operation failed.');
    }

    public function testErrorRuleGroupUnique()
    {
        $data = self::getDummyGroup();
        $data['name'] = 'Freelancer';
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['name']['group_unique']);
    }

    public function testErrorRuleAtLeastOneAdmin()
    {
        $data = self::getDummyGroup();
        $data['groups_users'] = [
            ['user_id' => UuidFactory::uuid('user.id.ada')],
            ['user_id' => UuidFactory::uuid('user.id.betty')]
        ];
        $options = self::getEntityDefaultOptions();
        $entity = $this->Groups->newEntity($data, $options);
        $save = $this->Groups->save($entity);
        $this->assertFalse($save);
        $errors = $entity->getErrors();
        $this->assertNotEmpty($errors);
        $this->assertNotNull($errors['groups_users']['at_least_one_admin']);
    }
}
