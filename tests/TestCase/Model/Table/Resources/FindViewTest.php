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

namespace App\Test\TestCase\Model\Table\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\ResourcesTable;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Exception;

class FindViewTest extends AppTestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\ResourcesTable
     */
    public $Resources;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Resources') ? [] : ['className' => ResourcesTable::class];
        $this->Resources = TableRegistry::getTableLocator()->get('Resources', $config);
    }

    public function testSuccess()
    {
        $user = UserFactory::make()->persist();
        $r1 = ResourceFactory::make()->withPermissionsFor([$user])->persist();
        $resources = $this->Resources->findView($user->id, $r1->id);

        // Expected fields.
        $resource = $resources->first();
        $this->assertResourceAttributes($resource);
        $this->assertEquals($r1->id, $resource->id);
        // Not expected fields.
        $this->assertObjectNotHasAttribute('secrets', $resource);
        $this->assertObjectNotHasAttribute('creator', $resource);
        $this->assertObjectNotHasAttribute('modifier', $resource);
        $this->assertObjectNotHasAttribute('favorite', $resource);
    }

    public function testPermissions_UserAccess(): void
    {
        [$user, $otherUser] = UserFactory::make(2)->persist();

        $r1HasAccess = ResourceFactory::make()->withPermissionsFor([$user, $otherUser])->persist();
        $r2HasAccess = ResourceFactory::make()->withPermissionsFor([$user], Permission::READ)->persist();
        $r3HasAccess = ResourceFactory::make()->withPermissionsFor([$user], Permission::UPDATE)->persist();
        $r4NoAccess = ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();

        $this->assertNotNull($this->Resources->findView($user->id, $r1HasAccess->id)->first());
        $this->assertNotNull($this->Resources->findView($user->id, $r2HasAccess->id)->first());
        $this->assertNotNull($this->Resources->findView($user->id, $r3HasAccess->id)->first());
        $this->assertNull($this->Resources->findView($user->id, $r4NoAccess->id)->first());
    }

    public function testPermissions_AccessViaGroup(): void
    {
        [$user, $otherUser] = UserFactory::make(2)->persist();
        $group = GroupFactory::make()->withGroupsManagersFor([$user])->persist();

        $r1HasAccess = ResourceFactory::make()->withPermissionsFor([$group, $otherUser])->persist();
        $r2HasAccess = ResourceFactory::make()->withPermissionsFor([$group], Permission::READ)->persist();
        $r3HasAccess = ResourceFactory::make()->withPermissionsFor([$group], Permission::UPDATE)->persist();
        $r4NoAccess = ResourceFactory::make()->withPermissionsFor([$otherUser])->persist();

        $this->assertNotNull($this->Resources->findView($user->id, $r1HasAccess->id)->first());
        $this->assertNotNull($this->Resources->findView($user->id, $r2HasAccess->id)->first());
        $this->assertNotNull($this->Resources->findView($user->id, $r3HasAccess->id)->first());
        $this->assertNull($this->Resources->findView($user->id, $r4NoAccess->id)->first());
    }

    public function testPermissions_SoftDeletedResourceIsNotVisible(): void
    {
        $user = UserFactory::make()->persist();
        $deleted = ResourceFactory::make()->withPermissionsFor([$user])->setDeleted()->persist();

        $this->assertNull($this->Resources->findView($user->id, $deleted->id)->first());
    }

    public function testErrorInvalidUserIdParameter()
    {
        try {
            $this->Resources->findView('not-valid', UuidFactory::uuid());
        } catch (Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }

    public function testErrorInvalidResourceIdParameter()
    {
        try {
            $this->Resources->findView(UuidFactory::uuid(), 'not-valid');
        } catch (Exception $e) {
            return $this->assertTrue(true);
        }
        $this->fail('Expect an exception');
    }
}
