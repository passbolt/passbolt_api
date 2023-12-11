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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiryPolicies\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService;

class PasswordExpiryPoliciesNotifyAboutExpiredResourcesEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/PasswordExpiryPolicies.LU/notify_about_expired_resources';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            PasswordExpiryPoliciesGetOwnersOfResourcesAboutToExpireService::NOTIFY_ABOUT_EXPIRED_RESOURCES_EVENT_NAME,
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $expiryNotificationInDays = $event->getData('expiryNotificationInDays');
        $notifyIfAboutToExpire = $event->getData('notifyIfAboutToExpire');
        $notifyIfExpiresToday = $event->getData('notifyIfExpiresToday');

        /** @var \Cake\ORM\Query $users */
        $users = $event->getData('users');
        $users
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->find('active')
            ->find('notDisabled');

        // Send emails to all the administrators
        foreach ($users as $user) {
            $emailCollection->addEmail(
                $this->createEmail(
                    $user,
                    $expiryNotificationInDays,
                    $notifyIfAboutToExpire,
                    $notifyIfExpiresToday
                ),
            );
        }

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient the recipient to send email to.
     * @param null|int $expiryNotificationInDays send notification N days prior to expiry
     * @param bool $notifyIfAboutToExpire email notification setting
     * @param bool $notifyIfExpiresToday email notification setting
     * @return \App\Notification\Email\Email
     */
    private function createEmail(
        User $recipient,
        ?int $expiryNotificationInDays,
        bool $notifyIfAboutToExpire,
        bool $notifyIfExpiresToday
    ): Email {
        $localeService = new LocaleService();
        $subject = $localeService->translateString(
            $recipient->locale,
            function () {
                return __('You have passwords requiring your attention');
            }
        );
        $message = $localeService->translateString(
            $recipient->locale,
            function () use ($expiryNotificationInDays, $notifyIfAboutToExpire, $notifyIfExpiresToday) {
                if ($notifyIfExpiresToday && !$expiryNotificationInDays) {
                    return __('Some of your passwords are expired.');
                } elseif (!$notifyIfExpiresToday && $expiryNotificationInDays) {
                    return __('Some of your passwords are expiring in {0} days.', $expiryNotificationInDays);
                } else {
                    return __('Some of your passwords are expired or expiring in {0} days.', $expiryNotificationInDays);
                }
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => compact(
                    'recipient',
                    'expiryNotificationInDays',
                    'notifyIfAboutToExpire',
                    'notifyIfExpiresToday',
                    'message'
                ),
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
