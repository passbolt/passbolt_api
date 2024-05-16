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
use Cake\Event\EventListenerInterface;

/**
 * Interface SubscribedEmailRedactorInterface
 *
 * @package App\Notification\Email
 *
 * A class implementing the interface SubscribedEmailRedactorInterface
 * can subscribe to the events in which it is interested to send emails.
 */
interface SubscribedEmailRedactorInterface extends EventListenerInterface
{
    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array;

    /**
     * Method called by the EventDispatcher to register the redactor so it can be called when its subscribed events
     * are triggered.
     *
     * @param \App\Notification\Email\CollectSubscribedEmailRedactorEvent $event Event object
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event);

    /**
     * Method called when its subscribed events are dispatched by the EmailSubscriptionDispatcher.
     * Redactor MUST implement this method to manipulate the email collection to send.
     * Return an EmailCollection.
     *
     * @param \Cake\Event\Event $event Event object
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection;

    /**
     * Gets the path of the email notification setting of this redactor
     * If enabled in email settings, the redactor is active
     * If disabled in the email settings, the redactor is inactive
     * If the path is null, the redactor cannot be deactivated and is always active
     *
     * @return ?string
     */
    public function getNotificationSettingPath(): ?string;
}
