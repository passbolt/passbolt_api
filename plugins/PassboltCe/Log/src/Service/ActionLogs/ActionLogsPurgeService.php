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
 * @since         4.8.0
 */
namespace Passbolt\Log\Service\ActionLogs;

use App\Utility\UuidFactory;
use Cake\Collection\CollectionInterface;
use Cake\Core\Configure;
use Cake\I18n\FrozenDate;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\ActionLog;

class ActionLogsPurgeService
{
    /**
     * Purge action logs.
     *
     * @param int $retentionInDays retention in days
     * @param int $limit Maximum number of rows to purge.
     * @return int
     */
    public function purge(int $retentionInDays, int $limit): int
    {
        $ActionLogsTable = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');

        /**
         * We cannot delete with `IN` clause with a subquery for MySQL. However, the approach works for Postgres and MariaDB.
         * Therefore, in case of a PDOException thrown by MySQL, we use a less performant approach with NOT IN on entity histories.
         */
        try {
            return $ActionLogsTable->deleteAll([
                'ActionLogs.id IN' => $this->getActionLogsToPurge($retentionInDays)->select('id')->limit($limit),
            ]);
        } catch (\PDOException $exception) {
            $createdBefore = FrozenDate::now()->subDays($retentionInDays);
            $entitiesHistory = $ActionLogsTable->getAssociation('EntitiesHistory')
                ->subquery()
                ->select('EntitiesHistory.action_log_id')
                ->where(['EntitiesHistory.created <' => $createdBefore]);

            return $ActionLogsTable->deleteQuery()
                ->whereInList('ActionLogs.action_id', array_keys($this->getActionUuidsToPurge()))
                ->where([
                    'ActionLogs.id NOT IN' => $entitiesHistory,
                    'ActionLogs.created < ' => $createdBefore,
                ])
                ->epilog("LIMIT {$limit}")
                ->execute()
                ->rowCount();
        }
    }

    /**
     * Dry run of the purge
     *
     * @param int $retentionInDays retention in days
     * @return \Cake\ORM\Query
     */
    public function dryRun(int $retentionInDays): Query
    {
        $total = $this->getActionLogsToPurge($retentionInDays)
            ->select([
                'count' => 'COUNT(*)',
                'action_id',
            ])
            ->group('ActionLogs.action_id')
            ->orderDesc('count');

        $total->formatResults(function (CollectionInterface $results) {
            $actionsToPurge = $this->getActionUuidsToPurge();
            $results = $results->map(function (ActionLog $entity) use ($actionsToPurge): ActionLog {
                $entity->set('action', $actionsToPurge[$entity->action_id] ?? null);

                return $entity;
            });
            $totalCount = $results->sumOf('count');

            return $results->appendItem([
                'count' => $totalCount,
                'action' => 'Total',
            ]);
        });

        return $total;
    }

    /**
     * @param int $retentionInDays retention in days
     * @return \Cake\ORM\Query
     */
    private function getActionLogsToPurge(int $retentionInDays): Query
    {
        $createdBefore = FrozenDate::now()->subDays($retentionInDays);
        $ActionLogsTable = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');

        return $ActionLogsTable->find()
            ->leftJoinWith('EntitiesHistory')
            ->whereInList('ActionLogs.action_id', array_keys($this->getActionUuidsToPurge()))
            ->whereNull('EntitiesHistory.id')
            ->where(['ActionLogs.created < ' => $createdBefore]);
    }

    /**
     * @return array
     */
    private function getActionUuidsToPurge(): array
    {
        $uuids = [];
        foreach ($this->getActionList() as $action) {
            $uuids[UuidFactory::uuid($action)] = $action;
        }

        return $uuids;
    }

    /**
     * List of actions to be purged
     *
     * @return string[]
     */
    public function getActionList(): array
    {
        $blacklistedActions = Configure::read(ActionLogsCreateService::LOG_CONFIG_BLACKLIST_CONFIG_KEY, []);

        return array_merge($blacklistedActions, [
            'shell',
            'AccountRecoveryOrganizationPoliciesGet.get',
            'AccountSettingsIndex.index',
            'AuthLogin.loginGet',
            'AuthVerify.verifyGet',
            'CommentsView.view',
            'DirectorySettings.view',
            'FolderLogs.view',
            'FoldersIndex.index',
            'FoldersView.view',
            'GetCsrfToken.get',
            'GpgkeysIndex.index',
            'GroupsView.view',
            'GroupsIndex.index',
            'HealthcheckIndex.index',
            'Home.apiExtApp',
            'Home.apiApp',
            'Home.view',
            'MfaOrgSettingsGet.get',
            'MfaSetupSelectProvider.get',
            'NotificationOrgSettingsGet.get',
            'PasswordGeneratorSettings.index',
            'PermissionsView.viewAcoPermissions',
            'ResourceLogs.view',
            'ResourceTypesIndex.index',
            'ResourceTypesView.view',
            'ResourcesIndex.index',
            'ResourcesView.view',
            'RolesIndex.index',
            'SettingsIndex.index',
            'Share.dryRun',
            'ShareSearch.searchArosToShareWith',
            'SubscriptionsView.view',
            'TagsIndex.index',
            'TotpSetupGet.get',
            'TotpVerifyGet.get',
            'ThemesIndex.index',
            'UserLogs.viewByFolder',
            'UserLogs.viewByResource',
            'UsersIndex.index',
            'UsersView.view',
            'MfaVerifyAjaxError.get',
            'MfaPoliciesSettingsGet.get',
            'MfaSetupSelectProvider.get',
            'AccountRecoveryOrganizationPoliciesGet.get',
        ]);
    }
}
