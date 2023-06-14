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
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\ActionLog;

class MfaSortWithLastUsedProviderFirstService
{
    public const SORT_BY_LAST_USAGE_CONFIG_NAME = 'passbolt.plugins.multiFactorAuthentication.sortProvidersByLastUsage';

    /**
     * Cache the result of the query in audit logs
     * ActionLog: the query was already performed and an entry was found
     * False: the query was already performed and no entry were found
     * Null: the query has not been cached yet
     *
     * @var \Passbolt\Log\Model\Entity\ActionLog|false|null
     */
    protected $lastProviderIfFoundOrFalse = null;

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param array $enabledProviders List of providers enabled for the user
     * @return array
     */
    public function sortWithLastUsedProviderFirst(UserAccessControl $uac, array $enabledProviders): array
    {
        if (!Configure::read(MfaSortWithLastUsedProviderFirstService::SORT_BY_LAST_USAGE_CONFIG_NAME)) {
            return $enabledProviders;
        }
        if (count($enabledProviders) < 2) {
            return $enabledProviders;
        }

        $lastProvider = $this->getLastUsedProvider($uac, $enabledProviders);
        if (is_null($lastProvider)) {
            return $enabledProviders;
        }
        $lastProviderKey = array_search($lastProvider, $enabledProviders);
        if ($lastProviderKey === false) {
            return $enabledProviders;
        }

        unset($enabledProviders[$lastProviderKey]);
        array_unshift($enabledProviders, $lastProvider);

        return $enabledProviders;
    }

    /**
     * Provides a list of MFA providers available to a user, query in the audit logs
     * which MFA provider was used last
     *
     * @param \App\Utility\UserAccessControl $uac User action control
     * @param array $enabledProviders List of MFA providers enabled in account settings
     * @return string|null The last MFA providers used in the last 30 days
     */
    protected function getLastUsedProvider(UserAccessControl $uac, array $enabledProviders): ?string
    {
        $actionIds = [];
        foreach ($enabledProviders as $provider) {
            $actionIds[$provider] = UuidFactory::uuid(ucfirst($provider) . 'VerifyGet.get');
        }

        $lastMfaRequestAction = $this->queryAndCacheLastMfaVerifyInActionLogs($uac, $actionIds);

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

    /**
     * @param \App\Utility\UserAccessControl $uac User Access
     * @param array $actionIds Action IDs to search based on the providers available to the user
     * @return \Passbolt\Log\Model\Entity\ActionLog|null
     */
    protected function queryAndCacheLastMfaVerifyInActionLogs(UserAccessControl $uac, array $actionIds): ?ActionLog
    {
        // Check if the query was already performed and the result is cached
        if ($this->lastProviderIfFoundOrFalse instanceof ActionLog) {
            return $this->lastProviderIfFoundOrFalse;
        } elseif ($this->lastProviderIfFoundOrFalse === false) {
            return null;
        }

        /** @var \Passbolt\Log\Model\Entity\ActionLog|null $lastMfaRequestAction */
        $lastMfaRequestAction = TableRegistry::getTableLocator()
            ->get('Passbolt/Log.ActionLogs')
            ->find()
            ->select('ActionLogs.action_id')
            ->where([
                'ActionLogs.user_id' => $uac->getId(),
                'ActionLogs.action_id IN' => $actionIds,
                'ActionLogs.status' => 1,
            ])
            ->orderDesc('ActionLogs.created')
            ->first();

        if (is_null($lastMfaRequestAction)) {
            $this->lastProviderIfFoundOrFalse = false;

            return null;
        }

        $this->lastProviderIfFoundOrFalse = $lastMfaRequestAction;

        return $lastMfaRequestAction;
    }
}
