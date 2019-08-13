<?php

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;

trait SubscribedEmailRedactorMockTrait
{
    private function createSubscribedRedactor(array $subscribedEvents, Email $email)
    {
        return new class($subscribedEvents, $email) implements SubscribedEmailRedactorInterface
        {
            use SubscribedEmailRedactorTrait;

            /** @var string */
            private $subscribedEvents;

            /** @var Email */
            private $email;

            public function __construct(array $subscribedEvents, Email $email)
            {
                $this->subscribedEvents = $subscribedEvents;
                $this->email = $email;
            }

            public function onSubscribedEvent(Event $event)
            {
                return new EmailCollection([$this->email]);
            }

            public function getSubscribedEvents()
            {
                return $this->subscribedEvents;
            }
        };
    }
}
