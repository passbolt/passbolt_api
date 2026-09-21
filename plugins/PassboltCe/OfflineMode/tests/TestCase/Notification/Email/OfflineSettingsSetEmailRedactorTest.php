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
namespace Passbolt\OfflineMode\Test\TestCase\Notification\Email;

use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\OfflineMode\Model\Dto\OfflineSettingsDto;
use Passbolt\OfflineMode\Notification\Email\OfflineSettingsSetEmailRedactor;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsSetService;

/**
 * @covers \Passbolt\OfflineMode\Notification\Email\OfflineSettingsSetEmailRedactor
 */
class OfflineSettingsSetEmailRedactorTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    private OfflineSettingsSetEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new OfflineSettingsSetEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testOfflineSettingsSetEmailRedactor_Success(): void
    {
        /** @var \App\Model\Entity\User $actor */
        $actor = UserFactory::make()->admin()->active()->withAvatar()->persist();
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->user()->active()->persist(); // non-admin — must not receive

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($actor))->getEmails();

        $this->assertCount(2, $emails);
        $recipientIds = array_map(
            fn($e) => $e->getData()['body']['recipient']->id,
            $emails,
        );
        $this->assertContains($actor->id, $recipientIds);
        $this->assertContains($otherAdmin->id, $recipientIds);
        // Assert email body content
        foreach ($emails as $email) {
            $body = $email->getData()['body'];
            if ($body['recipient']->id === $actor->id) {
                $this->assertSame('You edited the Offline Mode settings', $email->getSubject());
            } else {
                $this->assertStringContainsString('edited the Offline Mode settings', $email->getSubject());
                $this->assertStringContainsString($actor->profile->first_name, $email->getSubject());
            }
            $this->assertSame(300, $body['settings']['max_session_duration']);
            $this->assertSame(7, $body['settings']['data_retention_period']);
            $this->assertSame(1000, $body['settings']['max_items']);
            $this->assertSame('127.0.0.1', $body['ip']);
            $this->assertSame('test-user-agent', $body['user_agent']);
        }
    }

    public function testOfflineSettingsSetEmailRedactor_Empty_WhenNoActiveAdmins(): void
    {
        // Only a non-admin user — findAdmins() returns empty.
        UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $disabledAdmin */
        $disabledAdmin = UserFactory::make()->admin()->active()->disabled()->persist();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($disabledAdmin))->getEmails();

        $this->assertEmpty($emails);
    }

    public function testOfflineSettingsSetEmailRedactor_Error_WhenDtoIsMissing(): void
    {
        $event = new Event(OfflineSettingsSetService::EVENT_SETTINGS_UPDATED);
        $event->setData(['uac' => $this->mockExtendedAdminAccessControl()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/dto/');

        $this->sut->onSubscribedEvent($event);
    }

    public function testOfflineSettingsSetEmailRedactor_Throws_WhenUacMissing(): void
    {
        $event = new Event(OfflineSettingsSetService::EVENT_SETTINGS_UPDATED);
        $dto = OfflineSettingsDto::createFromArray([
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);
        $event->setData(['dto' => $dto]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/uac/');

        $this->sut->onSubscribedEvent($event);
    }

    /** Helper methods */

    private function buildEvent(User $actor): Event
    {
        $uac = new ExtendedUserAccessControl(
            $actor->role->name,
            $actor->id,
            $actor->username,
            '127.0.0.1',
            'test-user-agent',
        );
        $dto = OfflineSettingsDto::createFromArray([
            'max_session_duration' => 300,
            'data_retention_period' => 7,
            'max_items' => 1000,
        ]);
        $event = new Event(OfflineSettingsSetService::EVENT_SETTINGS_UPDATED);

        $event->setData(['dto' => $dto, 'uac' => $uac]);

        return $event;
    }
}
