<?php

namespace App\Notification\Email;

use Cake\Event\EventListenerInterface;

abstract class AbstractSubscribedEmailRedactorPool implements EventListenerInterface
{
    /**
     * @param CollectSubscribedEmailRedactorEvent $event
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event)
    {
        foreach ($this->getSubscribedRedactors() as $redactor) {
            $redactor->subscribe($event);
        }
    }

    /**
     * @return array
     */
    final public function implementedEvents()
    {
        return [
            CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this
        ];
    }

    /**
     * @param CollectSubscribedEmailRedactorEvent $event
     * @return void
     */
    final public function __invoke(CollectSubscribedEmailRedactorEvent $event)
    {
        $this->subscribe($event);
    }

    /**
     * Return a list of subscribed redactors
     * @return SubscribedEmailRedactorInterface[]
     */
    abstract public function getSubscribedRedactors();
}
