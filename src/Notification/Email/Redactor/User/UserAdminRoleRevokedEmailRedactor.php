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
 * @since         4.4.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\Purifier;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;

class UserAdminRoleRevokedEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;
    use LocatorAwareTrait;

    public const CONFIG_KEY_EMAIL_ENABLED = 'passbolt.email.send.admin.user.adminRoleRevoked.admin';
    public const CONFIG_KEY_SEND_USER_EMAIL = 'passbolt.email.send.admin.user.adminRoleRevoked.user';

    public const TEMPLATE = 'AD/admin_role_revoked';

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            UsersEditController::EVENT_USER_AFTER_UPDATE,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @param \Cake\Event\Event $event User register event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Utility\ExtendedUserAccessControl $uac */
        $uac = $event->getData('operator');
        if (!$uac instanceof ExtendedUserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        if (!$user instanceof User) {
            throw new InvalidArgumentException('`user` is missing from event data.');
        }

        // No need to send email if role is not changed
        if (!$user->isDirty('role_id') || !$this->isAdminRoleRevoked($user)) {
            return $emailCollection;
        }

        $clientIp = $uac->getUserIp();
        $userAgent = $uac->getUserAgent();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $recipients = $usersTable
            ->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->where(['Users.id !=' => $user->id])
            ->toArray();
        if (Configure::read(self::CONFIG_KEY_SEND_USER_EMAIL)) {
            $recipients[] = $usersTable
                ->find('notDisabled')
                ->find('locale')
                ->where(['Users.id' => $user->id, 'Users.deleted' => false])
                ->contain([
                    'Roles',
                    'Profiles' => AvatarsTable::addContainAvatar(),
                ])
                ->firstOrFail();
        }

        $operator = $usersTable->findFirstForEmail($uac->getId());

        foreach ($recipients as $admin) {
            $emailCollection->addEmail($this->createEmail($admin, $operator, $user, $clientIp, $userAgent));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient(admin) to send email to.
     * @param \App\Model\Entity\User $operator The user who changed to role.
     * @param \App\Model\Entity\User $user The user whose role got changed.
     * @param string $clientIp Operator's IP.
     * @param string $userAgent Operator's user agent.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $operator,
        User $user,
        string $clientIp,
        string $userAgent
    ): Email {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($user, $recipient) {
                $userFullName = Purifier::clean($user->profile->full_name);

                return $user->id === $recipient->id ?
                    __('Your admin role has been revoked') :
                    __('{0}\'s admin role has been revoked', $userFullName);
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'user' => $user,
                    'ip' => $clientIp,
                    'user_agent' => $userAgent,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * @param \App\Model\Entity\User $user User entity.
     * @return bool
     */
    private function isAdminRoleRevoked(User $user): bool
    {
        /** @var \App\Model\Table\RolesTable $rolesTable */
        $rolesTable = $this->fetchTable('Roles');
        /** @var \App\Model\Entity\Role $roleAdmin */
        $roleAdmin = $rolesTable
            ->find()
            ->select(['id', 'name'])
            ->where(['name' => Role::ADMIN])
            ->firstOrFail();

        if ($user->getOriginal('role_id') === $roleAdmin->id && $user->role_id !== $roleAdmin->id) {
            return true;
        }

        return false;
    }
}
