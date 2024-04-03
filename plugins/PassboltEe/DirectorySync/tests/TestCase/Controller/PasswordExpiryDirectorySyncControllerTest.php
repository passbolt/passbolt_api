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
 * @since         4.7.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Controller;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\DirectorySync\Test\Factory\DirectoryEntryFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncIntegrationTestCase;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

/**
 * @uses \Passbolt\DirectorySync\Controller\DirectorySyncController
 */
class PasswordExpiryDirectorySyncControllerTest extends DirectorySyncIntegrationTestCase
{
    use EmailQueueTrait;

    public $fixtures = [];

    /**
     * @group DirectorySync
     * @group DirectorySyncController
     */
    public function testPasswordExpiryDirectorySyncController_Remove_Permission_From_User_Delete_And_Group_Removal()
    {
        PasswordExpirySettingFactory::make()->persist();
        [$owner, $userInGroupToDelete] = UserFactory::make(2)->persist();

        // Arrange test to delete a user which had accessed to a shared resource
        $directoryEntry = DirectoryEntryFactory::make()
            ->withUser()
            ->persist();
        $userToDelete = $directoryEntry->get('user');

        $resourceSharedViaUserPermissionViewed = ResourceFactory::make()
            ->withPermissionsFor([$userToDelete, $owner])
            ->withSecretsFor([$userToDelete, $owner])
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDelete))
            ->withResources(ResourceFactory::make($resourceSharedViaUserPermissionViewed))
            ->persist();

        // Arrange test to delete a group with a user which had accessed to a shared resource
        $directoryEntry = DirectoryEntryFactory::make()
            ->withGroup(GroupFactory::make()->withGroupsUsersFor([$userInGroupToDelete]))
            ->persist();
        /** @var \App\Model\Entity\Group $groupToDelete */
        $groupToDelete = $directoryEntry->get('group');

        $resourceSharedViaGroupPermission = ResourceFactory::make()
            ->withPermissionsFor([$groupToDelete, $owner])
            ->withSecretsFor([$groupToDelete, $owner])
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userInGroupToDelete))
            ->withResources(ResourceFactory::make($resourceSharedViaGroupPermission))
            ->persist();

        $this->logInAsAdmin();
        $this->postJson('/directorysync/synchronize.json');

        $userToDelete = UserFactory::get($userToDelete->id);
        $this->assertTrue($userToDelete->isDeleted());
        $groupToDelete = GroupFactory::get($groupToDelete->id);
        $this->assertTrue($groupToDelete->deleted);
        $resourceSharedViaUserPermissionViewed = ResourceFactory::get($resourceSharedViaUserPermissionViewed->get('id'));
        $this->assertTrue($resourceSharedViaUserPermissionViewed->isExpired());
        $resourceSharedViaGroupPermission = ResourceFactory::get($resourceSharedViaUserPermissionViewed->get('id'));
        $this->assertTrue($resourceSharedViaGroupPermission->isExpired());

        $this->assertEmailQueueCount(1);
        $this->assertEmailInBatchContains('Some of your passwords expired', $owner->username);
    }
}
