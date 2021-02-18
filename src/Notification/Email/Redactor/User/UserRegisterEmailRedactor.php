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

namespace App\Notification\Email\Redactor\User;

use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class UserRegisterEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE_REGISTER_SELF = 'AN/user_register_self';
    public const TEMPLATE_REGISTER_ADMIN = 'AN/user_register_admin';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private $usersTable;

    /**
     * @param \App\Model\Table\UsersTable|null $usersTable Users Table
     */
    public function __construct(?UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
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
        $adminId = $event->getData('adminId');

        if ($adminId) {
            $email = $this->createEmailAdminRegister($user, $uac, $adminId);
        } else {
            $email = $this->createEmailSelfRegister($user, $uac);
        }

        return $emailCollection->addEmail($email);
    }

    /**
     * @param \App\Model\Entity\User $user User to include in the subject
     * @return string
     */
    private function getSubject(User $user): string
    {
        return __('Welcome to passbolt, {0}!', $user->profile->first_name);
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $uac UAC
     * @return \App\Notification\Email\Email
     */
    private function createEmailSelfRegister(User $user, AuthenticationToken $uac): Email
    {
        $user = $this->usersTable->findFirstForEmail($user->id);

        return new Email(
            $user->username,
            $this->getSubject($user),
            [
                'body' => [
                    'user' => $user, 'token' => $uac,
                ],
                'title' => $this->getSubject($user),
            ],
            static::TEMPLATE_REGISTER_SELF
        );
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \App\Model\Entity\AuthenticationToken $uac UAC
     * @param string $adminId Admin user ID
     * @return \App\Notification\Email\Email
     */
    private function createEmailAdminRegister(User $user, AuthenticationToken $uac, string $adminId): Email
    {
        $admin = $this->usersTable->findFirstForEmail($adminId);

        $this->usersTable->loadInto($user, [
            'Profiles' => AvatarsTable::addContainAvatar(),
        ]);

        return new Email(
            $user->username,
            $this->getSubject($user),
            [
                'body' => [
                    'user' => $user, 'token' => $uac, 'admin' => $admin,
                ],
                'title' => $this->getSubject($user),
            ],
            static::TEMPLATE_REGISTER_ADMIN
        );
    }

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME,
        ];
    }
}
