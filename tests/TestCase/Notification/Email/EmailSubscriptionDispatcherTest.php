<?php
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

use App\Notification\Email\CollectSubscribedEmailRedactorEvent;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\EmailSender;
use App\Notification\Email\EmailSubscriptionDispatcher;
use App\Notification\Email\EmailSubscriptionManager;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\TestSuite\TestCase;
use Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;
use stdClass;

class EmailSubscriptionDispatcherTest extends TestCase
{
    use SubscribedEmailRedactorMockTrait;

    /**
     * @var MockObject|EmailSender
     */
    private $emailSenderMock;

    /**
     * @var MockObject|EventManager
     */
    private $eventManagerMock;

    /**
     * @var MockObject|EmailSubscriptionManager
     */
    private $emailSubscriptionManagerMock;

    /**
     * @var EmailSubscriptionDispatcher
     */
    private $sut;

    /**
     * @var MockObject|LoggerInterface
     */
    private $loggerMock;

    public function setUp()
    {
        $this->eventManagerMock = $this->createMock(EventManager::class);
        $this->emailSubscriptionManagerMock = $this->createMock(EmailSubscriptionManager::class);
        $this->emailSenderMock = $this->createMock(EmailSender::class);
        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->sut = new EmailSubscriptionDispatcher(
            $this->eventManagerMock,
            $this->emailSubscriptionManagerMock,
            $this->emailSenderMock,
            $this->loggerMock
        );

        parent::setUp();
    }

    public function testThatEmailSubscriptionDispatcherCanBeInvokedAsEventListener()
    {
        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscribedEvents')
            ->willReturn([]);

        $this->assertTrue(is_array($this->sut->implementedEvents()));
        $this->assertTrue(is_callable($this->sut));

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->willReturn([]);

        call_user_func($this->sut, new Event('event'));
    }

    /**
     * @dataProvider provideSubscribedRedactors
     * @param array $subscribedRedactors
     * @param array $subscribedRedactorEmails
     */
    public function testThatDispatchCreateAndSendEmailForEachRedactorSubscribedOnEvent(
        array $subscribedRedactors,
        array $subscribedRedactorEmails
    ) {
        $eventName = 'test_event';
        $event = new Event($eventName);

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->with($event)
            ->willReturn($subscribedRedactors);

        $this->emailSenderMock->expects($this->exactly(count($subscribedRedactors)))
            ->method('sendEmail')
            ->withConsecutive(...$subscribedRedactorEmails);

        $this->sut->dispatch($event);
    }

    public function testThatCollectDispatchCollectEvent()
    {
        $this->eventManagerMock->expects($this->once())
            ->method('dispatch')
            ->with(CollectSubscribedEmailRedactorEvent::create($this->emailSubscriptionManagerMock));

        $this->eventManagerMock->expects($this->once())
            ->method('on')
            ->with($this->sut);

        $this->assertEquals($this->sut, $this->sut->collectSubscribedEmailRedactors());
    }

    public function testThatDispatchDoesNotSendEmailIfCollectionIsEmpty()
    {
        $eventName = 'test_event';
        $event = new Event($eventName);
        $subscribedRedactorMock = $this->createMock(SubscribedEmailRedactorInterface::class);
        $subscribedRedactors = [$subscribedRedactorMock];

        $subscribedRedactorMock->expects($this->once())
            ->method('onSubscribedEvent')
            ->willReturn(new EmailCollection());

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->with($event)
            ->willReturn($subscribedRedactors);

        $this->emailSenderMock->expects($this->never())
            ->method('sendEmail');

        $this->sut->dispatch($event);
    }

    public function testThatDispatchSendEmailForEachEmailInCollection()
    {
        $eventName = 'test_event';
        $event = new Event($eventName);
        $subscribedRedactorMock = $this->createMock(SubscribedEmailRedactorInterface::class);
        $subscribedRedactors = [$subscribedRedactorMock];
        $emails = [
            new Email('test', 'test', ['test'], 'test'),
            new Email('test', 'test', ['test'], 'test'),
        ];

        $subscribedRedactorMock->expects($this->once())
            ->method('onSubscribedEvent')
            ->willReturn(new EmailCollection($emails));

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->with($event)
            ->willReturn($subscribedRedactors);

        $this->emailSenderMock->expects($this->exactly(2))
            ->method('sendEmail')
            ->withConsecutive(...$emails);

        $this->sut->dispatch($event);
    }

    public function testThatDispatchThrowInvalidArgumentExceptionIfOnSubscribedDoesNotReturnAnEmailCollection()
    {
        $eventName = 'test_event';
        $event = new Event($eventName);
        $subscribedRedactorMock = $this->createMock(SubscribedEmailRedactorInterface::class);
        $subscribedRedactors = [$subscribedRedactorMock];

        $subscribedRedactorMock->expects($this->once())
            ->method('onSubscribedEvent')
            ->willReturn(new stdClass());

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->with($event)
            ->willReturn($subscribedRedactors);

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->dispatch($event);
    }

    public function testThatDispatchCatchEmailQueueExceptionAndLogError()
    {
        $eventName = 'test_event';
        $event = new Event($eventName);
        $subscribedRedactorMock = $this->createMock(SubscribedEmailRedactorInterface::class);
        $subscribedRedactors = [$subscribedRedactorMock];
        $emails = [
            new Email('test', 'test', ['test'], 'test'),
            new Email('test', 'test', ['test'], 'test'),
        ];
        $exception = new Exception();
        $emailCollection = new EmailCollection($emails);

        $subscribedRedactorMock->expects($this->once())
            ->method('onSubscribedEvent')
            ->willReturn($emailCollection);

        $this->emailSubscriptionManagerMock->expects($this->once())
            ->method('getSubscriptionsForEvent')
            ->with($event)
            ->willReturn($subscribedRedactors);

        $this->emailSenderMock->expects($this->exactly(2))
            ->method('sendEmail')
            ->willThrowException($exception);

        $this->loggerMock->expects($this->exactly(2))
            ->method('alert')
            ->with(
                sprintf('EmailSubscriptionDispatcher failed to send email on event `%s`', $eventName),
                ['emailCollection' => $emailCollection, 'exception' => $exception]
            );

        $this->sut->dispatch($event);
    }

    /**
     * @return array
     */
    public function provideSubscribedRedactors()
    {
        $emails = [
            new Email('test@test.test', 'test_subject', [], 'test_template'),
            new Email('test2@test.test', 'test_subject2', ['some_test_data'], 'test_template2'),
        ];

        return [
            [
                [
                    $this->createSubscribedRedactor(['event_name'], $emails[0]),
                    $this->createSubscribedRedactor(['event_name'], $emails[1]),
                ],
                $emails,
            ],
        ];
    }
}
