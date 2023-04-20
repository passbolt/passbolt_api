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
namespace App\Test\Lib\Model;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

trait UsersModelTrait
{
    /**
     * Get a dummy resource with test data.
     * The comment returned passes a default validation.
     *
     * @param array $data Custom data that will be merged with the default content.
     * @return array Comment data
     */
    public static function getDummyUser($data = [])
    {
        $entityContent = [
            'name' => UuidFactory::uuid('user.id.dummy'),
            'username' => 'dummy@passbolt.com',
            'role_id' => UuidFactory::uuid('role.id.user'),
            'deleted' => false,
            'active' => false,
            'profile' => [
                'first_name' => 'dummy',
                'last_name' => 'content',
            ],
        ];
        $entityContent = array_merge($entityContent, $data);

        return $entityContent;
    }

    /**
     * Get dummy data to update a test user
     * It merges it with a passable $override array
     * and returns the final array
     *
     * @param array $override to override the test array
     * @return array Update user data
     */
    public static function getUserUpdateData(array $override = [])
    {
        $updateData = [
            'username' => 'user@domain.com',
            'role_id' => UuidFactory::uuid('role.id.admin'),
            'deleted' => true,
            'active' => false,
            'profile' => [
                'first_name' => 'updated',
                'last_name' => 'name',
            ],
        ];

        return array_merge($updateData, $override);
    }

    /**
     * Asserts that an object has all the attributes a user should have.
     *
     * @param object $user
     */
    protected function assertUserAttributes($user)
    {
        $attributes = ['id', 'role_id', 'username', 'active', 'deleted', 'created', 'modified'];
        $this->assertObjectHasAttributes($attributes, $user);
    }

    /**
     * Asserts than a user is soft deleted.
     *
     * @param string $id
     */
    protected function assertUserIsSoftDeleted($id)
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $groupsUsersTable = TableRegistry::getTableLocator()->get('GroupsUsers');
        $permissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $secretsTable = TableRegistry::getTableLocator()->get('Secrets');
        $favoritesTable = TableRegistry::getTableLocator()->get('Favorites');
        $gpgKeysTable = TableRegistry::getTableLocator()->get('Gpgkeys');

        $user = $usersTable->get($id);
        $this->assertTrue($user->deleted);

        $groupsUsers = $groupsUsersTable->find()->where(['user_id' => $id])->count();
        $this->assertEquals(0, $groupsUsers);

        $permissions = $permissionsTable->find()->where(['aro_foreign_key' => $id])->count();
        $this->assertEquals(0, $permissions);

        $secrets = $secretsTable->find()->where(['user_id' => $id])->count();
        $this->assertEquals(0, $secrets);

        $favorites = $favoritesTable->find()->where(['user_id' => $id])->count();
        $this->assertEquals(0, $favorites);

        $gpgKeys = $gpgKeysTable->find()->where(['user_id' => $id, 'deleted' => 0])->count();
        $this->assertEquals(0, $gpgKeys);
    }

    /**
     * Asserts than a user is not soft deleted.
     *
     * @param string $id
     */
    protected function assertUserIsNotSoftDeleted($id)
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $usersTable->get($id);
        $this->assertFalse($user->deleted);
    }

    /**
     * Creates and saves a dummy user to the DB
     * and returns it's reference
     */
    protected function createTestUser()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $testUser = $usersTable->newEntity(self::getDummyUser(), static::getEntityDefaultOptions());

        $errors = $testUser->getErrors();
        $this->assertEmpty($errors);
        $this->assertNotEmpty($testUser);

        return $usersTable->save($testUser);
    }

    protected function getNonExistingRoleId()
    {
        return UuidFactory::uuid('role.id.notexist');
    }
}
