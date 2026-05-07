<?php
declare(strict_types=1);

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
 * @since         5.7.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Actions\GroupSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryOrgSettingFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryRelationFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\DirectorySync\Test\Utility\Traits\MockDirectoryTrait;

class GroupUserSyncActionRemoveDisabledUserTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;
    use MockDirectoryTrait;

    private GroupSyncAction $action;

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        DirectoryOrgSettingFactory::make()->deleteUserBehaviorDisable()->persist();

        $this->action = new GroupSyncAction(
            new ResourcesExpireResourcesFallbackServiceService()
        );
    }

    public function tearDown(): void
    {
        unset($this->action);
        parent::tearDown();
    }

    /**
     * Scenario: a disabled user is a member of a group.
     * The settings are set to disable users when deleted from LDAP.
     * The user is removed via LDAP from the group
     * Expected result: on synchronization, the user should be also removed from the group in passbolt
     */
    public function testGroupSyncActionRemoveDisabledUser()
    {
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $directoryUserEntry */
        $directoryUserEntry = DirectoryEntryFactory::make()
            ->withUser(UserFactory::make()->active()->disabled())
            ->persist();
        $userDisabled = $directoryUserEntry->user;
        /** @var \Passbolt\DirectorySync\Model\Entity\DirectoryEntry $directoryGroupEntry */
        $directoryGroupEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$userDisabled]))
            ->persist();

        /** @var \App\Model\Entity\Group $group */
        $group = $directoryGroupEntry->get('group');
        $groupUser = $group->groups_users[0];

        DirectoryRelationFactory::make(['id' => $groupUser->id])
            ->setParentKey($directoryGroupEntry->id)
            ->setChildKey($directoryUserEntry->id)
            ->persist();

        $this->mockDirectoryGroupData($group->name, [
            'id' => $directoryGroupEntry->id,
        ]);

        $report = $this->action->execute();

        // The disabled user should be removed from the group
        $this->assertSame(0, GroupsUserFactory::count());
        $this->assertSame(2, UserFactory::count()); // The user and the admin in setUp
        $this->assertSame(1, GroupFactory::count());

        $this->assertSame(1, $report->count());
        $expectedUserReportMessage = "The user $userDisabled->username was successfully removed from the group $group->name.";
        /** @var \Passbolt\DirectorySync\Actions\Reports\ActionReport $userReport */
        $userReport = $report->offsetGet(0);
        $this->assertSame($expectedUserReportMessage, $userReport->getMessage());
    }
}
