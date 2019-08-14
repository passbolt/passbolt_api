<?php

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\Email;
use App\Notification\Email\EmailSender;
use App\Notification\Email\EmailSenderException;
use App\Utility\Purifier;
use Cake\Event\EventManagerInterface;
use Cake\TestSuite\TestCase;
use EmailQueue\Model\Table\EmailQueueTable;
use PHPUnit\Framework\MockObject\MockObject;
use Throwable;

class EmailSenderTest extends TestCase
{
    const APP_FULL_BASE_URL = 'http://full_base_url.com';

    /**
     * @var EmailSender
     */
    private $sut;

    /**
     * @var MockObject|EventManagerInterface
     */
    private $eventManagerMock;

    /**
     * @var MockObject|EmailQueueTable
     */
    private $emailQueueMock;

    public function setUp()
    {
        $this->eventManagerMock = $this->createMock(EventManagerInterface::class);
        $this->emailQueueMock = $this->createMock(EmailQueueTable::class);

        $this->sut = new EmailSender($this->eventManagerMock, $this->emailQueueMock, self::APP_FULL_BASE_URL);
        parent::setUp();
    }

    public function testThatSendThrowExceptionIfEnqueueFailed()
    {
        $email = new Email('test', 'test', [], '');
        $options = [
            'template' => $email->getTemplate(),
            'subject' => Purifier::clean($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
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

        $this->assertTrue($expectedException, "sendEmail should have raised exception " . EmailSenderException::class);
    }

    public function testThatSendEnqueueEmailWithOptions()
    {
        $email = new Email('test', 'test', [], '');

        $options =  [
            'template' => $email->getTemplate(),
            'subject' => Purifier::clean($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
        ];

        $data = $email->getData();
        $data['body']['fullBaseUrl'] = self::APP_FULL_BASE_URL;

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getTo(), $data, $options)
            ->willReturn(true);

        $this->sut->sendEmail($email);
    }

    public function testThatSendEmailAddFullBaseUrlToBodyAndMergeData()
    {
        $expectedData = ['body' => ['some_data' => 'test']];
        $email = new Email('test', 'test', $expectedData, '');

        $options =  [
            'template' => $email->getTemplate(),
            'subject' => Purifier::clean($email->getSubject()),
            'format' => 'html',
            'config' => 'default',
            'headers' => ['Auto-Submitted' => 'auto-generated']
        ];

        $expectedData = ['body' => ['some_data' => 'test', 'fullBaseUrl' => self::APP_FULL_BASE_URL]];

        $this->emailQueueMock->expects($this->once())
            ->method('enqueue')
            ->with($email->getTo(), $expectedData, $options)
            ->willReturn(true);

        $this->sut->sendEmail($email);
    }
}
