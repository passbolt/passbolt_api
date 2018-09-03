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

use App\Model\Entity\Permission;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class SoftDeleteTest extends AppTestCase
{
    public $Groups;
    public $GroupsUsers;
    public $Permissions;
    public $Resources;
    public $Users;
    public $Secrets;

    public $fixtures = [
        'app.Base/users', 'app.Base/groups', 'app.Base/profiles',
        'app.Base/gpgkeys', 'app.Base/resources', 'app.Base/favorites', 'app.Alt0/secrets',
        'app.Alt0/groups_users', 'app.Alt0/permissions'
    ];

    public function setUp()
    {
        parent::setUp();
        $this->Users = TableRegistry::get('Users');
        $this->Permissions = TableRegistry::get('Permissions');
        $this->GroupsUsers = TableRegistry::get('GroupsUsers');
        $this->Resources = TableRegistry::get('Resources');
        $this->Groups = TableRegistry::get('Groups');
        $this->Secrets = TableRegistry::get('Secrets');
    }

    public function testGroupsSoftDeleteSimpleSuccess()
    {
        // Freelancer is a group that does not own anything
        $group = $this->Groups->get(UuidFactory::uuid('group.id.freelancer'));
        $this->assertTrue($this->Groups->softDelete($group));

        // Group should be marked as deleted
        $group = $this->Groups->get(UuidFactory::uuid('group.id.freelancer'));
        $this->assertTrue($group->deleted);

        // Frances should have been deleted from group freelancer
        $user = $this->Users->get(UuidFactory::uuid('user.id.frances'));
        $groups = $this->GroupsUsers->find()
            ->select()->where(['user_id' => $user->id])->first();
        $this->assertEmpty($groups);

        // Permissions should be dropped from docker resource
        $permissions = $this->Permissions->find()
            ->select()->where(['aco_foreign_key' => UuidFactory::uuid('resource.id.docker')])->all();
        $this->assertEquals(1, count($permissions));

        // Check the secrets have been deleted
        $secrets = $this->Secrets->find()
            ->select()->where(['resource_id' => UuidFactory::uuid('resource.id.docker')])->all();
        $this->assertEquals(1, count($secrets));
    }

    public function testGroupsSoftDeleteSoleResourceOwnerError()
    {
        // Creative and account groups are sole owner of shared resources
        $group = $this->Groups->get(UuidFactory::uuid('group.id.accounting'));
        $this->assertFalse($this->Groups->softDelete($group));
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);

        $group = $this->Groups->get(UuidFactory::uuid('group.id.creative'));
        $this->assertFalse($this->Groups->softDelete($group));
        $errors = $group->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
    }

    public function testGroupsSoftDeleteSoleResourceOwnerFixSuccess()
    {
        // Make carol own the resource framasoft resource previously solely owned by creative group
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft')
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        // Creative group can now be deleted
        $group = $this->Groups->get(UuidFactory::uuid('group.id.creative'));
        $this->assertTrue($this->Groups->softDelete($group));

        // Composer resources solely owned by group has been deleted
        $composer = $this->Resources->get(UuidFactory::uuid('resource.id.composer'));
        $this->assertTrue($composer->deleted);
    }
}
