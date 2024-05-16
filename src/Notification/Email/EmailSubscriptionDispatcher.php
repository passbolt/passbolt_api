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
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;
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
    private EmailSubscriptionManager $emailSubscriptionManager;

    /**
     * @var \App\Notification\Email\EmailSender
     */
    private EmailSender $emailSender;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * The constructor
     */
    public function __construct()
    {
        $this->setEventManager(EventManager::instance());
        $this->emailSubscriptionManager = new EmailSubscriptionManager();
        $this->emailSender = new EmailSender();
        $this->logger = new NullLogger();
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
     * @inheritDoc
     */
    public function implementedEvents(): array
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
     * Check if the email redactor should send emails, based on its settings
     * If the path is null, we consider that this redactor cannot be configured, it is always enabled.
     *
     * @param \App\Notification\Email\SubscribedEmailRedactorInterface $emailRedactor email redactor
     * @return bool
     */
    private function isRedactorActive(SubscribedEmailRedactorInterface $emailRedactor): bool
    {
        $settingPath = $emailRedactor->getNotificationSettingPath();

        return is_null($settingPath) || EmailNotificationSettings::get($settingPath);
    }

    /**
     * @param \Cake\Event\Event $event Event object to dispatch
     * @return void
     */
    public function dispatch(Event $event)
    {
        foreach ($this->emailSubscriptionManager->getSubscriptionsForEvent($event) as $emailRedactor) {
            if (!$this->isRedactorActive($emailRedactor)) {
                continue;
            }
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
