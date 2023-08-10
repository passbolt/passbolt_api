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

use App\Model\Entity\Resource;
use App\Utility\UserAccessControl;
use Cake\Database\Expression\IdentifierExpression;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class ResourceActionLogsFinder extends BaseActionLogsFinder
{
    /**
     * Find ActionLog ids for a given PermissionsHistory resource id
     *
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query query
     */
    protected function _findActionLogIdsForPermissionHistoryResources(string $resourceId): Query
    {
        return $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id', 'Actions__name' => 'Actions.name'])
            ->contain(['EntitiesHistory.PermissionsHistory'])
            ->innerJoinWith('Actions')
            ->innerJoinWith('EntitiesHistory.PermissionsHistory')
            ->contain(['EntitiesHistory.PermissionsHistory.PermissionsHistoryResources'])
            ->innerJoinWith('EntitiesHistory.PermissionsHistory.PermissionsHistoryResources')
            ->where([
                'PermissionsHistoryResources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group(['ActionLogs.id', 'Actions.name']);
    }

    /**
     * Find ActionLog ids for a given resource id
     *
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResources(string $resourceId): Query
    {
        return $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id', 'Actions__name' => 'Actions.name'])
            ->contain(['EntitiesHistory.Resources'])
            ->innerJoinWith('Actions')
            ->innerJoinWith('EntitiesHistory.Resources')
            ->where([
                'Resources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group(['ActionLogs.id', 'Actions.name']);
    }

    /**
     * Find ActionLog ids for a given SecretAccess resource id
     *
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResourcesSecretAccesses(string $resourceId): Query
    {
        return $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id', 'Actions__name' => 'Actions.name'])
            ->contain(['EntitiesHistory.SecretAccesses'])
            ->innerJoinWith('Actions')
            ->innerJoinWith('EntitiesHistory.SecretAccesses')
            ->where([
                'SecretAccesses.resource_id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group(['ActionLogs.id', 'Actions.name']);
    }

    /**
     * Find ActionLog ids for a given Secret History resource id
     *
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query query
     */
    protected function _findActionLogIdsForResourcesSecretHistory(string $resourceId): Query
    {
        return $this->ActionLogs
            ->find()
            ->select(['ActionLogs__id' => 'ActionLogs.id', 'Actions__name' => 'Actions.name'])
            ->contain(['EntitiesHistory.SecretsHistory'])
            ->innerJoinWith('Actions')
            ->innerJoinWith('EntitiesHistory.SecretsHistory')
            ->contain(['EntitiesHistory.SecretsHistory.SecretsHistoryResources'])
            ->innerJoinWith('EntitiesHistory.SecretsHistory.SecretsHistoryResources')
            ->where([
                'SecretsHistoryResources.id' => $resourceId,
                'ActionLogs.status' => 1,
            ])
            ->group(['ActionLogs.id', 'Actions.name']);
    }

    /**
     * Filter query by resource id
     *
     * @param \Cake\ORM\Query $query query
     * @param string $resourceId resource id
     * @return \Cake\ORM\Query
     */
    protected function _filterQueryByResourceId(Query $query, string $resourceId): Query
    {
        $subQuery = $this->_findActionLogIdsForPermissionHistoryResources($resourceId)
            ->union($this->_findActionLogIdsForResources($resourceId))
            ->union($this->_findActionLogIdsForResourcesSecretAccesses($resourceId))
            ->union($this->_findActionLogIdsForResourcesSecretHistory($resourceId));

        return $query->join([
            'resourceActionLogs' => [
                'table' => $subQuery,
                'alias' => 'resourceActionLogs',
                'type' => 'INNER',
                'conditions' => ['resourceActionLogs.ActionLogs__id' => new IdentifierExpression('ActionLogs.id')],
            ],
        ]);
    }

    /**
     * Check if a given user has access to a resource.
     *
     * @param \App\Utility\UserAccessControl $uac user
     * @param string $resourceId resource id
     * @return Resource whether or not he has access to the resource
     */
    protected function _checkUserCanAccessResource(UserAccessControl $uac, string $resourceId): Resource
    {
        /** @var \App\Model\Table\ResourcesTable $Resource */
        $Resource = TableRegistry::getTableLocator()->get('Resources');
        /** @var Resource|null $resource */
        $resource = $Resource->findView($uac->getId(), $resourceId)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        return $resource;
    }

    /**
     * @inheritDoc
     */
    public function find(UserAccessControl $uac, string $entityId, ?array $options = []): Query
    {
        // Check that user can access to resource.
        $this->_checkUserCanAccessResource($uac, $entityId);

        // Build query.
        $q = $this->_getBaseQuery();

        return $this->_filterQueryByResourceId($q, $entityId);
    }
}
