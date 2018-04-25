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

namespace App\Test\TestCase\Model\Table\Users;

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
        'app.Base/users', 'app.Base/groups', 'app.Base/favorites',
        'app.Base/profiles', 'app.Base/gpgkeys', 'app.Base/resources', 'app.Base/secrets',
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

    public function testUsersSoftDeleteSimpleSuccess()
    {
        // Frances is not the admin of any group or owner of any resources
        $user = $this->Users->get(UuidFactory::uuid('user.id.frances'));
        $this->assertTrue($this->Users->softDelete($user));

        // User should be marked as deleted
        $user = $this->Users->get(UuidFactory::uuid('user.id.frances'));
        $this->assertTrue($user->deleted);

        // Frances should have been deleted from group freelancer
        $groups = $this->GroupsUsers->find()
            ->select()->where(['user_id' => $user->id])->first();
        $this->assertEmpty($groups);

        // Frances permissions should have been delete from bower resource
        $permissions = $this->Permissions->find()
            ->select()->where([
                'aco_foreign_key' => UuidFactory::uuid('resource.id.bower')
            ])->all();
        $this->assertEquals(count($permissions), 3);

        // There should not be any secrets
        $secrets = $this->Secrets->find()
            ->select()->where([
                ['user_id' => $user->id]
            ])->all();
        $this->assertEquals(count($secrets), 0);
    }

    public function testUsersSoftDeleteCheckAllRulesError()
    {
        $user = $this->Users->get(UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertNotEmpty($errors['id']['soleManagerOfGroupOwnerOfSharedResource']);
    }

    public function testUsersSoftDeleteSoleResourceOwnerErrorFix()
    {
        // Ada breaks all the rules (see next three tests below)
        $user = $this->Users->get(UuidFactory::uuid('user.id.ada'));
        $this->Permissions->deleteAll([
            'aro_foreign_key' => UuidFactory::uuid('user.id.ada'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april')
        ]);
        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
        $this->assertNotEmpty($errors['id']['soleManagerOfGroupOwnerOfSharedResource']);
        $this->assertFalse(isset($errors['id']['soleOwnerOfSharedResource']));
    }

    public function testUsersSoftDeleteSoleManagerOfNonEmptyGroupErrorFix()
    {
        // Ada cannot be deleted because it is sole admin of group accounting
        // and that group owns a bunch of resources
        $user = $this->Users->get(UuidFactory::uuid('user.id.ada'));

        // Making betty admin of this group should solve the issue
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.betty')
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleManagerOfNonEmptyGroup']));
        $this->assertNotEmpty($errors['id']['soleManagerOfGroupOwnerOfSharedResource']);
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
    }

    public function testUsersSoftDeleteSoleAdminOfGroupOwnerOfSharedResourceErrorFix()
    {
        // Ada cannot be deleted because it is sole member of group creative
        // and that group owns the framasoft resource
        $user = $this->Users->get(UuidFactory::uuid('user.id.ada'));

        // Making carol own the resource too should solve the issue
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft')
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        $this->assertFalse($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertFalse(isset($errors['id']['soleManagerOfGroupOwnerOfSharedResource']));
        $this->assertNotEmpty($errors['id']['soleOwnerOfSharedResource']);
        $this->assertNotEmpty($errors['id']['soleManagerOfNonEmptyGroup']);
    }

    public function testUsersSoftDeleteAllErrorFix()
    {
        // Ada breaks all the rules (see next three tests below)
        $user = $this->Users->get(UuidFactory::uuid('user.id.ada'));

        // Make betty own the april resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => UuidFactory::uuid('user.id.betty'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.april')
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        // Make carol own the resource framasoft resource
        $permission = $this->Permissions->find()->select()->where([
            'aro_foreign_key' => UuidFactory::uuid('user.id.carol'),
            'aco_foreign_key' => UuidFactory::uuid('resource.id.framasoft')
        ])->first();
        $permission->type = Permission::OWNER;
        $this->Permissions->save($permission);

        // Make betty admin of accounting group
        $groupUser = $this->GroupsUsers->find()->select()->where([
            'group_id' => UuidFactory::uuid('group.id.accounting'),
            'user_id' => UuidFactory::uuid('user.id.betty')
        ])->first();
        $groupUser->is_admin = true;
        $this->GroupsUsers->save($groupUser);

        // Check if some favorites exist for ada
        $Favorites = TableRegistry::get('Favorites');
        $favorites = $Favorites->find()->where(['user_id' => $user->id])->all()->toArray();
        $this->assertNotEmpty($favorites);

        // Can delete ada
        $this->assertTrue($this->Users->softDelete($user));
        $errors = $user->getErrors();
        $this->assertEmpty($errors);

        // Apache resource, only owned by ada was deleted
        $apache = $this->Resources->get(UuidFactory::uuid('resource.id.apache'));
        $this->assertTrue($apache->deleted);

        // April is still there since it's owned by betty
        $april = $this->Resources->get(UuidFactory::uuid('resource.id.april'));
        $this->assertFalse($april->deleted);

        // Developer group still there since betty is also admin
        $developer = $this->Groups->get(UuidFactory::uuid('group.id.developer'));
        $this->assertFalse($developer->deleted);

        // Creative group is gone (group where ada is alone)
        $creative = $this->Groups->get(UuidFactory::uuid('group.id.creative'));
        $this->assertTrue($creative->deleted);

        // Composer, owned by creative group is gone
        $composer = $this->Resources->get(UuidFactory::uuid('resource.id.composer'));
        $this->assertTrue($composer->deleted);

        // Framasoft previously owned by creative is still theres since it's owned by carol
        $framasoft = $this->Resources->get(UuidFactory::uuid('resource.id.framasoft'));
        $this->assertFalse($framasoft->deleted);

        // Check Favorites are gone
        $favorites = $Favorites->find()->where(['user_id' => $user->id])->all()->toArray();
        $this->assertEmpty($favorites);

        // There should not be any secrets
        $secrets = $this->Secrets->find()
            ->select()->where([
                ['user_id' => $user->id]
            ])->all();
        $this->assertEquals(count($secrets), 0);
    }
}
