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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Groups;

use App\Model\Entity\Permission;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryGroupsDeleteControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        PasswordExpirySettingFactory::make()->persist();
    }

    /**
     * Given that a user is member of a group and has accessed to the resource
     * And the group is deleted
     * Then the resource should be expired
     */
    public function testPasswordExpiryGroupsDeleteController(): void
    {
        $allUsers = [
            $owner1,
            $viewerActive1,
            $viewerActive2,
        ] = UserFactory::make(3)->persist();

        /** @var \App\Model\Entity\Group $group */
        $group = GroupFactory::make()
            ->withGroupsManagersFor([$owner1])
            ->withGroupsUsersFor([$viewerActive1, $viewerActive2])
            ->persist();

        [$resource1, $resource2] = ResourceFactory::make(4)
            ->withPermissionsFor([$owner1])
            ->withPermissionsFor([$group, $viewerActive2], Permission::READ)
            ->withSecretsFor($allUsers)
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive1))
            ->withResources(ResourceFactory::make($resource1))
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($viewerActive2))
            ->withResources(ResourceFactory::make($resource2))
            ->persist();

        $this->logInAs($owner1);
        $this->deleteJson("/groups/{$group->id}.json");

        $this->assertSuccess();

        $resource1 = ResourceFactory::get($resource1->id);
        $resource2 = ResourceFactory::get($resource2->id);
        $this->assertTrue($resource1->isExpired());
        // Resource 2 is not expired because $viewer1 did not access to it
        // $viewer2 did consume it, but keeps the permission through direct permission
        // So the resource does not expire
        $this->assertFalse($resource2->isExpired());
        // Owners should be notified
        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $owner1->username);
        $this->assertEmailQueueCount(3);
    }
}
