<?php

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\CollectSubscribedEmailRedactorEvent;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\EmailSubscriptionManager;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SubscribedEmailRedactorTraitTest extends TestCase
{
    /**
     * @var SubscribedEmailRedactorInterface|callable
     */
    private $sut;

    /**
     * @var EmailSubscriptionManager|MockObject
     */
    private $subscriptionManagerMock;

    public function setUp()
    {
        $this->subscriptionManagerMock = $this->createMock(EmailSubscriptionManager::class);

        $this->sut = new class implements SubscribedEmailRedactorInterface {
            use SubscribedEmailRedactorTrait;

            public function getSubscribedEvents()
            {
                return [
                    'event_1'
                ];
            }

            public function onSubscribedEvent(Event $event)
            {
                return new EmailCollection();
            }
        };
        parent::setUp();
    }

    public function testThatIsInvokableAndCallSubscribe()
    {
        $this->subscriptionManagerMock->expects($this->once())
            ->method('addNewSubscription')
            ->with($this->sut);

        call_user_func($this->sut, CollectSubscribedEmailRedactorEvent::create($this->subscriptionManagerMock));
    }

    public function testThatSubscribeAddNewSubscriptionToManager()
    {
        $this->subscriptionManagerMock->expects($this->once())
            ->method('addNewSubscription')
            ->with($this->sut);

        $this->sut->subscribe(CollectSubscribedEmailRedactorEvent::create($this->subscriptionManagerMock));
    }

    public function testThatIsSubscribedToCollectSubscribedEmailRedactorEvent()
    {
        $this->assertEquals(
            [CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this->sut],
            $this->sut->implementedEvents()
        );
    }
}
