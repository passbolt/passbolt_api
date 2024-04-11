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

namespace Passbolt\SelfRegistration\Notification\Email\Redactor\User;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\LocaleService;

class SelfRegistrationAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const EMAIL_TEMPLATE = 'AD/user_register_self';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersTable::AFTER_SELF_REGISTER_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.admin.user.register.complete';
    }

    /**
     * @param \Cake\Event\Event $event User register event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $user = $event->getData('user');
        $uac = $event->getData('token');

        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $admins = $UsersTable
            ->findAdmins()
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->find('locale')
            ->find('notDisabled');

        foreach ($admins as $recipient) {
            $email = $this->createEmailForAdminSelfRegister($recipient, $user);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Recipient of the email
     * @param \App\Model\Entity\User $user User to include in the subject
     * @return string
     */
    private function getSubject(User $recipient, User $user): string
    {
        $userFirstName = Purifier::clean($user['profile']['first_name']);

        return (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($userFirstName) {
                return __('{0} just created an account on passbolt!', $userFirstName);
            }
        );
    }

    /**
     * @param \App\Model\Entity\User $recipient User
     * @param \App\Model\Entity\User $user User
     * @return \App\Notification\Email\Email
     */
    private function createEmailForAdminSelfRegister(User $recipient, User $user): Email
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $user = $UsersTable->findFirstForEmail($user->id);

        return new Email(
            $recipient,
            $this->getSubject($recipient, $user),
            [
                'body' => compact('user', 'recipient'),
                'title' => $this->getSubject($recipient, $user),
            ],
            static::EMAIL_TEMPLATE
        );
    }
}
