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
 * @since         4.1.0
 */
namespace Passbolt\MultiFactorAuthentication\Service\ActionLogs;

use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class MfaGetLastUsedProviderService
{
    public const SORT_BY_LAST_USAGE_CONFIG_NAME = 'passbolt.plugins.multiFactorAuthentication.sortProvidersByLastUsage';

    /**
     * Provided a list of MFA providers available to a user, query in the audit logs
     * which MFA provider was used last
     *
     * @param \App\Utility\UserAccessControl $uac User action control
     * @param array $enabledProviders List of MFA providers enabled in account settings
     * @return string|null The last MFA providers used in the last 30 days
     */
    public function getLastUsedOrDefaultProvider(UserAccessControl $uac, array $enabledProviders): ?string
    {
        if (count($enabledProviders) === 0) {
            return null;
        }
        if (count($enabledProviders) === 1) {
            return $enabledProviders[0];
        }
        $actionIds = [];
        foreach ($enabledProviders as $provider) {
            $actionIds[$provider] = UuidFactory::uuid(ucfirst($provider) . 'VerifyGet.get');
        }

        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        /** @var \Passbolt\Log\Model\Entity\ActionLog|null $lastMfaRequestAction */
        $lastMfaRequestAction = $ActionLogs
            ->find()
            ->select('action_id')
            ->where([
                'ActionLogs.user_id' => $uac->getId(),
                'ActionLogs.action_id IN' => $actionIds,
                'ActionLogs.status' => 1,
            ])
            ->orderDesc('created')
            ->first();

        if (is_null($lastMfaRequestAction)) {
            return null;
        }

        /** @var string|false $lastProvider */
        $lastProvider = array_search($lastMfaRequestAction->action_id, $actionIds);
        if ($lastProvider === false) {
            return null;
        }

        return $lastProvider;
    }
}
