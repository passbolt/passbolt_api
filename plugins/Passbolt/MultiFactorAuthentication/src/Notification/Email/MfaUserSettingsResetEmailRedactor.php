<?php

namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;

class MfaUserSettingsResetEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE = 'Passbolt/MultiFactorAuthentication.LU/mfa_user_settings_reset';

    /**
     * @param User $user
     * @return Email
     */
    public function createEmail(User $user)
    {
        return new Email(
            $user->username,
            __('Multi-factor authentication settings were reset for your account.'),
            [
                'title' => 'Multi-factor authentication settings reset.',
                'body' => ['user' => $user]
            ],
            self::TEMPLATE
        );
    }

    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();
        $email = $this->createEmail($event->getData('user'));

        return $emailCollection->addEmail($email);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            'mfa.user_settings.reset'
        ];
    }
}
