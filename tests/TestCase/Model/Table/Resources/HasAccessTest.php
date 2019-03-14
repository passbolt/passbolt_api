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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Table\ResourcesTable;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use PassboltTestData\Lib\PermissionMatrix;

class HasAccessTest extends AppTestCase
{
    public $Resources;

    public $fixtures = ['app.Base/Users', 'app.Base/Groups', 'app.Base/GroupsUsers', 'app.Base/Resources', 'app.Base/Permissions'];

    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function tearDown()
    {
        unset($this->Resources);

        parent::tearDown();
    }

    public function testPermissions()
    {
        $permissionsMatrix = PermissionMatrix::getCalculatedUsersResourcesPermissions('user');
        foreach ($permissionsMatrix as $userAlias => $usersExpectedPermissions) {
            $userId = UuidFactory::uuid("user.id.$userAlias");
            foreach ($usersExpectedPermissions as $resourceAlias => $permissionType) {
                $resourceId = UuidFactory::uuid("resource.id.$resourceAlias");
                $hasAccess = $this->Resources->hasAccess($userId, $resourceId);
                if ($permissionType == 0) {
                    $this->assertFalse($hasAccess, "$userAlias should not have access to $resourceAlias");
                } else {
                    $this->assertTrue($hasAccess, "$userAlias should have access to $resourceAlias");
                }
            }
        }
    }

    public function testErrorInvalidArgumentUserId()
    {
        try {
            $this->Resources->hasAccess('not-valid', UuidFactory::uuid());
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testErrorInvalidArgumentResourceId()
    {
        try {
            $this->Resources->hasAccess(UuidFactory::uuid(), 'not-valid');
        } catch (\InvalidArgumentException $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
