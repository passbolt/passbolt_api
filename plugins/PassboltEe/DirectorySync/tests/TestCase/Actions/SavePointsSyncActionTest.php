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
 * @since         5.11.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Test\Factory\UserFactory;
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Actions\AllSyncAction;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryOrgSettingFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\DirectorySync\Test\Utility\Traits\AssertUsersTrait;

/**
 * Tests for PB-49159: savepoints must be enabled before transactions begin,
 * and dry-run mode must not persist any changes.
 *
 * @group DirectorySync
 * @group DirectorySyncSavePoints
 */
class SavePointsSyncActionTest extends DirectorySyncIntegrationTestCase
{
    use AssertUsersTrait;

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        DirectoryOrgSettingFactory::make()->deleteUserBehaviorDisable()->persist();
    }

    /**
     * Test that AllSyncAction dry-run does not persist new users created during sync.
     *
     * @return void
     */
    public function testAllSyncAction_DryRun_DoesNotPersistNewUsers(): void
    {
        // Arrange: a directory entry pointing to a user that exists in LDAP but not yet in Passbolt.
        DirectoryEntryFactory::make()->withUser()->persist();
        $userCountBefore = UserFactory::count();
        $directoryEntryCountBefore = DirectoryEntryFactory::count();

        $action = new AllSyncAction(new ResourcesExpireResourcesFallbackServiceService());
        $result = $action->execute(dryRun: true);

        // Assert: reports were generated (sync ran) but no new data persisted.
        $this->assertNotEmpty($result);
        $this->assertSame($userCountBefore, UserFactory::count(), 'Dry-run should not change user count.');
        $this->assertSame(
            $directoryEntryCountBefore,
            DirectoryEntryFactory::count(),
            'Dry-run should not change directory entry count.'
        );
    }

    /**
     * Test that AllSyncAction dry-run does not persist user deletions.
     *
     * @return void
     */
    public function testAllSyncAction_DryRun_DoesNotPersistDeletions(): void
    {
        // Arrange: a directory entry linked to a user, but the user is NOT in the directory data.
        // This means the sync should try to delete/soft-delete the user.
        $directoryEntry = DirectoryEntryFactory::make()->withUser(
            UserFactory::make()->active()
        )->persist();
        $userId = $directoryEntry->get('user')->id;

        $action = new AllSyncAction(new ResourcesExpireResourcesFallbackServiceService());
        $action->execute(dryRun: true);

        // Assert: the user should still be active (not soft-deleted) after dry-run.
        $user = UserFactory::get($userId);
        $this->assertFalse($user->isDeleted(), 'Dry-run should not soft-delete users.');
    }

    /**
     * Test that savepoints are enabled on the connection after AllSyncAction executes.
     *
     * @return void
     */
    public function testAllSyncAction_EnablesSavePointsOnConnection(): void
    {
        $conn = TableRegistry::getTableLocator()->get('Users')->getConnection();

        // Disable savepoints to verify the action re-enables them.
        $conn->enableSavePoints(false);
        $this->assertFalse($conn->isSavePointsEnabled());

        $action = new AllSyncAction(new ResourcesExpireResourcesFallbackServiceService());
        $action->execute();

        $this->assertTrue($conn->isSavePointsEnabled(), 'AllSyncAction should enable savepoints.');
    }
}
