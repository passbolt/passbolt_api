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
 * @since         4.3.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;

/**
 * Class UserDisableEmailRedactor
 */
class UserDisableEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersEditController::EVENT_USER_WAS_DISABLED,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.admin.user.disable.user';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = $this->fetchTable('Users');
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        /** @var \App\Utility\UserAccessControl $operator */
        $operator = $event->getData('operator');
        $operator = $UsersTable->findFirstForEmail($operator->getId());

        $recipients = $UsersTable
            ->findAdmins()
            ->find('locale')
            ->find('notDisabled')
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
                'Roles',
            ]);

        /** @var \App\Model\Entity\User $recipient */
        foreach ($recipients as $recipient) {
            $email = $this->createEmail($recipient, $user);
            $emailCollection->addEmail($email);
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Recipient
     * @param \App\Model\Entity\User $user User
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $user
    ): Email {
        $userFullName = Purifier::clean($user->profile->first_name) . ' ' . Purifier::clean($user->profile->last_name);
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($userFullName) {
                return __('{0} has been suspended', $userFullName);
            }
        );

        return new Email(
            $recipient,
            $subject,
            ['body' => compact('userFullName', 'user', 'recipient'), 'title' => $subject],
            'AD/user_disable'
        );
    }
}
