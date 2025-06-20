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
 * @since         5.2.0
 */
namespace Passbolt\PasswordExpiry\Test\TestCase\Command;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\I18n\DateTime;
use Passbolt\PasswordExpiry\PasswordExpiryPlugin;
use Passbolt\PasswordExpiry\Test\Factory\PasswordExpirySettingFactory;

class PasswordExpiryNotifyAboutExpiredResourcesCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailQueueTrait;
    use PassboltCommandTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(PasswordExpiryPlugin::class);
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testPasswordExpiryNotifyAboutExpiredResourcesCommand_Help()
    {
        $this->exec('passbolt notify_about_expired_resources -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Notify resource owners about their expired passwords.');
    }

    /**
     * Basic test with no notification settings
     */
    public function testPasswordExpiryNotifyAboutExpiredResourcesCommand_No_Settings()
    {
        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('Password expiry is not activated.');
    }

    /**
     * Basic test
     */
    public function testPasswordExpiryNotifyAboutExpiredResourcesCommand_Notify_Users_Of_Password_Expiring_Today()
    {
        PasswordExpirySettingFactory::make()->persist();
        [
            $ownerOfExpiredResource1,
            $ownerOfExpiredResource2,
            $editorOfExpiredResource2,
            $ownerOfResourcesNotExpiringToday,
        ] = UserFactory::make(7)->user()->withAvatar()->persist();
        $resourceExpiredTodayShared = ResourceFactory::make()->expired(DateTime::today())->persist();
        $resourceExpiredTomorrow = ResourceFactory::make()
            ->withPermissionsFor([$ownerOfResourcesNotExpiringToday])
            ->expired(DateTime::tomorrow())
            ->persist();
        $resourceExpiredYesterday = ResourceFactory::make()
            ->withPermissionsFor([$ownerOfResourcesNotExpiringToday])
            ->expired(DateTime::yesterday())
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiredTodayShared)
            ->withAroUser([$ownerOfExpiredResource1])
            ->typeOwner()
            ->persist();
        PermissionFactory::make()
            ->acoResource($resourceExpiredTodayShared)
            ->withAroUser([$editorOfExpiredResource2])
            ->typeUpdate()
            ->persist();
        PermissionFactory::make()
            ->acoResource($resourceExpiredTodayShared)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$ownerOfExpiredResource2]))
            ->typeOwner()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiredTomorrow)
            ->withAroUser([$ownerOfResourcesNotExpiringToday])
            ->typeOwner()
            ->persist();
        PermissionFactory::make()
            ->acoResource($resourceExpiredYesterday)
            ->withAroUser([$ownerOfResourcesNotExpiringToday])
            ->typeOwner()
            ->persist();

        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('2 resource owners were notified of their passwords expiring today.');

        $this->assertEmailQueueCount(2);
        $expectedContent = [
            'You have passwords requiring your attention',
            'You have been requested to change them',
            'Some of your passwords are expiring today.',
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($expectedContent, $ownerOfExpiredResource1->username);
        $this->assertEmailInBatchContains($expectedContent, $ownerOfExpiredResource2->username);
    }

    public function testPasswordExpiryNotifyAboutExpiredResourcesCommand_Do_Not_Notify_Owners_Of_Passwords_Expired_In_The_Past()
    {
        PasswordExpirySettingFactory::make()->persist();
        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make()
            ->expired(DateTime::yesterday())
            ->withPermissionsFor([$user])
            ->persist();

        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('0 resource owners were notified of their passwords expiring today.');
        $this->assertEmailQueueCount(0);
    }

    public function testPasswordExpiryNotifyAboutExpiredResourcesCommand_Do_Not_Notify_Owners_When_No_Passwords_Are_Expired()
    {
        PasswordExpirySettingFactory::make()->persist();
        $user = UserFactory::make()->user()->persist();
        ResourceFactory::make()
            ->expired(DateTime::tomorrow())
            ->withPermissionsFor([$user])
            ->persist();

        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('0 resource owners were notified of their passwords expiring today.');
        $this->assertEmailQueueCount(0);
    }
}
