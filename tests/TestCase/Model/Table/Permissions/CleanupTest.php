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

namespace App\Test\TestCase\Model\Table\Permissions;

use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\CleanupTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class CleanupTest extends AppTestCase
{
    public $Users;
    public $Groups;
    public $Permissions;
    public $Resources;
    public $options;

    public $fixtures = [
        'app.Base/Groups', 'app.Base/Users', 'app.Alt0/GroupsUsers',
        'app.Alt0/Permissions', 'app.Base/Resources'
    ];

    use CleanupTrait;

    public function setUp()
    {
        parent::setUp();
        $this->Permissions = TableRegistry::getTableLocator()->get('Permissions');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Resources = TableRegistry::getTableLocator()->get('Resources');
        $this->options = ['accessibleFields' => [
            'aco' => true,
            'aco_foreign_key' => true,
            'aro' => true,
            'aro_foreign_key' => true,
            'type' => true,
        ]];
    }

    public function tearDown()
    {
        unset($this->Users);
        unset($this->Groups);
        unset($this->Permissions);
        unset($this->Resources);
        parent::tearDown();
    }

    public function testCleanupPermissionsSoftDeletedUsersSuccess()
    {
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.sofia'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedUsers', $originalCount);
    }

    public function testCleanupPermissionsHardDeletedUsersSuccess()
    {
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'User',
            'aro_foreign_key' => UuidFactory::uuid('user.id.nope'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedUsers', $originalCount);
    }

    public function testCleanupPermissionsSoftDeletedGroupsSuccess()
    {
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.deleted'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedGroups', $originalCount);
    }

    public function testCleanupPermissionsHardDeletedGroupsSuccess()
    {
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.nope'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedGroups', $originalCount);
    }

    public function testCleanupPermissionsSoftDeletedResourcesSuccess()
    {
        $this->Permissions->deleteAll(['aco_foreign_key' => UuidFactory::uuid('resource.id.jquery')]);
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.jquery'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupSoftDeletedResources', $originalCount);
    }

    public function testCleanupPermissionsHardDeletedResourcesSuccess()
    {
        $originalCount = $this->Permissions->find()->count();
        $perm = $this->Permissions->newEntity([
            'aco' => 'Resource',
            'aco_foreign_key' => UuidFactory::uuid('resource.id.nope'),
            'aro' => 'Group',
            'aro_foreign_key' => UuidFactory::uuid('group.id.accounting'),
            'type' => Permission::OWNER,
        ], $this->options);
        $this->Permissions->save($perm, ['checkRules' => false]);
        $this->runCleanupChecks('Permissions', 'cleanupHardDeletedResources', $originalCount);
    }
}
