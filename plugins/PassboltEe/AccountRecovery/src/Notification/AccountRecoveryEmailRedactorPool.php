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

namespace Passbolt\AccountRecovery\Notification;

use App\Notification\Email\AbstractSubscribedEmailRedactorPool;
use Passbolt\AccountRecovery\Notification\OrganizationPolicies\AccountRecoveryOrganizationPolicyDisableEmailRedactor;
use Passbolt\AccountRecovery\Notification\OrganizationPolicies\AccountRecoveryOrganizationPolicyEnableEmailRedactor;
use Passbolt\AccountRecovery\Notification\OrganizationPolicies\AccountRecoveryOrganizationPolicyUpdateEmailRedactor;
use Passbolt\AccountRecovery\Notification\Request\AccountRecoveryGetBadRequestAdminEmailRedactor;
use Passbolt\AccountRecovery\Notification\Request\AccountRecoveryRequestCreatedAdminEmailRedactor;
use Passbolt\AccountRecovery\Notification\Request\AccountRecoveryRequestCreatedUserEmailRedactor;
use Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseApprovedUserEmailRedactor;
use Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseCreatedAdminEmailRedactor;
use Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseCreatedAllAdminsEmailRedactor;
use Passbolt\AccountRecovery\Notification\Response\AccountRecoveryResponseRejectedUserEmailRedactor;

class AccountRecoveryEmailRedactorPool extends AbstractSubscribedEmailRedactorPool
{
    /**
     * @return \App\Notification\Email\SubscribedEmailRedactorInterface[]
     */
    public function getSubscribedRedactors(): array
    {
        $redactors = [];

        if ($this->isRedactorEnabled('send.accountRecovery.request.user')) {
            $redactors[] = new AccountRecoveryRequestCreatedUserEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.request.admin')) {
            $redactors[] = new AccountRecoveryRequestCreatedAdminEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.response.user.approved')) {
            $redactors[] = new AccountRecoveryResponseApprovedUserEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.response.user.rejected')) {
            $redactors[] = new AccountRecoveryResponseRejectedUserEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.response.created.admin')) {
            $redactors[] = new AccountRecoveryResponseCreatedAdminEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.response.created.allAdmins')) {
            $redactors[] = new AccountRecoveryResponseCreatedAllAdminsEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.request.guessing')) {
            $redactors[] = new AccountRecoveryGetBadRequestAdminEmailRedactor();
        }

        if ($this->isRedactorEnabled('send.accountRecovery.policy.update')) {
            $redactors[] = new AccountRecoveryOrganizationPolicyEnableEmailRedactor();
            $redactors[] = new AccountRecoveryOrganizationPolicyDisableEmailRedactor();
            $redactors[] = new AccountRecoveryOrganizationPolicyUpdateEmailRedactor();
        }

        return $redactors;
    }
}
