<?php

namespace App\Notification\Email;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;

interface SubscribedEmailRedactorInterface extends EventListenerInterface
{
    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     * @return array
     */
    public function getSubscribedEvents();

    /**
     * Method called by the EventDispatcher to register the redactor so it can be called when its subscribed events
     * are triggered.
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event);

    /**
     * Method called when its subscribed events are dispatched by the EmailSubscriptionDispatcher.
     * Redactor MUST implement this method to manipulate the email collection to send.
     * Return an EmailCollection.
     * @param Event $event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event);
}
