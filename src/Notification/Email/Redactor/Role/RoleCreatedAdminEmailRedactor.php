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

namespace App\Notification\Email\Redactor\Role;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Roles\RolesAddService;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;

/**
 * Email sent to all administrators when a new role is created.
 */
class RoleCreatedAdminEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'AD/role_created';

    /**
     * @var \App\Model\Table\UsersTable
     */
    private UsersTable $Users;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            RolesAddService::AFTER_ROLE_CREATE_SUCCESS_EVENT_NAME,
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
     * @param \Cake\Event\Event $event Role create event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');
        if (!$uac instanceof UserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \App\Model\Entity\Role $role */
        $role = $event->getData('role');
        if (!$role instanceof Role) {
            throw new InvalidArgumentException('`role` is missing from event data.');
        }

        $operator = $this->Users->findFirstForEmail($uac->getId());
        $recipients = $this->getAdministrators($uac->getId());

        foreach ($recipients as $recipient) {
            $emailCollection->addEmail(
                $this->createEmail($recipient, $role, $operator)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient User recipient.
     * @param \App\Model\Entity\Role $role Role that was created.
     * @param \App\Model\Entity\User $operator User admin who created the role.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, Role $role, User $operator): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($role, $operator) {
                return __('{0} created a new role {1}', $operator->profile->full_name, $role->name);
            }
        );

        $operator->profile->setVirtual(['full_name']);

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'role' => $role,
                    'operator' => $operator,
                ],
            ],
            self::TEMPLATE
        );
    }

    /**
     * @param string $operatorId ID of the user who created the role.
     * @return array<\App\Model\Entity\User>
     */
    private function getAdministrators(string $operatorId): array
    {
        return $this->Users
            ->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->where(['Users.id !=' => $operatorId])
            ->toArray();
    }
}
