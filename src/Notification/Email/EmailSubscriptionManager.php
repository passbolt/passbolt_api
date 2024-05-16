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

/**
 * Class EmailSubscriptionManager
 *
 * @package App\Notification\Email
 *
 * The EmailSubscriptionManager class is a composite object to handle the email subscriptions.
 * It handles the list of the subscription from creation to retrieval.
 * It is provided by the EmailSubscriptionDispatcher to the SubscribedEmailRedactor, and used
 * by the latter to make the registration.
 */
class EmailSubscriptionManager
{
    /**
     * @var array
     */
    private array $subscriptions = [];

    /**
     * @param \App\Notification\Email\SubscribedEmailRedactorInterface $subscribedEmailRedactor Email Redactor
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
     * @param \Cake\Event\Event $event Event object
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscriptionsForEvent(Event $event): array
    {
        return $this->subscriptions[$event->getName()] ?? [];
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return array_keys($this->subscriptions);
    }
}
