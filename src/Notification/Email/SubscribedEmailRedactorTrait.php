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

use Cake\Core\InstanceConfigTrait;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

/**
 * Trait SubscribedEmailRedactorTrait
 *
 * @package App\Notification\Email
 *
 * The SubscribedEmailRedactorTrait is a convenient trait used by EmailRedactor implementing
 * the SubscribedEmailRedactorInterface. It eases the creation of new redactors by providing
 * boilerplate code to get subscribed to the email dispatcher.
 */
trait SubscribedEmailRedactorTrait
{
    use InstanceConfigTrait {
        InstanceConfigTrait::getConfig as parentGetConfig;
    }

    /**
     * @var array
     */
    private $_defaultConfig = [];

    /**
     * @param string|null $key Configuration key to retrieve
     * @param mixed $default Default value
     * @return mixed
     */
    public function getConfig(?string $key = null, $default = null)
    {
        return $this->parentGetConfig($key) ?? EmailNotificationSettings::get($key);
    }

    /**
     * @return array<string, mixed>
     */
    public function implementedEvents(): array
    {
        return [
            CollectSubscribedEmailRedactorEvent::EVENT_NAME => $this,
        ];
    }

    /**
     * @param \App\Notification\Email\CollectSubscribedEmailRedactorEvent $event Event object
     * @return void
     */
    public function subscribe(CollectSubscribedEmailRedactorEvent $event)
    {
        /** @var \App\Notification\Email\SubscribedEmailRedactorInterface $this */
        $event->getManager()->addNewSubscription($this);
    }

    /**
     * @param \App\Notification\Email\CollectSubscribedEmailRedactorEvent $event Event object
     * @return void
     */
    public function __invoke(CollectSubscribedEmailRedactorEvent $event)
    {
        $this->subscribe($event);
    }
}
