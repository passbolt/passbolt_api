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
 * @since         5.8.0
 */

namespace App\Notification\Email\Redactor\User;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\ExtendedUserAccessControl;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;

class UserRoleUpdatedEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;
    use LocatorAwareTrait;

    public const TEMPLATE = 'LU/user_role_updated';

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
        if (!$this->isUserRoleUpdated($user)) {
            return $emailCollection;
        }

        $isAdminRoleRevoked = UserAdminRoleRevokedEmailRedactor::isAdminRoleRevoked($user);
        $clientIp = $uac->getUserIp();
        $userAgent = $uac->getUserAgent();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');

        $recipient = $usersTable
            ->find('locale')
            ->contain([
                'Roles',
                'Profiles' => AvatarsTable::addContainAvatar(),
            ])
            ->where(['Users.id' => $user->id])
            ->firstOrFail();

        $operator = $usersTable->findFirstForEmail($uac->getId());

        $emailCollection->addEmail(
            $this->createEmail($operator, $recipient, $clientIp, $userAgent, $isAdminRoleRevoked)
        );

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $operator The user who updated the role.
     * @param \App\Model\Entity\User $recipient The user and recipient whose role got updated.
     * @param string $clientIp Operator's IP.
     * @param string $userAgent Operator's user agent.
     * @param bool $isAdminRoleRevoked If admin role is revoked or not.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $operator,
        User $recipient,
        string $clientIp,
        string $userAgent,
        bool $isAdminRoleRevoked = false
    ): Email {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('Your role has been updated');
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'ip' => $clientIp,
                    'user_agent' => $userAgent,
                    'isAdminRoleRevoked' => $isAdminRoleRevoked,
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
    private function isUserRoleUpdated(User $user): bool
    {
        if (!$user->isDirty('role_id')) {
            return false;
        }

        $previousValue = $user->getOriginal('role_id');
        $value = $user->role_id;

        return $previousValue !== $value;
    }
}
