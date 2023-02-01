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
 * @since         3.11.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Event;

use App\Mailer\Transport\SmtpTransport;
use Cake\Event\Event;
use Cake\Mailer\Message;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Event\SmtpTransportBeforeSendEventListener;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Event\SmtpTransportBeforeSendEventListener
 */
class SmtpTransportBeforeSendEventListenerTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpTransportBeforeSendEventListener
     */
    protected $listener;

    public function setUp(): void
    {
        parent::setUp();
        $this->listener = new SmtpTransportBeforeSendEventListener();
    }

    public function tearDown(): void
    {
        unset($this->listener);
        parent::tearDown();
    }

    public function testSmtpTransportSendEventListener_implementedEvents(): void
    {
        $expectedListeners = [
            SmtpTransport::SMTP_TRANSPORT_INITIALIZE_EVENT => 'initializeTransport',
            SmtpTransport::SMTP_TRANSPORT_BEFORE_SEND_EVENT => 'setEmailFromIfDefinedInDB',
        ];
        $this->assertSame($expectedListeners, $this->listener->implementedEvents());
    }

    public function testSmtpTransportSendEventListener_SetFromWithSettingsInDB(): void
    {
        $senderEmail = 'onDB@test.test';
        $senderName = 'onDB';
        $configInDb = $this->getSmtpSettingsData();
        $configInDb['sender_email'] = $senderEmail;
        $configInDb['sender_name'] = $senderName;
        $senderOnDBConfig = [$senderEmail => $senderName];
        $this->encryptAndPersistSmtpSettings($configInDb);

        $to = 'john@passbolt.com';
        $senderOnFileConfig = ['onFile@test.test' => 'onFile'];
        $message = new Message();
        $message->setTo($to);
        $message->setFrom($senderOnFileConfig);
        $message->setSender($senderOnFileConfig);
        $message->setReturnPath($senderOnFileConfig);
        $event = new Event('foo', $message);

        $this->listener->initializeTransport($event);
        $this->listener->setEmailFromIfDefinedInDB($event);

        $this->assertIsArray($this->listener->getConfigInDB());
        $this->assertTrue($this->listener->isSourceDB());
        foreach ($configInDb as $key => $setting) {
            $this->assertSame($setting, $this->listener->getConfigInDB()[$key]);
        }
        $this->assertSame([$to => $to], $message->getTo());
        $this->assertSame($senderOnDBConfig, $message->getFrom());
        $this->assertSame($senderOnDBConfig, $message->getSender());
        $this->assertSame($senderOnDBConfig, $message->getReturnPath());
    }

    public function testSmtpTransportSendEventListener_SetFromWithoutSettingsInDB(): void
    {
        $to = 'john@passbolt.com';
        $from = ['onFile@test.test' => 'onFile'];
        $message = new Message();
        $message->setTo($to);
        $message->setFrom($from);
        $message->setSender($from);
        $message->setReturnPath($from);
        $event = new Event('foo', $message);

        $this->listener->initializeTransport($event);
        $this->listener->setEmailFromIfDefinedInDB($event);

        $this->assertNull($this->listener->getConfigInDB());
        $this->assertFalse($this->listener->isSourceDB());
        $this->assertSame([$to => $to], $message->getTo());
        $this->assertSame($from, $message->getFrom());
        $this->assertSame($from, $message->getSender());
        $this->assertSame($from, $message->getReturnPath());
    }
}
