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
use Passbolt\OfflineMode\Model\Entity\OfflineModeSetting;
use Passbolt\OfflineMode\Notification\Email\OfflineSettingsDeleteEmailRedactor;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;
use Passbolt\OfflineMode\Test\Factory\OfflineModeSettingFactory;

/**
 * @covers \Passbolt\OfflineMode\Notification\Email\OfflineSettingsDeleteEmailRedactor
 */
class OfflineSettingsDeleteEmailRedactorTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;

    private OfflineSettingsDeleteEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new OfflineSettingsDeleteEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testOfflineSettingsDeleteEmailRedactor_Success(): void
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
                $this->assertSame('You disabled Offline Mode', $email->getSubject());
            } else {
                $this->assertStringContainsString('disabled Offline Mode', $email->getSubject());
                $this->assertStringContainsString($actor->profile->first_name, $email->getSubject());
            }
            $this->assertInstanceOf(OfflineModeSetting::class, $body['entity']);
            $this->assertSame('127.0.0.1', $body['ip']);
            $this->assertSame('test-user-agent', $body['user_agent']);
        }
    }

    public function testOfflineSettingsDeleteEmailRedactor_Empty_WhenNoActiveAdmins(): void
    {
        // Only a non-admin user — findAdmins() returns empty.
        UserFactory::make()->user()->active()->persist();
        /** @var \App\Model\Entity\User $disabledAdmin */
        $disabledAdmin = UserFactory::make()->admin()->active()->disabled()->persist();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($disabledAdmin))->getEmails();

        $this->assertEmpty($emails);
    }

    public function testOfflineSettingsDeleteEmailRedactor_Error_WhenEntityIsMissing(): void
    {
        $event = new Event(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED);
        $event->setData(['uac' => $this->mockExtendedAdminAccessControl()]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/entity/');

        $this->sut->onSubscribedEvent($event);
    }

    public function testOfflineSettingsDeleteEmailRedactor_Error_WhenUacIsMissing(): void
    {
        $event = new Event(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED);
        $entity = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->getEntity();
        $event->setData(['entity' => $entity]);

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
        $entity = OfflineModeSettingFactory::make()
            ->setField('value', json_encode(['max_session_duration' => 300, 'data_retention_period' => 7]))
            ->getEntity();
        $event = new Event(OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED);

        $event->setData(['entity' => $entity, 'uac' => $uac]);

        return $event;
    }
}
