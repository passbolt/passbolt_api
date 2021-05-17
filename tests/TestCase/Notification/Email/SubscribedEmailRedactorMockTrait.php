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
 * @since         2.0.0
 */

namespace App\Test\TestCase\Notification\Email;

use App\Notification\Email\CollectSubscribedEmailRedactorEvent;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;

trait SubscribedEmailRedactorMockTrait
{
    private function createSubscribedRedactor(array $subscribedEvents, Email $email)
    {
        return new class ($subscribedEvents, $email) implements SubscribedEmailRedactorInterface
        {
            use SubscribedEmailRedactorTrait;

            /**
             * @var string
             */
            private $subscribedEvents;

            /**
             * @var Email
             */
            private $email;

            public function __construct(array $subscribedEvents, Email $email)
            {
                $this->subscribedEvents = $subscribedEvents;
                $this->email = $email;
            }

            public function onSubscribedEvent(Event $event): EmailCollection
            {
                return new EmailCollection([$this->email]);
            }

            public function getSubscribedEvents(): array
            {
                return $this->subscribedEvents;
            }

            public function subscribe(CollectSubscribedEmailRedactorEvent $event)
            {
            }

            public function implementedEvents(): array
            {
                return [];
            }
        };
    }
}
