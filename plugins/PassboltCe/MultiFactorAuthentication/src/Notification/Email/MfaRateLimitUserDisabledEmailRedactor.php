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
 * Notifies the user whose account was disabled after too many failed MFA attempts.
 */
class MfaRateLimitUserDisabledEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'Passbolt/MultiFactorAuthentication.LU/mfa_rate_limit_user_disabled';

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
        return 'send.mfa.disable.user';
    }

    /**
     * @param \Cake\Event\Event $event User disabled on MFA rate limit event.
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $user = $event->getData('user');
        if (!$user instanceof User) {
            throw new InvalidArgumentException('`user` is missing from event data.');
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        $recipient = $usersTable->findFirstForEmail($user->id);
        if ($recipient === null) {
            return new EmailCollection();
        }

        return (new EmailCollection())->addEmailToDisabledRecipient($this->createEmail($recipient));
    }

    /**
     * @param \App\Model\Entity\User $recipient User whose account was disabled.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('Your account has been suspended');
            },
        );

        return new Email(
            $recipient,
            $subject,
            ['body' => ['user' => $recipient], 'title' => $subject],
            self::TEMPLATE,
        );
    }
}
