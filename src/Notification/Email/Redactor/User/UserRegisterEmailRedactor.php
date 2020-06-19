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

    const TEMPLATE_REGISTER_SELF = 'AN/user_register_self';
    const TEMPLATE_REGISTER_ADMIN = 'AN/user_register_admin';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * @param UsersTable|null $usersTable Users Table
     */
    public function __construct(UsersTable $usersTable = null)
    {
        $this->usersTable = $usersTable ?? TableRegistry::getTableLocator()->get('Users');
    }

    /**
     * @param Event $event User register event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();

        $user = $event->getData('user');
        $uac = $event->getData('token');
        $adminId = $event->getData('adminId');

        $email = $adminId ? $this->createEmailAdminRegister($user, $uac, $adminId) : $this->createEmailSelfRegister($user, $uac);

        return $emailCollection->addEmail($email);
    }

    /**
     * @param User $user User to include in the subject
     * @return string
     */
    private function getSubject(User $user)
    {
        return __("Welcome to passbolt, {0}!", $user->profile->first_name);
    }

    /**
     * @param User                $user User
     * @param AuthenticationToken $uac UAC
     * @return Email
     */
    private function createEmailSelfRegister(User $user, AuthenticationToken $uac)
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
     * @param User                $user User
     * @param AuthenticationToken $uac UAC
     * @param string              $adminId Admin user ID
     * @return Email
     */
    private function createEmailAdminRegister(User $user, AuthenticationToken $uac, string $adminId)
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
    public function getSubscribedEvents()
    {
        return [
            UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME,
        ];
    }
}
