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
 * @since         2.13.0
 */

namespace App\Notification\Email\Redactor\Recovery;

use App\Controller\Users\UsersRecoverController;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

class AccountRecoveryEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'AN/user_recover';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersRecoverController::RECOVER_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.user.recover';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        /** @var \App\Model\Entity\AuthenticationToken $token */
        $token = $event->getData('token');
        /** @var string $case */
        $case = $event->getData('case') ?? 'default';

        $emailCollection->addEmail($this->createAccountRecoveryEmail($user, $token, $case));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $token Token for recovery
     * @param string $case 'lost-passphrase' or 'default'
     * @return \App\Notification\Email\Email
     */
    private function createAccountRecoveryEmail(User $user, AuthenticationToken $token, string $case): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($user) {
                return __('Your account recovery, {0}!', $user->profile->first_name);
            }
        );

        $data = ['body' => ['user' => $user, 'token' => $token, 'case' => $case], 'title' => $subject];

        return new Email($user, $subject, $data, self::TEMPLATE);
    }
}
