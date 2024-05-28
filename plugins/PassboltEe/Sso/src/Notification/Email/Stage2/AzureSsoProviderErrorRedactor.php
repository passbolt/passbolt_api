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
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Locator\LocatorAwareTrait;
use InvalidArgumentException;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Error\Exception\AzureException;
use Passbolt\Sso\Service\Cache\SsoProviderErrorCacheService;

class AzureSsoProviderErrorRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    /**
     * @var string
     */
    public const TEMPLATE = 'Passbolt/Sso.AD/azure_provider_error';

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER,
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

        $exception = $event->getData('exception');
        if (!$exception instanceof \Throwable) {
            throw new InvalidArgumentException('`$exception` should be an instance of `Throwable`.');
        }

        if ((!$exception instanceof AzureException) || ($exception->getError() !== 'invalid_client')) {
            // No need to do anything
            return $emailCollection;
        }

        $cachePresent = (new SsoProviderErrorCacheService())->read('login_error_admin_notification_performed');
        if ($cachePresent !== null) {
            // Do not send email if cache is present, this is to not spam administrators
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
            $emailCollection->addEmail($this->createEmail($recipient, $exception));
        }

        $msg = __('Single sign-on failed.') . ' ' . __('Provider error: "{0}"', $exception->getMessage());
        $event->setResult([
            'customException' => new BadRequestException($msg, 400, $exception),
        ]);

        // Do not forget to write cache upon successful email sent
        (new SsoProviderErrorCacheService())->write('login_error_admin_notification_performed', '1');

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $recipient The recipient(admin) to send email to.
     * @param \Passbolt\Sso\Error\Exception\AzureException $exception Exception.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $recipient, AzureException $exception): Email
    {
        $subject = (new LocaleService())->translateString(
            $recipient->locale,
            function () {
                return __('Users are unable to log in via Azure SSO');
            }
        );

        return new Email(
            $recipient,
            $subject,
            [
                'body' => [
                    'recipient' => $recipient,
                    'error' => $exception->getError(),
                    'error_description' => $exception->getErrorDescription(),
                ],
                'title' => $subject,
            ],
            self::TEMPLATE
        );
    }
}
