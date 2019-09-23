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
 * @since         2.12.0
 */
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
     * @param User $user entity, user who got their MFA settings reset
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

    /**
     * @param Event $event user settings reset event
     * @return EmailCollection
     */
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
