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

use Cake\Event\EventListenerInterface;

abstract class AbstractSubscribedEmailRedactorPool implements EventListenerInterface
{
    /**
     * @param \App\Notification\Email\CollectSubscribedEmailRedactorEvent $event Event object
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event)
    {
        foreach ($this->getSubscribedRedactors() as $redactor) {
            $redactor->subscribe($event);
        }
    }

    /**
     * @inheritDoc
     */
    final public function implementedEvents(): array
    {
        return [
            CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this,
        ];
    }

    /**
     * @param \App\Notification\Email\CollectSubscribedEmailRedactorEvent $event Event object
     * @return void
     */
    final public function __invoke(CollectSubscribedEmailRedactorEvent $event)
    {
        $this->subscribe($event);
    }

    /**
     * Return a list of subscribed redactors
     *
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    abstract public function getSubscribedRedactors();
}
