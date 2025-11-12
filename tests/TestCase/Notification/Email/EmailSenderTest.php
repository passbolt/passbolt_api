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
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use EmailQueue\Model\Table\EmailQueueTable;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class EmailSenderTest extends AppTestCase
{
    private const APP_FULL_BASE_URL = 'http://full_base_url.com';

    public function testEmailSender_SendEmail_ThrowsEmailSenderExceptionIfEnqueueFailed()
    {
        $emailQueueMock = $this->createMock(EmailQueueTable::class);
        $emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->willReturn(false);

        $email = new Email(UserFactory::make()->getEntity(), 'Test subject', [], '');
        $sut = new EmailSender($emailQueueMock, self::APP_FULL_BASE_URL);

        $isExceptionThrown = false;
        try {
            $sut->sendEmail($email);
        } catch (EmailSenderException $e) {
            $isExceptionThrown = true;
            $this->assertInstanceOf(EmailSenderException::class, $e);

            // Assert options are correct
            $exceptionOptions = $e->getOptions();
            $this->assertEquals(
                [
                    'template' => $email->getTemplate(),
                    'subject' => $sut->purifySubject($email->getSubject()),
                    'format' => 'html',
                    'config' => 'default',
                ],
                [
                    'template' => $exceptionOptions['template'],
                    'subject' => $exceptionOptions['subject'],
                    'format' => $exceptionOptions['format'],
                    'config' => $exceptionOptions['config'],
                ]
            );
            $this->assertEquals('auto-generated', $exceptionOptions['headers']['Auto-Submitted']);
            $this->assertStringMatchesFormat('<%s@%s>', $exceptionOptions['headers']['Message-ID']);
        }

        $this->assertTrue($isExceptionThrown, 'EmailSender::sendEmail() should have raised exception' . EmailSenderException::class);
    }

    /**
     * @dataProvider purifySubjectValueProvider
     */
    public function testEmailSender_SendEmail_WithOptions(bool $purifySubject)
    {
        $email = new Email(UserFactory::make()->getEntity(), 'Test subject', [], '');

        $sut = new EmailSender(null, self::APP_FULL_BASE_URL, $purifySubject);
        $result = $sut->sendEmail($email);

        $this->assertInstanceOf(EmailSender::class, $result);
        $emailQueue = EmailQueueFactory::find()->all()->last();
        $this->assertSame($email->getRecipient(), $emailQueue->email);
        $this->assertSame($sut->purifySubject($email->getSubject()), $emailQueue->subject);
        $this->assertSame($email->getTemplate(), $emailQueue->template);
        $this->assertSame('html', $emailQueue->format);
        $this->assertSame('default', $emailQueue->config);
        // Check headers
        $this->assertSame('auto-generated', $emailQueue->headers['Auto-Submitted']);
        $this->assertStringMatchesFormat('<%s@%s>', $emailQueue->headers['Message-ID']);
        // Check template vars
        $this->assertSame(self::APP_FULL_BASE_URL, $emailQueue->template_vars['body']['fullBaseUrl']);
    }

    public static function purifySubjectValueProvider(): array
    {
        return [
            [false], // purifier disabled
            [true], // purifier enabled
        ];
    }

    public function testEmailSender_SendEmail_WithSubjectExceedingMaximumLength()
    {
        $longSubject = 'Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰 - Long subject with emoticon 😰';
        $email = new Email(UserFactory::make()->getEntity(), $longSubject, [], '');

        $sut = new EmailSender(null, self::APP_FULL_BASE_URL, true);
        $result = $sut->sendEmail($email);

        $this->assertInstanceOf(EmailSender::class, $result);
        $emailQueue = EmailQueueFactory::find()->all()->last();
        // Check subject length is not more than database field's max length
        $this->assertSame(255, mb_strlen($emailQueue->subject));
        // Check headers
        $this->assertSame('auto-generated', $emailQueue->headers['Auto-Submitted']);
        $this->assertStringMatchesFormat('<%s@%s>', $emailQueue->headers['Message-ID']);
        // Check template vars
        $this->assertSame(self::APP_FULL_BASE_URL, $emailQueue->template_vars['body']['fullBaseUrl']);
        // Check format, config, etc.
        $this->assertSame($sut->purifySubject($email->getSubject()), $emailQueue->subject);
        $this->assertSame($email->getTemplate(), $emailQueue->template);
        $this->assertSame('html', $emailQueue->format);
        $this->assertSame('default', $emailQueue->config);
        $this->assertSame($email->getRecipient(), $emailQueue->email);
    }

    public function testEmailSender_SendEmail_AddFullBaseUrlToBodyAndMergeData()
    {
        $data = ['body' => ['some_data' => 'test']];
        $email = new Email(UserFactory::make()->getEntity(), 'Test subject', $data, 'user_activated');

        $sut = new EmailSender(null, self::APP_FULL_BASE_URL, true);
        $result = $sut->sendEmail($email);

        $this->assertInstanceOf(EmailSender::class, $result);
        $emailQueue = EmailQueueFactory::find()->all()->last();
        // Check template vars body
        $this->assertEqualsCanonicalizing([
            'some_data' => 'test',
            'fullBaseUrl' => self::APP_FULL_BASE_URL,
        ], $emailQueue->template_vars['body']);
        // Check headers
        $this->assertSame('auto-generated', $emailQueue->headers['Auto-Submitted']);
        $this->assertStringMatchesFormat('<%s@%s>', $emailQueue->headers['Message-ID']);
        // Check subject, template, etc.
        $this->assertSame($email->getRecipient(), $emailQueue->email);
        $this->assertSame($sut->purifySubject($email->getSubject()), $emailQueue->subject);
        $this->assertSame('user_activated', $emailQueue->template);
        $this->assertSame('html', $emailQueue->format);
        $this->assertSame('default', $emailQueue->config);
    }
}
