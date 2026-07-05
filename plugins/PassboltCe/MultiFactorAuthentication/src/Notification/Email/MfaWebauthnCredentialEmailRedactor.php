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
 * @since         5.14.0
 */
namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;

/**
 * Sends a security notification when a user registers or removes a WebAuthn security key.
 */
class MfaWebauthnCredentialEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE_REGISTERED = 'Passbolt/MultiFactorAuthentication.LU/mfa_webauthn_credential_registered';
    public const TEMPLATE_REMOVED = 'Passbolt/MultiFactorAuthentication.LU/mfa_webauthn_credential_removed';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

    /**
     * MfaWebauthnCredentialEmailRedactor constructor.
     */
    public function __construct()
    {
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            MfaWebauthnEnableService::WEBAUTHN_CREDENTIAL_REGISTERED_EVENT,
            MfaWebauthnEnableService::WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
        ];
    }

    /**
     * @param \Cake\Event\Event $event WebAuthn credential registered/removed event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emailCollection = new EmailCollection();

        $userId = $event->getData('userId');
        if (!is_string($userId)) {
            return $emailCollection;
        }

        try {
            /** @var \App\Model\Entity\User $user */
            $user = $this->Users->findFirstForEmail($userId);
        } catch (RecordNotFoundException $exception) {
            return $emailCollection;
        }
        if ($user === null || $user->isDisabled()) {
            return $emailCollection;
        }

        $isRegistration = $event->getName() === MfaWebauthnEnableService::WEBAUTHN_CREDENTIAL_REGISTERED_EVENT;
        $emailCollection->addEmail($this->createEmail($user, $isRegistration, $event->getData('name')));

        return $emailCollection;
    }

    /**
     * @param \App\Model\Entity\User $user Recipient
     * @param bool $isRegistration Whether a key was registered (true) or removed (false)
     * @param string|null $name The security key friendly name
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $user, bool $isRegistration, ?string $name): Email
    {
        $localeService = new LocaleService();
        if ($isRegistration) {
            $subject = $localeService->translateString(
                $user->locale,
                function () {
                    return __('A new security key was added to your account.');
                }
            );
            $template = self::TEMPLATE_REGISTERED;
        } else {
            $subject = $localeService->translateString(
                $user->locale,
                function () {
                    return __('A security key was removed from your account.');
                }
            );
            $template = self::TEMPLATE_REMOVED;
        }

        return new Email(
            $user,
            $subject,
            [
                'title' => $subject,
                'body' => ['user' => $user, 'name' => $name],
            ],
            $template
        );
    }
}
