<?php
declare(strict_types=1);

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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;

/**
 * Class EmailSubscriptionDispatcher
 *
 * @package App\Notification\Email
 *
 * The EmailSubscriptionDispatcher class is the core class of the email notification system.
 * It provides the plugin with a mechanism to register emails. It does that by dispatching an event to
 * all subscribed SubscribedEmailRedactor in order to collect them.
 *
 * The dispatcher listen for all the subscribed events and will get the matching SubscribedEmailRedactor and
 * delegate to them the email creation. Then it will pass those created emails to the EmailSender for sending.
 */
class EmailSubscriptionDispatcher implements EventListenerInterface
{
    use EventDispatcherTrait;

    /**
     * @var \App\Notification\Email\EmailSubscriptionManager
     */
    private $emailSubscriptionManager;

    /**
     * @var \App\Notification\Email\EmailSender
     */
    private $emailSender;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @param \Cake\Event\EventManager|null $eventManager Event Manager Instance
     * @param \App\Notification\Email\EmailSubscriptionManager|null $emailSubscriptionManager instance
     * @param \App\Notification\Email\EmailSender|null $emailSender EmailSender Instance
     * @param \Psr\Log\LoggerInterface|null $logger Logger Instance
     */
    public function __construct(
        ?EventManager $eventManager = null,
        ?EmailSubscriptionManager $emailSubscriptionManager = null,
        ?EmailSender $emailSender = null,
        ?LoggerInterface $logger = null
    ) {
        $this->setEventManager($eventManager ?? EventManager::instance());
        $this->emailSubscriptionManager = $emailSubscriptionManager ?? new EmailSubscriptionManager();
        $this->emailSender = $emailSender ?? new EmailSender();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * The collect method allow the dispatcher to dispatch an event to the subscribed redactors to know which events
     * they are subscribed to.
     * Then the dispatcher register itself in the event manager to listen on all the events
     * that redactors are subscribed to.
     * It is required that the EmailSubscriptionDispatcher collects the subscribers before it register,
     * if not, it will not be subscribed to any events.
     *
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
     * @param \Cake\Event\Event $event Event object to dispatch
     * @return void
     */
    public function __invoke(Event $event)
    {
        $this->dispatch($event);
    }

    /**
     * @param \Cake\Event\Event $event Event object to dispatch
     * @return void
     */
    public function dispatch(Event $event)
    {
        foreach ($this->emailSubscriptionManager->getSubscriptionsForEvent($event) as $emailRedactor) {
            $emailCollection = $emailRedactor->onSubscribedEvent($event);

            if ($emailCollection->getEmails()) {
                foreach ($emailCollection->getEmails() as $email) {
                    try {
                        $this->emailSender->sendEmail($email);
                    } catch (Throwable $t) {
                        $msg = 'EmailSubscriptionDispatcher failed to send email on event `%s`';
                        $this->logger->alert(
                            sprintf($msg, $event->getName()),
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
