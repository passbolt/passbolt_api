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
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Utility;

use App\Model\Table\AvatarsTable;
use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Datasource\Paginator;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class ActionLogsFinder
{
    /**
     * @var \Passbolt\Log\Model\Table\ActionLogsTable
     */
    private $ActionLogs;

    /**
     * ActionLogsFinder constructor.
     */
    public function __construct()
    {
        $this->ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
    }

    /**
     * Get base query
     *
     * @param array $options options
     * @return \Cake\ORM\Query
     */
    protected function _getBaseQuery(array $options = [])
    {
        $ActionLog = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $query = $ActionLog->find();
        $query->group([
            'ActionLogs.id',
        ]);

        $query->contain(['Actions' => [
            'fields' => ['Actions.name']]]);

        $query->contain(['Users' => [
                'fields' => [
                    'Users.id',
                    'Users.username']]]);

        $query->contain(['Users.Profiles' => [
                'Avatars' => [
                    'queryBuilder' => AvatarsTable::addContainAvatar()['Avatars'],
                    'fields' => [
                        'Avatars.id',
                        'Avatars.model',
                        'Avatars.extension',
                        'Avatars.path',
                    ],
                ],
                'fields' => [
                    'Profiles.first_name',
                    'Profiles.last_name']]]);

        $query->innerJoinWith('Users.Profiles');

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
     * Find ActionLog ids for a given PermissionsHistory resource id
     *
     * @param string $resourceId resource id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForPermissionHistoryResources(string $resourceId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.PermissionsHistory'])
            ->innerJoinWith('EntitiesHistory.PermissionsHistory')
            ->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryResources'])
            ->innerJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryResources')
            ->where([
                'PermissionsHistoryResources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Find ActionLog ids for a given resource id
     *
     * @param string $resourceId resource id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResources(string $resourceId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.Resources'])
            ->innerJoinWith('EntitiesHistory.Resources')
            ->where([
                'Resources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Find ActionLog ids for a given SecretAccess resource id
     *
     * @param string $resourceId resource id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResourcesSecretAccesses(string $resourceId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.SecretAccesses'])
            ->innerJoinWith('EntitiesHistory.SecretAccesses')
            ->where([
                'SecretAccesses.resource_id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Find ActionLog ids for a given Secret History resource id
     *
     * @param string $resourceId resource id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResourcesSecretHistory(string $resourceId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.SecretsHistory'])
            ->innerJoinWith('EntitiesHistory.SecretsHistory')
            ->contain(['EntitiesHistory.SecretsHistory.SecretsHistoryResources'])
            ->innerJoinWith('EntitiesHistory.SecretsHistory.SecretsHistoryResources')
            ->where([
                'SecretsHistoryResources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Find ActionLog ids for a given FolderHistory folder id
     *
     * @param string $folderId folder id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForFolders(string $folderId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.FoldersHistory'])
            ->innerJoinWith('EntitiesHistory.FoldersHistory')
            ->where([
                'FoldersHistory.folder_id' => $folderId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Find ActionLog ids for a given PermissionHistory folder id
     *
     * @param string $folderId folder id
     * @return array|\Cake\ORM\Query query
     */
    protected function _findActionLogIdsForPermissionsHistoryFolders(string $folderId)
    {
        $q = $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id'])
            ->contain(['EntitiesHistory.PermissionsHistory'])
            ->innerJoinWith('EntitiesHistory.PermissionsHistory')
            ->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryFolders'])
            ->innerJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryFolders')
            ->where([
                'PermissionsHistoryFolders.id' => $folderId,
                'ActionLogs.status' => 1,
            ])
            ->group('ActionLogs.id');

        return $q;
    }

    /**
     * Filter query by resource id
     *
     * @param \Cake\ORM\Query $query query
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query
     */
    protected function _filterQueryByResourceId(Query $query, string $resourceId)
    {
        $subQuery = $this->_findActionLogIdsForPermissionHistoryResources($resourceId)
            ->union($this->_findActionLogIdsForResources($resourceId))
            ->union($this->_findActionLogIdsForResourcesSecretAccesses($resourceId))
            ->union($this->_findActionLogIdsForResourcesSecretHistory($resourceId));

        $query->join([
            'resourceActionLogs' => [
                'table' => $subQuery,
                'alias' => 'resourceActionLogs',
                'type' => 'INNER',
                'conditions' => ['resourceActionLogs.ActionLogs__id = ActionLogs.id'],
            ],
        ]);

        $query->order([
            'ActionLogs.created' => 'DESC',
        ]);

        return $query;
    }

    /**
     * Filter a query by folder id
     *
     * @param \Cake\ORM\Query $query The target query
     * @param string $folderId The target folder
     * @return \Cake\ORM\Query
     */
    protected function _filterQueryByFolderId(Query $query, string $folderId)
    {
        $subQuery = $this->_findActionLogIdsForFolders($folderId)
            ->union($this->_findActionLogIdsForPermissionsHistoryFolders($folderId));

        $query->join([
            'folderActionLogs' => [
                'table' => $subQuery,
                'alias' => 'folderActionLogs',
                'type' => 'INNER',
                'conditions' => ['folderActionLogs.ActionLogs__id = ActionLogs.id'],
            ],
        ]);

        $query->order([
            'ActionLogs.created' => 'DESC',
        ]);

        return $query;
    }

    /**
     * Paginate results as per the pagination options provided.
     *
     * @param \Cake\ORM\Query $query query
     * @param array $options options
     * @return mixed
     */
    protected function _paginate(Query $query, array $options)
    {
        $paginator = new Paginator();
        $paginator->paginate($query, $options);

        return $query;
    }

    /**
     * Check if a given user has access to a resource.
     *
     * @param \App\Utility\UserAccessControl $uac user
     * @param string $resourceId resource id
     * @return bool whether or not he has access to the resource
     */
    protected function _checkUserCanAccessResource(UserAccessControl $uac, string $resourceId)
    {
        $Resource = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resource->findView($uac->getId(), $resourceId)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        return $resource;
    }

    /**
     * find action logs for a given resource.
     *
     * @param \App\Utility\UserAccessControl $user user
     * @param string $resourceId resource id
     * @param array $options options array
     * @return array
     */
    public function findForResource(UserAccessControl $user, string $resourceId, ?array $options = [])
    {
        // Check that user can access to resource.
        $this->_checkUserCanAccessResource($user, $resourceId);

        // Build query.
        $q = $this->_getBaseQuery();
        $q = $this->_filterQueryByResourceId($q, $resourceId);
        if (!empty($options)) {
            $q = $this->_paginate($q, $options);
        }
        $actionLogs = $q->all();
        $resultParser = new ActionLogResultsParser($actionLogs, ['resources' => [$resourceId]]);
        $res = $resultParser->parse();

        return $res;
    }

    /**
     * find action logs for a given folder.
     *
     * @param \App\Utility\UserAccessControl $user user
     * @param string $folderId resource id
     * @param array $options options array
     * @return array
     */
    public function findForFolder(UserAccessControl $user, string $folderId, ?array $options = [])
    {
        if (!Configure::read('passbolt.plugins.folders.enabled')) {
            return [];
        }

        // Check that the folder exists and is accessible.
        $Folders = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
        $folder = $Folders->findView($user->getId(), $folderId, $options)->first();

        if (empty($folder)) {
            throw new NotFoundException('The folder does not exist.');
        }

        // Build query.
        $q = $this->_getBaseQuery();
        $q = $this->_filterQueryByFolderId($q, $folderId);
        if (!empty($options)) {
            $q = $this->_paginate($q, $options);
        }
        $actionLogs = $q->all();
        $resultParser = new ActionLogResultsParser($actionLogs, ['folders' => [$folderId]]);
        $res = $resultParser->parse();

        return $res;
    }
}
