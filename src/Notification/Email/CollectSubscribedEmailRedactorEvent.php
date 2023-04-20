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
use InvalidArgumentException;

/**
 * Class CollectSubscribedEmailRedactorEvent
 *
 * @package App\Notification\Email
 * @method \App\Notification\Email\EmailSubscriptionManager getSubject() Event triggered by the EmailSubscriptionDispatcher to collect emails from the subscribed EmailRedactors.
 */
class CollectSubscribedEmailRedactorEvent extends Event
{
    public const EVENT_NAME = 'subscribed_email_redactor.collect';

    /**
     * @param string $name Name of the event
     * @param \App\Notification\Email\EmailSubscriptionManager|null $subject Subject of the dispatched event
     * @param array|null $data Data for the event
     */
    final public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof EmailSubscriptionManager) {
            throw new InvalidArgumentException('$subject must be an instance of ' . EmailSubscriptionManager::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param \App\Notification\Email\EmailSubscriptionManager $emailSubscriptionManager Email Subscription Manager
     * @return \App\Notification\Email\CollectSubscribedEmailRedactorEvent
     */
    public static function create(EmailSubscriptionManager $emailSubscriptionManager)
    {
        return new static(static::EVENT_NAME, $emailSubscriptionManager);
    }

    /**
     * @return \App\Notification\Email\EmailSubscriptionManager
     */
    public function getManager()
    {
        return $this->getSubject();
    }
}
