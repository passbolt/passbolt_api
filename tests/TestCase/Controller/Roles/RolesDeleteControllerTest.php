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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Controller\Roles;

use App\Model\Entity\Role;
use App\Service\Roles\RolesDeleteService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\Rbacs\Test\Factory\RbacFactory;
use Passbolt\Rbacs\Test\Factory\UiActionFactory;
use Passbolt\Rbacs\Test\Lib\RbacsIntegrationTestCase;

/**
 * @covers \App\Controller\Roles\RolesDeleteController
 */
class RolesDeleteControllerTest extends RbacsIntegrationTestCase
{
    public function testRolesDeleteController_Success(): void
    {
        // Enable event tracking, required to test events.
        EventManager::instance()->setEventList(new EventList());
        RoleFactory::make()->guest()->persist();
        $userRole = RoleFactory::make()->user()->persist();
        $adminRole = RoleFactory::make()->admin()->persist();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        // rbacs
        $this->createDummyRbacs($userRole);
        $this->createDummyRbacs($adminRole);
        $this->createDummyRbacs($role);
        // deleted user
        UserFactory::make(2)
            ->with('Roles', $role)
            ->deleted()
            ->persist();

        $admin = $this->logInAsAdmin();
        $this->deleteJson("/roles/$role->id.json");

        $this->assertSuccess();
        /** @var \App\Model\Entity\Role $roleUpdated */
        $roleUpdated = RoleFactory::find()->where(['id' => $role->id])->firstOrFail();
        $this->assertNotNull($roleUpdated->deleted);
        $this->assertSame($admin->id, $roleUpdated->deleted_by);
        // Assert event fired
        $this->assertEventFired(RolesDeleteService::AFTER_ROLE_DELETE_SUCCESS_EVENT_NAME);
        // Assert rbacs are deleted
        $this->assertSame(0, RbacFactory::find()->where(['role_id' => $role->id])->count());
        $this->assertGreaterThan(0, RbacFactory::find()->where()->count());
    }

    public function testRolesDeleteController_Error_NotLoggedIn(): void
    {
        RoleFactory::make()->guest()->persist();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->deleteJson("/roles/$role->id.json");
        $this->assertResponseCode(401);
    }

    public function testRolesDeleteController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->deleteJson("/roles/$role->id.json");
        $this->assertResponseCode(403);
    }

    public function testRolesDeleteController_Error_RoleDoesNotExist(): void
    {
        $this->logInAsAdmin();
        $roleId = UuidFactory::uuid();
        $this->deleteJson("/roles/$roleId.json");
        $this->assertResponseCode(404);
    }

    public function testRolesDeleteController_Error_RoleAlreadyDeleted(): void
    {
        $this->logInAsAdmin();
        $role = RoleFactory::make(['name' => 'sales'])->deleted()->persist();
        $this->deleteJson("/roles/$role->id.json");
        $this->assertResponseCode(404);
    }

    public function testRolesDeleteController_Error_NotJson(): void
    {
        $this->logInAsAdmin();
        $role = RoleFactory::make(['name' => 'sales'])->persist();
        $this->delete("/roles/$role->id");
        $this->assertResponseCode(404);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    private function createDummyRbacs(Role $role): void
    {
        $uiAction = UiActionFactory::make()->persist();
        $action = ActionFactory::make()->persist();
        RbacFactory::make(['role_id' => $role->id])->setUiAction($uiAction)->persist();
        RbacFactory::make(['role_id' => $role->id])->setAction($action)->persist();
    }
}
