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
 * @since         3.10.0
 */

namespace Passbolt\SelfRegistration\Notification\Email\Redactor\User;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Users\UserRecoverService;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;

class SelfRegistrationUserEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const EMAIL_TEMPLATE = 'AN/user_register_self';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersTable::AFTER_SELF_REGISTER_SUCCESS_EVENT_NAME,
            UserRecoverService::AFTER_RECOVER_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event User register event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $uac = $event->getData('token');

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        if (!$user->isDisabled()) {
            $email = $this->createEmailSelfRegister($user, $uac);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User to include in the subject
     * @return string
     */
    private function getSubject(User $user): string
    {
        $userFirstName = Purifier::clean($user->profile->first_name);

        return (new LocaleService())->translateString(
            $user->locale,
            function () use ($userFirstName) {
                return __('Welcome to passbolt, {0}!', $userFirstName);
            }
        );
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $uac UAC
     * @return \App\Notification\Email\Email
     */
    private function createEmailSelfRegister(User $user, AuthenticationToken $uac): Email
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->findFirstForEmail($user->id);

        return new Email(
            $user,
            $this->getSubject($user),
            [
                'body' => [
                    'user' => $user, 'token' => $uac,
                ],
                'title' => $this->getSubject($user),
            ],
            static::EMAIL_TEMPLATE
        );
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.user.create';
    }
}
