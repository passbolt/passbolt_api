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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Notification\Email;

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
use Passbolt\OfflineMode\Model\Entity\OfflineModeSetting;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;

class OfflineSettingsDeleteEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'Passbolt/OfflineMode.AD/settings_deleted';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [OfflineSettingsDeleteService::EVENT_SETTINGS_DELETED];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @param \Cake\Event\Event $event Settings-deleted event.
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $entity = $event->getData('entity');
        if (!$entity instanceof OfflineModeSetting) {
            throw new InvalidArgumentException('`entity` is missing from event data.');
        }
        $uac = $event->getData('uac');
        if (!$uac instanceof ExtendedUserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var array<\App\Model\Entity\User> $admins */
        $admins = $usersTable->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->all();

        if (count($admins) === 0) {
            return $emailCollection;
        }

        $operator = $usersTable->findFirstForEmail($uac->getId());

        foreach ($admins as $recipient) {
            $emailCollection->addEmail(
                $this->createEmail($recipient, $operator, $entity, $uac->getUserIp(), $uac->getUserAgent()),
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Admin to notify.
     * @param \App\Model\Entity\User $operator Admin who performed the action.
     * @param \Passbolt\OfflineMode\Model\Entity\OfflineModeSetting $entity The deleted settings entity.
     * @param string $clientIp Client IP.
     * @param string $userAgent User browser agent.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $operator,
        OfflineModeSetting $entity,
        string $clientIp,
        string $userAgent,
    ): Email {
        $subject = $this->getSubject($recipient, $operator);

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'entity' => $entity,
                    'ip' => $clientIp,
                    'user_agent' => $userAgent,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE,
        );
    }

    /**
     * @param \App\Model\Entity\User $recipient Recipient admin.
     * @param \App\Model\Entity\User $operator Acting admin.
     * @return string
     */
    private function getSubject(User $recipient, User $operator): string
    {
        return (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($recipient, $operator) {
                return $operator->id === $recipient->id
                    ? __('You disabled Offline Mode')
                    : __('{0} disabled Offline Mode', $operator->profile->first_name);
            },
        );
    }
}
