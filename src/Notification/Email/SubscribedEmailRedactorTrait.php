<?php

namespace App\Notification\Email;

trait SubscribedEmailRedactorTrait
{
    /**
     * @return array
     */
    public function implementedEvents()
    {
        return [
            CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this
        ];
    }

    /**
     * @param CollectSubscribedEmailRedactorEvent $event
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event)
    {
        /** @var SubscribedEmailRedactorInterface $this */
        $event->getManager()->addNewSubscription($this);
    }

    /**
     * @param CollectSubscribedEmailRedactorEvent $event
     * @return void
     */
    public function __invoke(CollectSubscribedEmailRedactorEvent $event)
    {
        $this->subscribe($event);
    }
}
