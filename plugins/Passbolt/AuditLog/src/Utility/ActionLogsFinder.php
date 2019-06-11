<?php
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
use Cake\Datasource\Paginator;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class ActionLogsFinder
{
    /**
     * Get base query
     * @param array $options options
     *
     * @return Query
     */
    protected function _getBaseQuery(array $options = [])
    {
        $ActionLog = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $query = $ActionLog->find();
        $query->group([
            'ActionLogs.id',
            'EntitiesHistory.created'
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
                'PermissionsHistoryUsers.username'
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
            ]
        ]]);
        $query->leftJoinWith('EntitiesHistory.Resources');

        $query->contain(['EntitiesHistory.SecretAccesses.SecretAccessResources' => [
            'fields' => [
                'SecretAccesses.id',
                'SecretAccessResources.id',
                'SecretAccessResources.name'
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
                'SecretsHistoryUsers.username'
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

        return $query;
    }

    /**
     * Filter query by resource id
     * @param Query $query query
     * @param string $resourceId resource id
     *
     * @return Query
     */
    protected function _filterQueryByResourceId(Query $query, string $resourceId)
    {
        $query->where([
            'OR' => [
                'PermissionsHistoryResources.id' => $resourceId,
                'Resources.id' => $resourceId,
                'SecretAccesses.resource_id' => $resourceId,
                'SecretsHistoryResources.id' => $resourceId,
            ]
        ]);

        $query->order([
            'ActionLogs.created' => 'DESC',
            'EntitiesHistory.created' => 'DESC',
        ]);

        return $query;
    }

    /**
     * Paginate results as per the pagination options provided.
     * @param Query $query query
     * @param array $options options
     *
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
     * @param UserAccessControl $user user
     * @param string $resourceId resource id
     * @return bool whether or not he has access to the resource
     */
    protected function _checkUserCanAccessResource(UserAccessControl $user, string $resourceId)
    {
        $Resource = TableRegistry::getTableLocator()->get('Resources');
        $resource = $Resource->findView($user->userId(), $resourceId)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        return $resource;
    }

    /**
     * find action logs for a given resource.
     * @param UserAccessControl $user user
     * @param string $resourceId resource id
     * @param array $options options array
     *
     * @return array
     */
    public function findForResource(UserAccessControl $user, string $resourceId, array $options = [])
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
        $resultParser = new ActionLogResultsParser($actionLogs);
        $res = $resultParser->parse();

        return $res;
    }
}
