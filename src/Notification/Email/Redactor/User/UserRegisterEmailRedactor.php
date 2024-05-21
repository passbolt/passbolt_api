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
use Passbolt\Locale\Service\LocaleService;

class UserRegisterEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE_REGISTER_ADMIN = 'AN/user_register_admin';

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersTable::AFTER_REGISTER_SUCCESS_EVENT_NAME,
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
        $adminId = $event->getData('adminId');

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        if (!$user->isDisabled()) {
            $email = $this->createEmailAdminRegister($user, $uac, $adminId);
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
        return (new LocaleService())->translateString(
            $user->locale,
            function () use ($user) {
                return __('Welcome to passbolt, {0}!', $user->profile->first_name);
            }
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
        /** @var \App\Model\Table\UsersTable $UsersTable */
        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $admin = $UsersTable->findFirstForEmail($adminId);
        $user = $UsersTable->findFirstForEmail($user->id);

        $UsersTable->loadInto($user, [
            'Profiles' => AvatarsTable::addContainAvatar(),
        ]);

        return new Email(
            $user,
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
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.user.create';
    }
}
