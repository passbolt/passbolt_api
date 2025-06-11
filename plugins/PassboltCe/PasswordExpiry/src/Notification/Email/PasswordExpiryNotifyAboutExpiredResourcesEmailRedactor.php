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
 * @since         5.2.0
 */

namespace Passbolt\PasswordExpiry\Notification\Email;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryGetOwnersOfExpiredResourcesService;

class PasswordExpiryNotifyAboutExpiredResourcesEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/PasswordExpiry.LU/notify_about_expired_resources';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            PasswordExpiryGetOwnersOfExpiredResourcesService::NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return 'send.password.expire';
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();
        $users = $event->getData('users');

        // Send emails to all the owners
        foreach ($users as $user) {
            $emailCollection->addEmail($this->createEmail($user));
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user the recipient to send email to.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $user,
    ): Email {
        $localeService = new LocaleService();
        $subject = $localeService->translateString(
            $user->locale,
            function () {
                return __('You have passwords requiring your attention');
            }
        );
        $message = $localeService->translateString(
            $user->locale,
            function () {
                return __('Some of your passwords are expiring today.');
            }
        );

        return new Email(
            $user,
            $subject,
            [
                'body' => compact('message', 'user'),
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
