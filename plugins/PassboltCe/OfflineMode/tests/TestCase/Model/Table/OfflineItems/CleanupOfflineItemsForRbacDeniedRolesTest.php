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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Model\Table\OfflineItems;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Test\Factory\ActionFactory;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Test\Factory\RbacFactory;

/**
 * @covers \Passbolt\OfflineMode\Model\Table\OfflineItemsTable::cleanupOfflineItemsForRbacDeniedRoles
 */
class CleanupOfflineItemsForRbacDeniedRolesTest extends AppTestCaseV5
{
    private ?OfflineItemsTable $OfflineItems = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->OfflineItems = TableRegistry::getTableLocator()->get('Passbolt/OfflineMode.OfflineItems');
    }

    public function tearDown(): void
    {
        unset($this->OfflineItems);
        parent::tearDown();
    }

    public function testCleanupOfflineItemsForRbacDeniedRoles_Success(): void
    {
        $viewAction = ActionFactory::make()
            ->name(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW)
            ->persist();
        // entries related to items going to be deleted
        $deniedRole = RoleFactory::make()->persist();
        RbacFactory::make()
            ->setField('role_id', $deniedRole->get('id'))
            ->setAction($viewAction)
            ->deny()
            ->persist();
        $deniedUser = UserFactory::make()->active()
            ->with('Roles', $deniedRole)
            ->persist();
        $offlineItemToBeDeleted = OfflineItemFactory::make()->setUser($deniedUser)->persist();
        // entries related to items going to be kept
        $allowedRole = RoleFactory::make()->persist();
        RbacFactory::make()
            ->setField('role_id', $allowedRole->get('id'))
            ->setAction($viewAction)
            ->allow()
            ->persist();
        $allowedUser = UserFactory::make()->active()
            ->with('Roles', $allowedRole)
            ->persist();
        $offlineItemToBeKept = OfflineItemFactory::make()->setUser($allowedUser)->persist();

        $result = $this->OfflineItems->cleanupOfflineItemsForRbacDeniedRoles(false);

        $this->assertSame(1, $result);
        $this->assertFalse($this->OfflineItems->exists(['id' => $offlineItemToBeDeleted->get('id')]));
        $this->assertTrue($this->OfflineItems->exists(['id' => $offlineItemToBeKept->get('id')]));
    }

    public function testCleanupOfflineItemsForRbacDeniedRoles_Success_DryRun(): void
    {
        $viewAction = ActionFactory::make()
            ->name(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW)
            ->persist();

        $deniedRole = RoleFactory::make()->persist();
        RbacFactory::make()
            ->setField('role_id', $deniedRole->get('id'))
            ->setAction($viewAction)
            ->deny()
            ->persist();
        $userA = UserFactory::make()->active()
            ->with('Roles', $deniedRole)
            ->persist();
        $userB = UserFactory::make()->active()
            ->with('Roles', $deniedRole)
            ->persist();
        $itemA = OfflineItemFactory::make()->setUser($userA)->persist();
        $itemB = OfflineItemFactory::make()->setUser($userB)->persist();

        $count = $this->OfflineItems->cleanupOfflineItemsForRbacDeniedRoles(true);

        $this->assertSame(2, $count);
        $this->assertTrue($this->OfflineItems->exists(['id' => $itemA->get('id')]));
        $this->assertTrue($this->OfflineItems->exists(['id' => $itemB->get('id')]));
    }

    /**
     * Edge-case where RBAC entries are missing for the role.
     * RBAC entries should be present all the time for each role, if not present issue is some other part.
     *
     * @return void
     */
    public function testCleanupOfflineItemsForRbacDeniedRoles_Success_NoRbacRowForActionKeepsAllRows(): void
    {
        $role = RoleFactory::make()->persist();
        $user = UserFactory::make()->active()
            ->with('Roles', $role)
            ->persist();
        $item = OfflineItemFactory::make()->setUser($user)->persist();

        $this->assertSame(0, $this->OfflineItems->cleanupOfflineItemsForRbacDeniedRoles(false));
        $this->assertTrue($this->OfflineItems->exists(['id' => $item->get('id')]));
    }
}
