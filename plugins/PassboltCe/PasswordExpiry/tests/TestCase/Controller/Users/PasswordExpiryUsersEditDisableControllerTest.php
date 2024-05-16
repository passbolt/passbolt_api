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
namespace Passbolt\PasswordExpiry\Test\TestCase\Controller\Users;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\Purifier;
use Cake\I18n\FrozenTime;
use Passbolt\Log\Test\Factory\SecretAccessFactory;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryUsersEditDisableControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP
        $this->mockUserAgent('PHPUnit');
        $this->mockUserIp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        PasswordExpirySettingFactory::make()->persist();
    }

    public function testPasswordExpiryUsersEditDisableController_Success_Admin_Disable_User(): void
    {
        [$admin1, $admin2] = UserFactory::make(2)->admin()->persist();
        /** @var \App\Model\Entity\User $userToDisable */
        $userToDisable = UserFactory::make(['profile' => ['last_name' => 'O\'Conner']])->user()->persist();
        [$ownerWithResourceShared1, $ownerWithGroupShared1] = UserFactory::make(2)->user()->persist();
        [$resourceSharedViewed, $resourceSharedNotViewed] = ResourceFactory::make(2)
            ->withPermissionsFor([$userToDisable, $ownerWithResourceShared1])
            ->withSecretsFor([$userToDisable, $ownerWithResourceShared1])
            ->persist();

        $group = GroupFactory::make()
            ->withGroupsUsersFor([$userToDisable, $ownerWithGroupShared1])
            ->persist();
        [$resourcesSharedViaGroupViewed, $resourcesSharedViaGroupNotViewed] = ResourceFactory::make(2)
            ->withPermissionsFor([$group])
            ->withSecretsFor([$group])
            ->persist();

        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDisable))
            ->withResources(ResourceFactory::make($resourceSharedViewed))
            ->persist();
        SecretAccessFactory::make()
            ->withUsers(UserFactory::make($userToDisable))
            ->withResources(ResourceFactory::make($resourcesSharedViaGroupViewed))
            ->persist();

        $userFullName = $userToDisable->profile->full_name;
        $this->logInAs($admin1);
        $data = [
            'id' => $userToDisable->id,
            'disabled' => FrozenTime::now(),
        ];
        $this->postJson('/users/' . $userToDisable->id . '.json', $data);
        $this->assertSuccess();
        $userDisabled = UserFactory::get($userToDisable->id);
        $this->assertTrue($userDisabled->isDisabled());
        $resourceSharedViewed = ResourceFactory::get($resourceSharedViewed->id);
        $resourceSharedNotViewed = ResourceFactory::get($resourceSharedNotViewed->id);
        $resourcesSharedViaGroupViewed = ResourceFactory::get($resourcesSharedViaGroupViewed->id);
        $resourcesSharedViaGroupNotViewed = ResourceFactory::get($resourcesSharedViaGroupNotViewed->id);
        $this->assertTrue($resourceSharedViewed->isExpired());
        $this->assertFalse($resourceSharedNotViewed->isExpired());
        $this->assertTrue($resourcesSharedViaGroupViewed->isExpired());
        $this->assertFalse($resourcesSharedViaGroupNotViewed->isExpired());

        $this->assertEmailQueueCount(4);
        $userFullName = h(Purifier::clean($userFullName));
        $this->assertEmailInBatchContains(
            "The user {$userFullName} has been suspended.",
            $admin1->username,
            '',
            false
        );
        $this->assertEmailInBatchContains(
            "The user {$userFullName} has been suspended.",
            $admin2->username,
            '',
            false
        );
        $emailContent = [
            'Some of your passwords expired',
            'Access for users to your shared passwords have been revoked.',
            'These passwords are now marked as expired.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($emailContent, $ownerWithResourceShared1->username);
        $this->assertEmailInBatchContains($emailContent, $ownerWithGroupShared1->username);
    }
}
