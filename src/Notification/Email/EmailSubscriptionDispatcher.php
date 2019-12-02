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
use Cake\Event\EventListenerInterface;
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
     * @param EventManagerInterface $eventManager Event manager
     * @param EmailSubscriptionManager $emailSubscriptionManager EmailSubscriptionManaer
     * @param EmailSender $emailSender EmailSender
     * @param LoggerInterface $logger Logger
     */
    public function __construct(
        EventManagerInterface $eventManager,
        EmailSubscriptionManager $emailSubscriptionManager,
        EmailSender $emailSender,
        LoggerInterface $logger = null
    ) {
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
                                'exception' => $t
                            ]
                        );
                    }
                }
            }
        }
    }
}
