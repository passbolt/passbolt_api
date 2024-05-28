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
 * @since         4.9.0
 */

namespace Passbolt\Sso\Notification\Email\Stage2;

use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Chronos\ChronosInterface;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Service\Cache\AzureSsoSecretExpiryNotifyCacheService;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\Sso\Azure\SsoAzureService;

class AzureSsoSecretExpiryNotifyRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/Sso.AD/azure_secret_expiry_notify';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AbstractSso2Stage2Controller::EVENT_USER_LOGIN_SUCCESS,
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

        $ssoService = $event->getData('ssoService');
        if (!$ssoService instanceof AbstractSsoService) {
            throw new InvalidArgumentException('`$ssoService` should be an instance of `AbstractSsoService`.');
        }

        if (!$ssoService instanceof SsoAzureService) {
            // No need to do anything
            return $emailCollection;
        }

        $cachePresent = (new AzureSsoSecretExpiryNotifyCacheService())->read('azure_secret_expiry_notification_performed'); // phpcs:ignore
        if ($cachePresent) {
            return $emailCollection;
        }

        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsAzureDataDto $ssoSettings */
        $ssoSettings = $ssoService->getSettings()->getData();
        $secretExpiry = $ssoSettings->client_secret_expiry;
        if (is_string($secretExpiry)) {
            $secretExpiry = new FrozenTime($secretExpiry);
        }

        if (!$secretExpiry->isWithinNext('10 days')) {
            return $emailCollection;
        }

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        // Get all the active admins to notify them
        $recipients = $usersTable
            ->findAdmins()
            ->find('notDisabled')
            ->find('locale')
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->toArray();

        if (empty($recipients)) {
            return $emailCollection;
        }

        foreach ($recipients as $recipient) {
            $emailCollection->addEmail($this->createEmail($recipient, $secretExpiry));
        }

        // Do not forget to write cache upon successful email sent
        (new AzureSsoSecretExpiryNotifyCacheService())->write('azure_secret_expiry_notification_performed', '1');

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient(admin) to send email to.
     * @param \Cake\Chronos\ChronosInterface $secretExpiryDate Secret expiry date object.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, ChronosInterface $secretExpiryDate): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('Azure SSO secret expiry date is near');
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'secret_expiry_date' => $secretExpiryDate->toDateString(), // format: 'Y-m-d'
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
