<?php

namespace App\Notification\Email;

use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManagerInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

class EmailSubscriptionDispatcher implements EventListenerInterface
{
    /**
     * @var EventManagerInterface
     */
    private $eventManager;
    /**
     * @var EmailSubscriptionManager
     */
    private $emailSubscriptionManager;
    /**
     * @var EmailSender
     */
    private $emailSender;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param EventManagerInterface $eventManager
     * @param EmailSubscriptionManager $emailSubscriptionManager
     * @param EmailSender $emailSender
     * @param LoggerInterface $logger
     */
    public function __construct(
        EventManagerInterface $eventManager,
        EmailSubscriptionManager $emailSubscriptionManager,
        EmailSender $emailSender,
        LoggerInterface $logger = null
    )
    {
        $this->eventManager = $eventManager;
        $this->emailSubscriptionManager = $emailSubscriptionManager;
        $this->emailSender = $emailSender;
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @return $this
     */
    public function collect()
    {
        $this->eventManager->dispatch(CollectSubscribedEmailRedactorEvent::create($this->emailSubscriptionManager));
        $this->eventManager->on($this);

        return $this;
    }

    /**
     * @return array
     */
    public function implementedEvents()
    {
        return array_fill_keys($this->emailSubscriptionManager->getSubscribedEvents(), $this);
    }

    /**
     * @param Event $event
     * @return void
     */
    public function __invoke(Event $event)
    {
        $this->dispatch($event);
    }

    /**
     * @param Event $event
     * @return void
     */
    public function dispatch(Event $event)
    {
        foreach ($this->emailSubscriptionManager->getSubscriptionsForEvent($event) as $emailRedactor) {
            $emailCollection = $emailRedactor->onSubscribedEvent($event);

            if (!$emailCollection instanceof EmailCollection) {
                throw new InvalidArgumentException('$emailCollection must be an instance ' . EmailCollection::class);
            }

            if ($emailCollection->getEmails()) {
                foreach ($emailCollection->getEmails() as $email) {
                    try {
                        $this->emailSender->sendEmail($email);
                    } catch (Throwable $t) {
                        $this->logger->alert(
                            sprintf('EmailSubscriptionDispatcher failed to send email on event `%s`', $event->getName()),
                            [
                                'emailCollection' => $emailCollection,
                                'exception' => $t
                            ]
                        );
                    }
                }
            }
        }
    }
}
