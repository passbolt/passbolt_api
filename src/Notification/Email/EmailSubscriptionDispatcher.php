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
 * @since         2.12.0
 */
namespace App\Notification\Email;

use Cake\Event\Event;
use Cake\Event\EventDispatcherTrait;
use Cake\Event\EventListenerInterface;
use Cake\Event\EventManager;
use Cake\Event\EventManagerInterface;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

/**
 * Class EmailSubscriptionDispatcher
 * @package App\Notification\Email
 *
 * The EmailSubscriptionDispatcher class is the core class of the email notification system.
 * It provides the registration mechanism by dispatching an event to each subscribed SubscribedEmailRedactor
 * to collect them.
 *
 * It will listen for all the subscribed events and will get the matching SubscribedEmailRedactor and
 * delegate to them the email creation. Then it will pass those created emails to the EmailSender for sending.
 */
class EmailSubscriptionDispatcher implements EventListenerInterface
{
    use EventDispatcherTrait;

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
     * @param EventManager $eventManager Event Manager Instance
     * @param EmailSubscriptionManager $emailSubscriptionManager EmailSubscriptionManager Instance
     * @param EmailSender $emailSender EmailSender Instance
     * @param LoggerInterface $logger Logger Instance
     */
    public function __construct(
        EventManager $eventManager = null,
        EmailSubscriptionManager $emailSubscriptionManager = null,
        EmailSender $emailSender = null,
        LoggerInterface $logger = null
    ) {
        $this->setEventManager($eventManager ?? EventManager::instance());
        $this->emailSubscriptionManager = $emailSubscriptionManager ?? new EmailSubscriptionManager();
        $this->emailSender = $emailSender ?? new EmailSender();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * The collect method allow the dispatcher to dispatch an event to the subscribed redactors to know which events
     * they are subscribed to.
     * Then the dispatcher register itself in the event manager to listen on all the events that redactors are subscribed.
     * It is required that the EmailSubscriptionDispatcher collects the subscribers before it register, if not, it will not
     * be subscribed to any events.
     * @return $this
     */
    public function collectSubscribedEmailRedactors()
    {
        $this->getEventManager()->dispatch(
            CollectSubscribedEmailRedactorEvent::create($this->emailSubscriptionManager)
        );
        $this->getEventManager()->on($this);

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
     * @param Event $event Event object to dispatch
     * @return void
     */
    public function __invoke(Event $event)
    {
        $this->dispatch($event);
    }

    /**
     * @param Event $event Event object to dispatch
     * @return void
     */
    public function dispatch(Event $event)
    {
        foreach ($this->emailSubscriptionManager->getSubscriptionsForEvent($event) as $emailRedactor) {
            $emailCollection = $emailRedactor->onSubscribedEvent($event);

            if (!$emailCollection instanceof EmailCollection) {
                throw new InvalidArgumentException('$emailCollection must be an instance of ' . EmailCollection::class);
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
                                'exception' => $t,
                            ]
                        );
                    }
                }
            }
        }
    }
}
