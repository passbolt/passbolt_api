<?php

namespace App\Notification\Email;

use Cake\Event\Event;
use InvalidArgumentException;

/**
 * @method EmailSubscriptionManager getSubject()
 */
class CollectSubscribedEmailRedactorEvent extends Event
{
    const EVENT_NAME = 'subscribed_email_redactor.collect';

    /**
     * @param $name
     * @param null $subject
     * @param null $data
     */
    public function __construct($name, $subject = null, $data = null)
    {
        if (!$subject instanceof EmailSubscriptionManager) {
            throw new InvalidArgumentException('$subject must be an instance of ' . EmailSubscriptionManager::class);
        }

        parent::__construct($name, $subject, $data);
    }

    /**
     * @param EmailSubscriptionManager $emailSubscriptionManager
     * @return CollectSubscribedEmailRedactorEvent
     */
    public static function create(EmailSubscriptionManager $emailSubscriptionManager)
    {
        return new static(static::EVENT_NAME, $emailSubscriptionManager);
    }

    /**
     * @return EmailSubscriptionManager
     */
    public function getManager()
    {
        return $this->getSubject();
    }
}
