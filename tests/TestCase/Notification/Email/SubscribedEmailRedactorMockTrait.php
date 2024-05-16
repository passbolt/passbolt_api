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

use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;

trait SubscribedEmailRedactorMockTrait
{
    private function createSubscribedRedactor(
        array $subscribedEvents,
        Email $email,
        ?string $notificationSettingPath = null
    ): SubscribedEmailRedactorInterface {
        return new class ($subscribedEvents, $email, $notificationSettingPath) implements SubscribedEmailRedactorInterface
        {
            use SubscribedEmailRedactorTrait;

            private array $subscribedEvents;

            private Email $email;

            private ?string $notificationSettingPath;

            public function __construct(array $subscribedEvents, Email $email, ?string $notificationSettingPath)
            {
                $this->subscribedEvents = $subscribedEvents;
                $this->email = $email;
                $this->notificationSettingPath = $notificationSettingPath;
            }

            public function onSubscribedEvent(Event $event): EmailCollection
            {
                return new EmailCollection([$this->email]);
            }

            /**
             * @inheritDoc
             */
            public function getNotificationSettingPath(): ?string
            {
                return $this->notificationSettingPath;
            }

            public function getSubscribedEvents(): array
            {
                return $this->subscribedEvents;
            }
        };
    }
}
