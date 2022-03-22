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

namespace Passbolt\AccountRecovery\Notification\OrganizationPolicies;

use App\Model\Entity\User;
use App\Notification\Email\Email;
use App\Notification\Email\SubscribedEmailRedactorInterface;
use App\Utility\Purifier;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AbstractAccountRecoveryOrganizationPolicySetService; // phpcs:ignore
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\Locale\Service\LocaleService;

class AccountRecoveryOrganizationPolicyDisableEmailRedactor extends AbstractAccountRecoveryOrganizationPolicyEmailRedactor implements SubscribedEmailRedactorInterface // phpcs:ignore
{
    public const EMAIL_TEMPLATE = 'Passbolt/AccountRecovery.OrganizationPolicies/disable';

    /**
     * Return the list of events to which the redactor is subscribed and when it must create emails to be sent.
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            AbstractAccountRecoveryOrganizationPolicySetService::AFTER_DISABLE_POLICY_EVENT,
        ];
    }

    /**
     * @param \App\Model\Entity\User $admin Admin receiving the mail
     * @param \App\Model\Entity\User $user User making the action
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy Account recovery request initiated by the user
     * @return \App\Notification\Email\Email
     */
    protected function makeAdminEmail(User $admin, User $user, AccountRecoveryOrganizationPolicy $policy): Email
    {
        $locale = (new GetUserLocaleService())->getLocale($admin->username);
        $subject = (new LocaleService())->translateString(
            $locale,
            function () use ($admin, $user) {
                if ($admin->id === $user->id) {
                    return __('You have disabled the account recovery.');
                }

                return __(
                    '{0} has disabled the account recovery.',
                    Purifier::clean($user->profile->first_name)
                );
            }
        );

        $data = ['body' => [
            'user' => $user,
            'admin' => $admin,
            'created' => $policy->created,
            'subject' => $subject,
        ], 'title' => $subject,];

        return new Email($admin->username, $subject, $data, self::EMAIL_TEMPLATE);
    }
}
