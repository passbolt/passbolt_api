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
namespace Passbolt\PasswordExpiryPolicies\Test\TestCase\Command;

use App\Test\Factory\GroupFactory;
use App\Test\Factory\PermissionFactory;
use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Console\ConsoleOptionParser;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\I18n\FrozenTime;
use Passbolt\PasswordExpiryPolicies\Command\PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService;
use Passbolt\PasswordExpiryPolicies\Test\Factory\PasswordExpiryPoliciesSettingFactory;

class PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use EmailQueueTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->markTestSkipped();
        parent::setUp();
        $this->useCommandRunner();
    }

    /**
     * Basic help test
     */
    public function testPasswordExpiryPoliciesNotifyAboutExpiredResourcesCommandHelp()
    {
        $mock = $this
            ->getMockBuilder(PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cmd = new PasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand($mock);
        $optionParser = new ConsoleOptionParser();
        $cmd->buildOptionParser($optionParser);
        $this->assertSame(
            'Notify resource owners about their expired passwords.',
            $optionParser->getDescription()
        );
    }

    /**
     * Basic test with no notification settings
     */
    public function testPasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand_No_Settings()
    {
        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('Password expiry is not activated.');
    }

    /**
     * Basic test
     */
    public function testPasswordExpiryPoliciesNotifyAboutExpiredResourcesCommand_Notify_Users_Of_Password_Expiring_In_The_Future_Or_Expired()
    {
        $nDays = rand(2, 100);
        PasswordExpiryPoliciesSettingFactory::make()
            ->setField('value.expiry_notification', $nDays) // Notify about passwords expiring in N days
            ->persist();

        [$user1, $user2, $user3, $user4] = UserFactory::make(6)->user()->persist();

        [$resourceExpiredYesterdaySharedWithUser1And2] = ResourceFactory::make(2)
            ->expired(FrozenTime::yesterday())
            ->persist();
        [$resourceExpiringInNDaysSharedWithUser3And4] = ResourceFactory::make(2)
            ->expired(FrozenTime::now()->addDays($nDays))
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiredYesterdaySharedWithUser1And2)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$user1, $user2]))
            ->typeOwner()
            ->persist();

        PermissionFactory::make()
            ->acoResource($resourceExpiringInNDaysSharedWithUser3And4)
            ->withAroGroup(GroupFactory::make()->withGroupsUsersFor([$user3, $user4]))
            ->typeOwner()
            ->persist();

        $this->exec('passbolt notify_about_expired_resources');
        $this->assertExitSuccess();
        $this->assertOutputContains('2 resource owners were notified.');

        $this->assertEmailQueueCount(2);
        $expectedContent = [
            'You have passwords requiring your attention',
            'You have been requested to change them',
            "Some of your passwords are expired or expiring in {$nDays} days. ",
            'Please rotate them to ensure continued security.',
        ];
        $this->assertEmailInBatchContains($expectedContent, $user3->username);
        $this->assertEmailInBatchContains($expectedContent, $user4->username);
    }
}
