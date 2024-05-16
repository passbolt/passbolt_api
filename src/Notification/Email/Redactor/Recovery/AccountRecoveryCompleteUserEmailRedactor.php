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
 * @since         3.6.0
 */

namespace App\Notification\Email\Redactor\Recovery;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Service\Setup\RecoverCompleteServiceInterface;
use Cake\Event\Event;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

class AccountRecoveryCompleteUserEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'AN/user_recover_complete';

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            RecoverCompleteServiceInterface::COMPLETE_SUCCESS_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.user.recoverComplete';
    }

    /**
     * @param \Cake\Event\Event $event User delete event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        /** @var \App\Model\Entity\User $user */
        $user = $event->getData('user');
        $clientIp = $event->getData('clientIp') ?? '';
        $userAgent = $event->getData('userAgent') ?? '';
        $emailCollection->addEmail($this->createAccountRecoveryUserEmail($user, $clientIp, $userAgent));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param string $clientIp Client IP address
     * @param string $userAgent Client User Agent
     * @return \App\Notification\Email\Email
     */
    private function createAccountRecoveryUserEmail(User $user, string $clientIp, string $userAgent): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () {
                return __('You just completed the account recovery process!');
            }
        );

        $data = ['body' => compact('user', 'clientIp', 'userAgent'), 'title' => $subject];

        return new Email($user, $subject, $data, self::TEMPLATE);
    }
}
