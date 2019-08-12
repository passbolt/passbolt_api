<?php

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\Email;
use App\Notification\Email\EmailSubscriptionManager;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;

class EmailSubscriptionManagerTest extends TestCase
{
    use SubscribedEmailRedactorMockTrait;

    /**
     * @var EmailSubscriptionManager
     */
    private $sut;

    public function setUp()
    {
        $this->sut = new EmailSubscriptionManager();
        parent::setUp();
    }

    public function testThatNewSubscriptionRegisterAllSubscribedEventsForRedactor()
    {
        $expectedRedactors = [
            $this->createSubscribedRedactor(
                ['event_name'],
                new Email('test', 'test', [], 'test')
            ),
            $this->createSubscribedRedactor(
                ['event_name'],
                new Email('test', 'test', [], 'test')
            )
        ];
        $this->sut->addNewSubscription($expectedRedactors[0]);
        $this->sut->addNewSubscription($expectedRedactors[1]);

        $this->assertEquals($expectedRedactors, $this->sut->getSubscriptionsForEvent(new Event('event_name')));
    }

    public function testThatGetSubscribedEventsReturnAllEventsSubscribed()
    {
        $expectedRedactors = [
            $this->createSubscribedRedactor(
                ['event_name'],
                new Email('test', 'test', [], 'test')
            ),
            $this->createSubscribedRedactor(
                ['event_name1'],
                new Email('test', 'test', [], 'test')
            )
        ];
        $this->sut->addNewSubscription($expectedRedactors[0]);
        $this->sut->addNewSubscription($expectedRedactors[1]);

        $this->assertEquals(['event_name', 'event_name1'], $this->sut->getSubscribedEvents());
    }
}
