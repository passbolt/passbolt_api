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
 * @since         4.4.0
 */

namespace App\Test\TestCase\Model\Table\Users;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

class UsernameCaseSensitiveTest extends TestCase
{
    use TruncateDirtyTables;

    /**
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    public function setUp(): void
    {
        parent::setUp();
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function testUsernameCaseSensitive_Lower_Case_Before_Saving_Should_Lower_Case_Username()
    {
        $this->setUsernameLowerCaseConfigToTrue();
        $username = 'FOO@test.test';
        $role = RoleFactory::make()->user()->persist();
        $user = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', $username)

            ->getEntity();

        $user = $this->Users->buildEntity($user->toArray());
        $this->Users->save($user);
        $this->assertSame(1, UserFactory::count());
        $this->assertSame(strtolower($username), UserFactory::firstOrFail()->username);
    }

    public function testUsernameCaseSensitive_Case_Sensitive_Before_Saving_Should_Not_Lower_Case_Username()
    {
        $username = 'FOO@test.test';
        $role = RoleFactory::make()->user()->persist();
        $user = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', $username)

            ->getEntity();

        $user = $this->Users->buildEntity($user->toArray());
        $this->Users->save($user);
        $this->assertSame(1, UserFactory::count());
        $this->assertSame($username, UserFactory::firstOrFail()->username);
    }

    public function testUsernameCaseSensitive_Case_Insensitive_UpperCase_Username_And_Users_Not_Deleted_Should_Fail()
    {
        /** @var \App\Model\Entity\User $user1 */
        $user1 = UserFactory::make()->user()->persist();
        $role = $user1->role;
        $user2 = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', strtoupper($user1->username))->getEntity();

        $this->Users->save($user2);
        $this->assertUserSaveFail($user2);
    }

    public function testUsernameCaseSensitive_Case_Insensitive_Same_Username_And_User_Deleted_Should_Succeed()
    {
        $role = RoleFactory::make()->persist();
        /** @var \App\Model\Entity\User $user1 */
        $user1 = UserFactory::make(['role_id' => $role->id])->deleted()->persist();
        $user2 = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', $user1->username)
            ->getEntity();
        unset($user2->role);
        $result = $this->Users->save($user2);
        $this->assertUserSaveSuccess($result);
    }

    public function testUsernameCaseSensitive_Case_Sensitive_Different_Username_And_User_Not_Deleted_Should_Succeed()
    {
        $this->setUsernameCaseSensitiveConfigToTrue();
        /** @var \App\Model\Entity\User $user1 */
        $user1 = UserFactory::make()->user()->persist();
        $role = $user1->role;
        $user2 = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', strtoupper($user1->username))->getEntity();
        unset($user2->role);
        $result = $this->Users->save($user2);
        $this->assertUserSaveSuccess($result);
    }

    public function testUsernameCaseSensitive_Case_Sensitive_And_Users_With_Same_Username_Should_Fail()
    {
        $this->setUsernameCaseSensitiveConfigToTrue();
        /** @var \App\Model\Entity\User $user1 */
        $user1 = UserFactory::make()->user()->persist();
        $role = $user1->role;
        $user2 = UserFactory::make()
            ->setField('role_id', $role->id)
            ->setField('username', $user1->username)->getEntity();

        $this->Users->save($user2);
        $this->assertUserSaveFail($user2);
    }

    public function testUsernameCaseSensitive_listDuplicateUsernamesCaseInsensitive_No_Duplicates()
    {
        UserFactory::make(rand(3, 5))->persist();
        $duplicatesCaseInsensitive = $this->Users->listDuplicateUsernames();
        $this->assertSame(0, $duplicatesCaseInsensitive->all()->count());
    }

    public function testUsernameCaseSensitive_listDuplicateUsernamesCaseInsensitive()
    {
        // Create a bunch of users, keep the first one
        UserFactory::make(rand(3, 5))->persist();
        [$user1, $user2] = UserFactory::find()->orderByAsc('username')->toArray();

        // Create resp. 1 user with the same username but upper case
        $nDuplicatesUpperCase1 = 1;
        UserFactory::make($nDuplicatesUpperCase1)->setField('username', strtoupper($user1->username))->persist();
        $nDuplicatesUpperCase2 = 1;
        UserFactory::make($nDuplicatesUpperCase2)->setField('username', strtoupper($user2->username))->persist();
        // Create a bunch of exact duplicates
        $nDuplicatesCaseSensitive1 = rand(2, 5);
        UserFactory::make($nDuplicatesCaseSensitive1)->setField('username', $user1->username)->persist();
        $nDuplicatesCaseSensitive2 = rand(2, 5);
        UserFactory::make($nDuplicatesCaseSensitive2)->setField('username', $user2->username)->persist();

        // Add a bunch of deleted users with same username that should be ignored
        UserFactory::make(rand(1, 5))->setField('username', $user1->username)->deleted()->persist();
        UserFactory::make(rand(1, 5))->setField('username', $user2->username)->deleted()->persist();

        // Act And Assert Case Insensitive
        $duplicatesCaseInsensitive = $this->Users->listDuplicateUsernames()->toArray();
        $expectedCountCaseInsensitive = $nDuplicatesCaseSensitive1 + $nDuplicatesCaseSensitive2 + $nDuplicatesUpperCase1 + $nDuplicatesUpperCase2 + 2;
        $values = array_values($duplicatesCaseInsensitive);
        $this->assertSame($expectedCountCaseInsensitive, count($duplicatesCaseInsensitive));
        // Assert that the first user has $user1->username
        $this->assertSame(strtolower($values[0]), strtolower($user1->username));
        // Assert that the $nDuplicatesCaseSensitive1 + $nDuplicatesUpperCase1 + 1 has $user1->username
        $this->assertSame(strtolower($values[$nDuplicatesCaseSensitive1 + $nDuplicatesUpperCase1]), strtolower($user1->username));
        // Assert that the $nDuplicatesCaseSensitive1 + $nDuplicatesUpperCase1 + 2 has $user1->username
        $this->assertSame(strtolower($values[$nDuplicatesCaseSensitive1 + $nDuplicatesUpperCase1 + 1]), strtolower($user2->username));

        // Act And Assert Case Sensitive
        $this->setUsernameCaseSensitiveConfigToTrue();
        $duplicatesCaseSensitive = $this->Users->listDuplicateUsernames()->toArray();
        $expectedCountCaseSensitive = $nDuplicatesCaseSensitive1 + $nDuplicatesCaseSensitive2 + 2;
        $values = array_values($duplicatesCaseSensitive);
        $this->assertSame($expectedCountCaseSensitive, count($duplicatesCaseSensitive));
        // Assert that the first user has $user1->username
        $this->assertSame($values[0], $user1->username);
        // Assert that the $nDuplicatesCaseSensitive1 + 2 has $user2->username
        $this->assertSame($values[$nDuplicatesCaseSensitive1], $user1->username);
        $this->assertSame($values[$nDuplicatesCaseSensitive1 + 1], $user2->username);
    }

    public function testUsernameCaseSensitive_findByUsernameCaseAware()
    {
        $lowerUsername = 'john@passbolt.com';
        // Create users with username lower-cased, inactive in order to be able to perform a sort in the "Assert" part of the test
        $nLowerCased = rand(3, 5);
        UserFactory::make($nLowerCased)->setField('username', $lowerUsername)->inactive()->persist();

        // Create users with the same username but upper-cased, non deleted
        $upperUsername = strtoupper($lowerUsername);
        $nUpperCased = rand(3, 5);
        UserFactory::make($nUpperCased)->setField('username', $upperUsername)->persist();

        // Query with case sensitivity off, both queries retrieve all users
        /** @var User[] $queriedUsers */
        $queriedUsers = $this->Users->findByUsernameCaseAware($lowerUsername)
            ->orderByAsc('active')
            ->toArray();

        $this->assertSame($nLowerCased + $nUpperCased, count($queriedUsers));
        $this->assertSame($lowerUsername, $queriedUsers[0]->username);
        $this->assertSame($upperUsername, $queriedUsers[$nLowerCased]->username);

        // Query with case sensitivity on
        $this->setUsernameCaseSensitiveConfigToTrue();
        /** @var User[] $queriedUsers */
        $queriedUsers = $this->Users->findByUsernameCaseAware($lowerUsername)->toArray();

        $this->assertSame($nLowerCased, count($queriedUsers));
        $this->assertSame($lowerUsername, $queriedUsers[0]->username);

        /** @var User[] $queriedUsers */
        $queriedUsers = $this->Users->findByUsernameCaseAware($upperUsername)->toArray();
        $this->assertSame($nUpperCased, count($queriedUsers));
        $this->assertSame($upperUsername, $queriedUsers[0]->username);
    }

    private function assertUserSaveSuccess($result)
    {
        $this->assertNotFalse($result);
        $this->assertSame(2, UserFactory::count());
    }

    private function assertUserSaveFail(User $user)
    {
        $this->assertSame(['uniqueUsername' => 'The username is already in use.'], $user->getError('username'));
        $this->assertSame(1, UserFactory::count());
    }

    private function setUsernameCaseSensitiveConfigToTrue()
    {
        Configure::write(UsersTable::PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE, true);
    }

    private function setUsernameLowerCaseConfigToTrue()
    {
        Configure::write(UsersTable::PASSBOLT_SECURITY_USERNAME_LOWER_CASE, true);
    }
}
