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

namespace Passbolt\AccountRecovery\Service\Setup;

use App\Service\Setup\SetupStartService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;

/**
 * @property \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens
 * @property \App\Model\Table\UsersTable $Users
 */
class AccountRecoverySetupStartService extends SetupStartService
{
    /**
     * @inheritDoc
     */
    public function getInfo(string $userId, string $token): array
    {
        $data = parent::getInfo($userId, $token);
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $data['account_recovery_organization_policy'] = $policy;

        return $data;
    }
}
