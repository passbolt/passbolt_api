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
use App\Notification\Email\EmailSender;
use App\Notification\Email\EmailSenderException;
use App\Utility\Purifier;
use Cake\TestSuite\TestCase;
use EmailQueue\Model\Table\EmailQueueTable;

class EmailSenderTest extends TestCase
{
    public const APP_FULL_BASE_URL = 'http://full_base_url.com';

    /**
     * @var EmailSender
     */
    private $sut;

    /**
     * @var MockObject|EmailQueueTable
     */
    private $emailQueueMock;

    /**
     * @var bool
     */
    private $purifySubject;

    public function setUp()
    {
        $this->emailQueueMock = $this->createMock(EmailQueueTable::class);
        $this->purifySubject = false;

        $this->sut = new EmailSender(
            $this->emailQueueMock,
            self::APP_FULL_BASE_URL,
            $this->purifySubject
        );

        parent::setUp();
    }

    public function getSubject($subject, $purifierEnabled)
    {
        return $purifierEnabled ? Purifier::clean($subject) : $subject;
    }

    public function testThatSendThrowExceptionIfEnqueueFailed()
    {
        $email = new Email('test', 'test', [], '');
        $options = [
            'template' => $email->getTemplate(),
            'subject' => $this->getSubject($email->getSubject(), $this->purifySubject),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated'],
        ];

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->willReturn(false);

        $expectedException = false;
        try {
            $this->sut->sendEmail($email);
        } catch (EmailSenderException $e) {
            $this->assertInstanceOf(EmailSenderException::class, $e);
            $this->assertEquals($options, $e->getOptions());
            $expectedException = true;
        }

        $this->assertTrue($expectedException, 'sendEmail should have raised exception ' . EmailSenderException::class);
    }

    public function testThatSendEnqueueEmailWithOptionsWhenPurifySubjectIsDisabled()
    {
        $email = new Email('test', 'test', [], '');

        $options = [
            'template' => $email->getTemplate(),
            'subject' => $this->getSubject($email->getSubject(), $this->purifySubject),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated'],
        ];

        $data = $email->getData();
        $data['body']['fullBaseUrl'] = self::APP_FULL_BASE_URL;

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getRecipient(), $data, $options)
            ->willReturn(true);

        $this->sut->sendEmail($email);
    }

    public function testThatSendEnqueueEmailWithOptionsWhenPurifySubjectIsEnabled()
    {
        $sut = new EmailSender(
            $this->emailQueueMock,
            self::APP_FULL_BASE_URL,
            true
        );

        $email = new Email('test', 'test', [], '');

        $options = [
            'template' => $email->getTemplate(),
            'subject' => $this->getSubject($email->getSubject(), true),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated'],
        ];

        $data = $email->getData();
        $data['body']['fullBaseUrl'] = self::APP_FULL_BASE_URL;

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getRecipient(), $data, $options)
            ->willReturn(true);

        $sut->sendEmail($email);
    }

    public function testThatSendEnqueueEmailWithSubjectExceedingMaximumLength()
    {
        $sut = new EmailSender(
            $this->emailQueueMock,
            self::APP_FULL_BASE_URL,
            true
        );

        $subject = 'Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long su';
        $email = new Email('test', $subject, [], '');

        $options = [
            'template' => $email->getTemplate(),
            'subject' => $this->getSubject($email->getSubject(), true),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated'],
        ];

        $data = $email->getData();
        $data['body']['fullBaseUrl'] = self::APP_FULL_BASE_URL;

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getRecipient(), $data, $options)
            ->willReturn(true);

        $sut->sendEmail($email);
    }

    public function testThatSendEmailAddFullBaseUrlToBodyAndMergeData()
    {
        $expectedData = ['body' => ['some_data' => 'test']];
        $email = new Email('test', 'test', $expectedData, '');

        $options = [
            'template' => $email->getTemplate(),
            'subject' => $this->getSubject($email->getSubject(), $this->purifySubject),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated'],
        ];

        $expectedData = ['body' => ['some_data' => 'test', 'fullBaseUrl' => self::APP_FULL_BASE_URL]];

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getRecipient(), $expectedData, $options)
            ->willReturn(true);

        $this->sut->sendEmail($email);
    }
}
