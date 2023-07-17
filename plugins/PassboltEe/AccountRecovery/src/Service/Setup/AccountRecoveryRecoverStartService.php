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

use App\Service\Setup\RecoverStartService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsGetService;

class AccountRecoveryRecoverStartService extends RecoverStartService
{
    /**
     * @inheritDoc
     */
    public function getInfo(string $userId, string $token): array
    {
        $data = parent::getInfo($userId, $token);
        $userSetting = (new AccountRecoveryUserSettingsGetService())->get($userId);
        if ($userSetting) {
            /** @var \App\Model\Entity\User $user */
            $user = $data['user'];
            $user->set('account_recovery_user_setting', ['status' => $userSetting->status]);
        }
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $policy->unset('deleted');
        if ($policy->has('account_recovery_organization_public_key')) {
            $policy->set('account_recovery_organization_public_key', [
                'id' => $policy->account_recovery_organization_public_key->id,
                'armored_key' => $policy->account_recovery_organization_public_key->armored_key,
            ]);
        }
        $data['account_recovery_organization_policy'] = $policy->toArray();

        return $data;
    }
}
