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
use App\Model\Entity\WebauthnCredential;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use Cake\Event\Event;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Locale\Service\LocaleService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnDisableService;

class MfaWebauthnCredentialRemovedEmailRedactor implements SubscribedEmailRedactorInterface
{
    use LocatorAwareTrait;
    use SubscribedEmailRedactorTrait;

    public const TEMPLATE = 'Passbolt/MultiFactorAuthentication.LU/mfa_webauthn_credential_removed';

    protected UsersTable $Users;

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        /** @var \App\Model\Table\UsersTable $users */
        $users = $this->fetchTable('Users');
        $this->Users = $users;
    }

    /**
     * @inheritDoc
     */
    public function getNotificationSettingPath(): ?string
    {
        return null;
    }

    /**
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
        ];
    }

    /**
     * @param \Cake\Event\Event $event Event
     * @return \App\Notification\Email\EmailCollection
     */
    public function onSubscribedEvent(Event $event): EmailCollection
    {
        $emails = new EmailCollection();

        /** @var \App\Utility\UserAccessControl $uac */
        $uac = $event->getData('uac');
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = $event->getData('credential');

        /** @var \App\Model\Entity\User|null $user */
        $user = $this->Users->find('locale')
            ->find('notDisabled')
            ->find('activeNotDeleted')
            ->where(['Users.id' => $uac->getId()])
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->first();
        if ($user === null) {
            return $emails;
        }

        $emails->addEmail($this->createEmail($user, $credential));

        return $emails;
    }

    /**
     * @param \App\Model\Entity\User $user Target user.
     * @param \App\Model\Entity\WebauthnCredential $credential Credential removed before this event fired.
     * @return \App\Notification\Email\Email
     */
    private function createEmail(User $user, WebauthnCredential $credential): Email
    {
        $localeService = new LocaleService();
        $subject = $localeService->translateString(
            $user->locale,
            function () {
                return __('An MFA passkey was removed from your account.');
            },
        );
        $title = $localeService->translateString(
            $user->locale,
            function () {
                return __('MFA passkey removed.');
            },
        );

        return new Email(
            $user,
            $subject,
            [
                'title' => $title,
                'body' => [
                    'user' => $user,
                    'credential' => $credential,
                ],
            ],
            self::TEMPLATE,
        );
    }
}
