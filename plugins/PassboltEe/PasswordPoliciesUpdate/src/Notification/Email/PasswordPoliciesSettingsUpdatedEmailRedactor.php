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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPoliciesUpdate\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\Purifier;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPoliciesUpdate\Service\PasswordPoliciesUpdateSetSettingsService;

class PasswordPoliciesSettingsUpdatedEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/PasswordPoliciesUpdate.AD/settings_updated';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            PasswordPoliciesUpdateSetSettingsService::EVENT_SETTINGS_UPDATED,
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
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto $passwordPoliciesSettingsDto */
        $passwordPoliciesSettingsDto = $event->getData('passwordPoliciesSetting');
        if (!$passwordPoliciesSettingsDto instanceof PasswordPoliciesSettingsDto) {
            throw new InvalidArgumentException('`passwordPoliciesSetting` is missing from event data.');
        }

        /** @var \App\Utility\ExtendedUserAccessControl $uac */
        $uac = $event->getData('uac');
        if (!$uac instanceof ExtendedUserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        $clientIp = $uac->getUserIp();
        $userAgent = $uac->getUserAgent();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        // Get all the active admins to notify them all
        $admins = $usersTable->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->all();

        if (!count($admins)) {
            return $emailCollection;
        }

        $operator = $usersTable->findFirstForEmail($uac->getId());

        // Send emails to all the administrators
        foreach ($admins as $admin) {
            $emailCollection->addEmail(
                $this->createEmail($admin, $operator, $passwordPoliciesSettingsDto, $clientIp, $userAgent)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient(admin) to send email to.
     * @param \App\Model\Entity\User $operator The admin who performed the action.
     * @param \Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto $passwordPoliciesSettingsDto The password policies settings.
     * @param string $clientIp Client IP.
     * @param string $userAgent User browser agent.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $operator,
        PasswordPoliciesSettingsDto $passwordPoliciesSettingsDto,
        string $clientIp,
        string $userAgent
    ): Email {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($operator, $recipient) {
                return $operator->id === $recipient->id ?
                    __('You edited the password policy') :
                    __('{0} edited the password policy', Purifier::clean($operator->profile->first_name));
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'settings' => $passwordPoliciesSettingsDto,
                    'ip' => $clientIp,
                    'user_agent' => $userAgent,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
