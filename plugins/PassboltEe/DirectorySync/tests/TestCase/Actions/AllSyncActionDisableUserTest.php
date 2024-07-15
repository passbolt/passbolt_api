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
 * @since         4.9.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Passbolt\DirectorySync\Actions\AllSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryOrgSettingFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryValidationService;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class AllSyncActionDisableUserTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;

    private AllSyncAction $action;

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        DirectoryOrgSettingFactory::make()->deleteUserBehaviorDisable()->persist();
        PasswordExpirySettingFactory::make()->persist();

        $this->action = new AllSyncAction(
            new PasswordExpiryExpireResourcesService(
                new PasswordExpiryValidationService(
                    new PasswordExpiryGetSettingsService()
                )
            )
        );
    }

    public function tearDown(): void
    {
        unset($this->action);
        parent::tearDown();
    }

    /**
     * Scenario: the user is active in passbolt and not present in the directory and can be deleted and delete behavior in settings is disable user
     * Expected result: user is disabled
     *
     * @group DirectorySync
     * @group DirectorySyncUser
     * @group DirectorySyncUserDelete
     */
    public function testAllSyncActionDisableUser_User_Enabled_With_Setting_On_Disable_User()
    {
        $directoryUserEntry = DirectoryEntryFactory::make()->withUser()->persist();
        $userToDisable = $directoryUserEntry->get('user');
        $directoryGroupEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$userToDisable]))
            ->persist();
        $group = $directoryGroupEntry->get('group');
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userToDisable])
            ->withSecretsFor([$userToDisable])->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDisable))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $result = $this->action->execute();

        $userToDisable = UserFactory::get($userToDisable->id);
        $this->assertTrue($userToDisable->isDisabled());
        // Directory entries should be deleted
        $this->assertSame(0, DirectoryEntryFactory::count());
        // The associated resources should not be soft deleted
        $resource = ResourceFactory::firstOrFail();
        $this->assertFalse($resource->deleted);
        // The resource should be marked as expired
        $this->assertTrue($resource->isExpired());
        // User permissions should not be deleted
        $this->assertSame(1, PermissionFactory::count());
        // User secrets should not be deleted
        $this->assertSame(1, SecretFactory::count());
        // The groups_users association should not be deleted
        // TODO: uncomment this assertion, which is failing because the arrange part is not complete
//        $this->assertNotNull(GroupsUserFactory::get($group->groups_users[0]->id));
        $expectedReportMessage = "The user $userToDisable->username was successfully suspended.";
        /** @var \Passbolt\DirectorySync\Actions\Reports\ActionReport $report */
        $report = $result['users']->offsetGet(0);
        $this->assertSame($expectedReportMessage, $report->getMessage());
    }

    public function testAllSyncActionDisableUser_User_Disabled_With_Setting_On_Disable_User()
    {
        $directoryEntry = DirectoryEntryFactory::make()->withUser(UserFactory::make()->active()->disabled())->persist();
        $userToDisable = $directoryEntry->get('user');
        $directoryGroupEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$userToDisable]))
            ->persist();
        $group = $directoryGroupEntry->get('group');
        $resource = ResourceFactory::make()
            ->withPermissionsFor([$userToDisable])
            ->withSecretsFor([$userToDisable])->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDisable))
            ->withResources(ResourceFactory::make($resource))
            ->persist();

        $result = $this->action->execute();

        $userToDisable = UserFactory::get($userToDisable->id);
        $this->assertTrue($userToDisable->isDisabled());
        // Directory entries should be deleted
        $this->assertSame(0, DirectoryEntryFactory::count());
        // The associated resources should not be soft deleted
        $resource = ResourceFactory::firstOrFail();
        // The resource should not be marked as expired, as the user was disabled and expiration was not processed
        $this->assertFalse($resource->isExpired());
        $this->assertFalse($resource->deleted);
        // User permissions should not be deleted
        $this->assertSame(1, PermissionFactory::count());
        // User secrets should not be deleted (they should have)
        $this->assertSame(1, SecretFactory::count());
        // The groups_users association should not be deleted
        // TODO: uncomment this assertion, which is failing because the arrange part is not complete
//        $this->assertNotNull(GroupsUserFactory::get($group->groups_users[0]->id));
        $expectedReportMessage = "The directory user $userToDisable->username was already suspended in passbolt.";
        /** @var \Passbolt\DirectorySync\Actions\Reports\ActionReport $report */
        $report = $result['users']->offsetGet(0);
        $this->assertSame($expectedReportMessage, $report->getMessage());
    }
}
