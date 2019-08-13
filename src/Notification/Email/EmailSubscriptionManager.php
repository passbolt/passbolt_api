<?php

namespace App\Notification\Email;

use Cake\Event\Event;

class EmailSubscriptionManager
{
    /**
     * @var array
     */
    private $subscriptions = [];

    /**
     * @param SubscribedEmailRedactorInterface $subscribedEmailRedactor
     * @return $this
     */
    public function addNewSubscription(SubscribedEmailRedactorInterface $subscribedEmailRedactor)
    {
        foreach ($subscribedEmailRedactor->getSubscribedEvents() as $subscribedEvent) {
            $this->subscriptions[$subscribedEvent][] = $subscribedEmailRedactor;
        }

        return $this;
    }

    /**
     * @param Event $event
     * @return SubscribedEmailRedactorInterface[]
     */
    public function getSubscriptionsForEvent(Event $event)
    {
        return $this->subscriptions[$event->getName()] ?? [];
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array_keys($this->subscriptions);
    }
}
