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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.0
 */

namespace Passbolt\AuditLog\Utility;

use App\Model\Table\AvatarsTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

abstract class BaseActionLogsFinder
{
    /**
     * Find action logs for a given entity
     *
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param string $entityId entity id
     * @param array|null $options options array
     * @return \Cake\ORM\Query
     */
    abstract public function find(UserAccessControl $uac, string $entityId, ?array $options = []): Query;

    /**
     * @var \Passbolt\Log\Model\Table\ActionLogsTable
     */
    protected $ActionLogs;

    /**
     * ActionLogsFinder constructor.
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
    }

    /**
     * Get base query
     *
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    protected function _getBaseQuery(array $options = []): Query
    {
        $query = $this->ActionLogs->find();
        $query->group([
            'ActionLogs.id',
            'Actions.name',
        ]);

        $query->contain(['Actions' => [
            'fields' => ['Actions.name']]]);

        $this->joinUser($query);

        $query->contain(['EntitiesHistory.PermissionsHistory' => [
            'fields' => [
                'PermissionsHistory.id',
                'PermissionsHistory.type',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.PermissionsHistory');

        $query->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryUsers' => [
            'fields' => [
                'PermissionsHistoryUsers.id',
                'PermissionsHistoryUsers.username',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryUsers');

        $query->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryResources' => [
            'fields' => [
                'PermissionsHistoryResources.id',
                'PermissionsHistoryResources.name',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryResources');

        $query->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryGroups' => [
            'fields' => [
                'PermissionsHistoryGroups.id',
                'PermissionsHistoryGroups.name',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryGroups');

        $query->contain(['EntitiesHistory.Resources' => [
            'fields' => [
                'Resources.id',
                'Resources.name',
            ],
        ]]);
        $query->leftJoinWith('EntitiesHistory.Resources');

        $query->contain(['EntitiesHistory.SecretAccesses.SecretAccessResources' => [
            'fields' => [
                'SecretAccesses.id',
                'SecretAccessResources.id',
                'SecretAccessResources.name',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.SecretAccesses.SecretAccessResources');

        $query->contain(['EntitiesHistory.SecretsHistory' => [
            'fields' => [
                'SecretsHistory.id',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.SecretsHistory');

        $query->contain(['EntitiesHistory.SecretsHistory.SecretsHistoryUsers' => [
            'fields' => [
                'SecretsHistoryUsers.id',
                'SecretsHistoryUsers.username',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.SecretsHistory.SecretsHistoryUsers');

        $query->contain(['EntitiesHistory.SecretsHistory.SecretsHistoryResources' => [
            'fields' => [
                'SecretsHistoryResources.id',
                'SecretsHistoryResources.name',
            ]]]);
        $query->leftJoinWith('EntitiesHistory.SecretsHistory.SecretsHistoryResources');

        $query->where([
            'ActionLogs.status' => 1]);

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $query->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryFolders' => [
                'fields' => [
                    'PermissionsHistoryFolders.id',
                    'PermissionsHistoryFolders.name',
                ]]]);
            $query->leftJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryFolders');

            $query->contain(['EntitiesHistory.FoldersHistory' => [
                'fields' => [
                    'FoldersHistory.folder_id',
                    'FoldersHistory.name',
                ]]]);
            $query->leftJoinWith('EntitiesHistory.FoldersHistory');
        }

        return $query;
    }

    /**
     * Contain the user associated of the action log
     *
     * @param \Cake\ORM\Query $query Query
     * @return void
     */
    public function joinUser(Query $query): void
    {
        $query->contain(['Users' => [
            'fields' => [
                'Users.id',
                'Users.username']]]);

        $query->contain(['Users.Profiles' => [
            'Avatars' => [
                'queryBuilder' => AvatarsTable::addContainAvatar()['Avatars'],
                'fields' => [
                    'Avatars.id',
                    'Avatars.profile_id',
                ],
            ],
            'fields' => [
                'Profiles.first_name',
                'Profiles.last_name']]]);

        $query->innerJoinWith('Users.Profiles');
    }
}
