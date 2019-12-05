<?php
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
 * @since         2.12.0
 */
namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Notification\Email\SubscribedEmailRedactorTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Controller\UserSettings\MfaUserSettingsDeleteController;

class MfaUserSettingsResetEmailRedactor implements SubscribedEmailRedactorInterface
{
    use SubscribedEmailRedactorTrait;

    const TEMPLATE_SELF = 'Passbolt/MultiFactorAuthentication.LU/mfa_user_settings_reset_self';
    const TEMPLATE_ADMIN = 'Passbolt/MultiFactorAuthentication.LU/mfa_user_settings_reset_admin';

    /**
     * @var UsersTable
     */
    private $usersTable;

    /**
     * MfaUserSettingsResetEmailRedactor constructor.
     * @param UsersTable $usersTable user table
     */
    public function __construct(UsersTable $usersTable)
    {
        $this->usersTable = $usersTable;
    }

    /**
     * @param User $user user who got their settings deleted
     * @param UserAccessControl $uac of user who deleted the settings
     * @return Email
     */
    public function createEmail(User $user, UserAccessControl $uac)
    {
        if ($user->id !== $uac->getId()) {
            return $this->createEmailAdminDelete($user, $uac);
        }

        return $this->createEmailSelfDelete($user);
    }

    /**
     * @param User $user user who deleted their own settings
     * @return Email
     */
    private function createEmailSelfDelete(User $user)
    {
        return new Email(
            $user->username,
            $subject = __('Your multi-factor authentication settings were reset by you.'),
            [
                'title' => __('Multi-factor authentication settings were reset.'),
                'body' => ['user' => $user]
            ],
            self::TEMPLATE_SELF
        );
    }

    /**
     * @param User $user user who got their settings deleted
     * @param UserAccessControl $uac of user who deleted the settings
     * @return Email
     */
    private function createEmailAdminDelete(User $user, UserAccessControl $uac)
    {
        $admin = $this->usersTable->findFirstForEmail($uac->getId());

        return new Email(
            $user->username,
            $subject = __('Your multi-factor authentication settings were reset by an administrator.'),
            [
                'title' => __('Multi-factor authentication settings were reset.'),
                'body' => ['user' => $admin]
            ],
            self::TEMPLATE_ADMIN
        );
    }

    /**
     * @param Event $event user settings reset event
     * @return EmailCollection
     */
    public function onSubscribedEvent(Event $event)
    {
        $emailCollection = new EmailCollection();
        $email = $this->createEmail($event->getData('target'), $event->getData('uac'));

        return $emailCollection->addEmail($email);
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            MfaUserSettingsDeleteController::MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT
        ];
    }
}
