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

namespace App\Test\TestCase\Model\Table\GroupsUsers;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

class CleanupTest extends AppTestCase
{

    public $GroupsUsers;
    public $Groups;
    public $fixtures = ['app.Base/Groups', 'app.Base/Users', 'app.Base/GroupsUsers'];
    public $options;

    use CleanupTrait;

    public function setUp()
    {
        parent::setUp();
        $this->GroupsUsers = TableRegistry::getTableLocator()->get('GroupsUsers');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->options = ['accessibleFields' => [
            'group_id' => true,
            'user_id' => true,
            'is_admin' => true,
            'created_by' => true
        ]];
    }

    public function tearDown()
    {
        unset($this->GroupsUsers);
        unset($this->Groups);
        unset($this->Users);
        parent::tearDown();
    }

    public function testCleanupGroupsUsersSoftDeletedUsersSuccess()
    {
        $originalCount = $this->GroupsUsers->find()->count();
        $gu = $this->GroupsUsers->newEntity([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.sofia'),
            'is_admin' => false,
            'created_by' => UuidFactory::uuid('user.id.admin'),
        ], $this->options);
        $this->GroupsUsers->save($gu, ['checkRules' => false]);
        $this->runCleanupChecks('GroupsUsers', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupGroupsUsersHardDeletedUsersSuccess()
    {
        $originalCount = $this->GroupsUsers->find()->count();
        $gu = $this->GroupsUsers->newEntity([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.nope'),
            'is_admin' => false,
        ], $this->options);
        $this->GroupsUsers->save($gu, ['checkRules' => false]);
        $this->runCleanupChecks('GroupsUsers', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupGroupsUsersSoftDeletedGroupsSuccess()
    {
        $originalCount = $this->GroupsUsers->find()->count();
        $gu = $this->GroupsUsers->newEntity([
            'group_id' => UuidFactory::uuid('group.id.deleted'),
            'user_id' => UuidFactory::uuid('user.id.ada'),
            'is_admin' => false,
            'created_by' => UuidFactory::uuid('user.id.admin'),
        ], $this->options);
        $this->GroupsUsers->save($gu, ['checkRules' => false]);
        $this->runCleanupChecks('GroupsUsers', 'cleanupSoftDeletedGroups', $originalCount);
    }

    public function testCleanupGroupsUsersHardDeletedGroupsSuccess()
    {
        $originalCount = $this->GroupsUsers->find()->count();
        $gu = $this->GroupsUsers->newEntity([
            'group_id' => UuidFactory::uuid('group.id.nope'),
            'user_id' => UuidFactory::uuid('user.id.nope'),
            'is_admin' => false,
            'created_by' => UuidFactory::uuid('user.id.admin'),
        ], $this->options);
        $this->GroupsUsers->save($gu, ['checkRules' => false]);
        $this->runCleanupChecks('GroupsUsers', 'cleanupHardDeletedGroups', $originalCount);
    }
}
