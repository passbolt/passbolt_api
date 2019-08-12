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
 * @since         2.0.0
 */

namespace App\Notification\Email;

use Cake\Event\Event;

class EmailSubscriptionManager
{
    /**
     * @var array
     */
    private $subscriptions = [];

    /**
     * @param SubscribedEmailRedactorInterface $subscribedEmailRedactor Email Redactor
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
     * @param Event $event Event object
     * @return SubscribedEmailRedactorInterface[]
     */
    public function getSubscriptionsForEvent(Event $event)
    {
        return $this->subscriptions[$event->getName()] ?? [];
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array_keys($this->subscriptions);
    }
}
