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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\MultiFactorAuthentication\Service\MfaRateLimitDisableUserService;

/**
 * Notifies the administrators when an account was disabled after too many failed MFA attempts.
 */
class MfaRateLimitAdminDisabledEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'Passbolt/MultiFactorAuthentication.AD/mfa_rate_limit_user_disabled';

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.mfa.disable.admin';
    }

    /**
     * @param \Cake\Event\Event $event User disabled on MFA rate limit event.
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        /** @var \App\Model\Entity\User $disabledUser */
        $disabledUser = $event->getData('user');
        if (!$disabledUser instanceof User) {
            throw new InvalidArgumentException('`disabledUser` is missing from event data.');
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $user = $usersTable->findFirstForEmail($disabledUser->id);

        if ($user === null) {
            return new EmailCollection();
        }

        $recipients = $usersTable
            ->findAdmins()
            ->find('locale')
            ->find('notDisabled')
            ->contain([
                'Profiles' => AvatarsTable::addContainAvatar(),
                'Roles',
            ]);

        $emailCollection = new EmailCollection();
        /** @var \App\Model\Entity\User $recipient */
        foreach ($recipients as $recipient) {
            $emailCollection->addEmail($this->createEmail($recipient, $user));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient Administrator to notify.
     * @param \App\Model\Entity\User $user User whose account was disabled.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, User $user): Email
    {
        $userFullName = $user->profile->full_name;
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () use ($userFullName) {
                return __('{0} has been suspended', $userFullName);
            },
        );

        return new Email(
            $recipient,
            $subject,
            ['body' => compact('userFullName', 'user', 'recipient'), 'title' => $subject],
            self::TEMPLATE,
        );
    }
}
