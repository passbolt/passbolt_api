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

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use PassboltTestData\Lib\PermissionMatrix;

class FindIndexTest extends AppTestCase
{
    public $Resources;

    public $fixtures = ['app.Base/Users', 'app.Base/Profiles', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions', 'app.Base/Avatars'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
    }

    public function testFilterHasAccess()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('resource');
        foreach ($permissionsMatrix as $resourceAlias => $resourcesExpectedPermissions) {
            // Extract expected users.
            $expectedUsersIds = [];
            foreach ($resourcesExpectedPermissions as $userAlias => $permissionType) {
                if ($permissionType) {
                    $expectedUsersIds[] = UuidFactory::uuid("user.id.$userAlias");
                }
            }

            // Find all the users who have access to the resource.
            $findIndexOptions['filter']['has-access'] = [UuidFactory::uuid("resource.id.$resourceAlias")];
            $users = $this->Users->findIndex(Role::USER, $findIndexOptions)->all();
            $usersIds = Hash::extract($users->toArray(), '{n}.id');

            $this->assertEquals(count($expectedUsersIds), count($usersIds), "The filter hasAccess does not return expected users for the resource $resourceAlias");
            $this->assertEmpty(array_diff($expectedUsersIds, $usersIds), "The filter hasAccess does not return expected users for the resource $resourceAlias");
        }
    }

    /**
     * @throws \Exception
     */
    public function testFilterHasNotPermission()
    {
        $permissionsMatrix = PermissionMatrix::getUsersResourcesPermissions('resource');
        foreach ($permissionsMatrix as $resourceAlias => $resourcesExpectedPermissions) {
            // Extract expected users.
            $expectedUsersIds = [];
            foreach ($resourcesExpectedPermissions as $userAlias => $permissionType) {
                if (!$permissionType) {
                    $expectedUsersIds[] = UuidFactory::uuid("user.id.$userAlias");
                }
            }

            // Find all the users who have no permission for the resource.
            $findIndexOptions['filter']['has-not-permission'] = [UuidFactory::uuid("resource.id.$resourceAlias")];
            $findIndexOptions['filter']['is-active'] = true;
            $users = $this->Users->findIndex(Role::ADMIN, $findIndexOptions)->all();
            $usersIds = Hash::extract($users->toArray(), '{n}.id');

            // Check there is no diff between the expected users and users returned by the model.
            $diff = array_diff($usersIds, $expectedUsersIds);
            $this->assertEmpty($diff, "The filter hasNotPermission does not return expected users for the resource $resourceAlias");
        }
    }
}
