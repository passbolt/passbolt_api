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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\Email;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\NotificationSettings\CoreNotificationSettingsDefinition;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class EmailSubscriptionDispatcherTest extends TestCase
{
    use EmailQueueTrait;
    use EmailNotificationSettingsTestTrait;
    use SubscribedEmailRedactorMockTrait;
    use TruncateDirtyTables;

    private EmailSubscriptionDispatcher $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = new EmailSubscriptionDispatcher();
    }

    public function testEmailSubscriptionDispatcher()
    {
        $event = 'foo';
        $settingActivated = 'send.comment.add';
        $this->setEmailNotificationSetting($settingActivated, true);
        $settingDeactivated = 'show.comment';
        $userEnabled = UserFactory::make()->getEntity();
        $userEnabled->disabled = null;
        $userDisabled = UserFactory::make()->disabled()->getEntity();
        $redactorAlwaysActive = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorAlwaysActive', [], 'test')
        );
        $redactorAlwaysRecipientDisabled = $this->createSubscribedRedactor(
            [$event],
            new Email($userDisabled, 'redactorAlwaysRecipientDisabled', [], 'test')
        );
        $redactorOnSettingActivated = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorOnSettingActivated', [], 'test'),
            $settingActivated
        );
        $redactorOnSettingDeactivated = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorOnSettingDeactivated', [], 'test'),
            $settingDeactivated
        );

        EventManager::instance()
            ->on($redactorAlwaysActive)
            ->on($redactorAlwaysRecipientDisabled)
            ->on($redactorOnSettingActivated)
            ->on($redactorOnSettingDeactivated)
            ->on(new CoreNotificationSettingsDefinition());

        $this->sut->collectSubscribedEmailRedactors();
        $this->sut->dispatch(new Event($event));

        $this->assertEmailQueueCount(2);
        $this->assertEmailIsInQueue([
            'subject' => 'redactorAlwaysActive',
        ]);
        $this->assertEmailIsInQueue([
            'subject' => 'redactorOnSettingActivated',
        ]);
    }

    public function testEmailSubscriptionDispatcher_WithAppFullBaseUrlToBool(): void
    {
        Configure::write('App.fullBaseUrl', false);
        $event = 'foo';
        $settingActivated = 'send.comment.add';
        $this->setEmailNotificationSetting($settingActivated, true);
        $settingDeactivated = 'show.comment';
        $userEnabled = UserFactory::make()->getEntity();
        $userEnabled->disabled = null;
        $userDisabled = UserFactory::make()->disabled()->getEntity();
        $redactorAlwaysActive = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorAlwaysActive', [], 'test')
        );
        $redactorAlwaysRecipientDisabled = $this->createSubscribedRedactor(
            [$event],
            new Email($userDisabled, 'redactorAlwaysRecipientDisabled', [], 'test')
        );
        $redactorOnSettingActivated = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorOnSettingActivated', [], 'test'),
            $settingActivated
        );
        $redactorOnSettingDeactivated = $this->createSubscribedRedactor(
            [$event],
            new Email($userEnabled, 'redactorOnSettingDeactivated', [], 'test'),
            $settingDeactivated
        );

        EventManager::instance()
            ->on($redactorAlwaysActive)
            ->on($redactorAlwaysRecipientDisabled)
            ->on($redactorOnSettingActivated)
            ->on($redactorOnSettingDeactivated)
            ->on(new CoreNotificationSettingsDefinition());

        $this->sut->collectSubscribedEmailRedactors();
        $this->sut->dispatch(new Event($event));

        $this->assertEmailQueueCount(2);
        $this->assertEmailIsInQueue([
            'subject' => 'redactorAlwaysActive',
        ]);
        $this->assertEmailIsInQueue([
            'subject' => 'redactorOnSettingActivated',
        ]);
    }
}
