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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Notification\Email\SsoSettings;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\Purifier;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsActivateService;

class SsoSettingsActivatedEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/Sso.AD/sso_settings_activated';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            SsoSettingsActivateService::AFTER_ACTIVATE_SSO_SETTINGS_EVENT,
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Utility\ExtendedUserAccessControl $uac */
        $uac = $event->getData('uac');
        if (!$uac instanceof ExtendedUserAccessControl) {
            throw new InvalidArgumentException('`uac` is missing from event data.');
        }

        /** @var \Passbolt\Sso\Model\Entity\SsoSetting $ssoSetting */
        $ssoSetting = $event->getData('ssoSetting');
        if (!$ssoSetting instanceof SsoSetting) {
            throw new InvalidArgumentException('`ssoSetting` is missing from event data.');
        }

        $clientIp = $uac->getUserIp();
        $userAgent = $uac->getUserAgent();

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        // Get all the other active admins to notify them
        $admins = $usersTable
            ->findAdmins()
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->toArray();

        // Required as we don't have sent email to operator
        [$operator, $recipients] = $this->filterOperatorAndRecipient($admins, $uac);

        foreach ($recipients as $recipient) {
            $emailCollection->addEmail(
                $this->createEmail($recipient, $operator, $ssoSetting, $clientIp, $userAgent)
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient(admin) to send email to.
     * @param \App\Model\Entity\User $operator The admin who performed the action.
     * @param \Passbolt\Sso\Model\Entity\SsoSetting $ssoSetting SSO setting entity.
     * @param string $clientIp Client IP.
     * @param string $userAgent User browser agent.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        User $operator,
        SsoSetting $ssoSetting,
        string $clientIp,
        string $userAgent
    ): Email {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($operator) {
                return __('{0} activated the SSO setting', Purifier::clean($operator->profile->first_name));
            }
        );

        return new Email(
            $recipient->username,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'operator' => $operator,
                    'ssoSetting' => $ssoSetting,
                    'ip' => $clientIp,
                    'user_agent' => $userAgent,
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }

    /**
     * @param array $admins Array of user entity.
     * @param \App\Utility\UserAccessControl $uac UAC object.
     * @return array
     */
    private function filterOperatorAndRecipient(array $admins, UserAccessControl $uac): array
    {
        $operator = null;
        $recipients = [];

        foreach ($admins as $admin) {
            if ($admin->id === $uac->getId()) {
                $operator = $admin;

                continue;
            }

            $recipients[] = $admin;
        }

        return [$operator, $recipients];
    }
}
