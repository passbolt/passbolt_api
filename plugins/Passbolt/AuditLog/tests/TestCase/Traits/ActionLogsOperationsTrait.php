<?php
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
 */
namespace Passbolt\AuditLog\Test\TestCase\Traits;

use App\Utility\UserAccessControl;
use App\Utility\UserAction;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Log\Model\Entity\EntityHistory;

trait ActionLogsOperationsTrait
{
    /**
     * Simulate a share operation from the perspective of action logs and history tables.
     * @param UserAccessControl $user user access control
     * @param string $aco aco
     * @param string $acoKey aco key
     * @param string $aro aro
     * @param string $aroKey aro key
     * @param string $crud crud
     * @return void
     */
    public function simulateShare(UserAccessControl $user, string $aco, string $acoKey, string $aro, string $aroKey, string $crud, int $permissionType)
    {
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $EntitiesHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $PermissionsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.PermissionsHistory');
        $userAction = UserAction::getInstance($user, 'share.share', 'PUT share/share');

        $ActionLogs->create($userAction, 1);

        $permissionHistory = [
            'id' => UuidFactory::uuid(),
            'aro' => $aro,
            'aco' => $aco,
            'aro_foreign_key' => $aroKey,
            'aco_foreign_key' => $acoKey,
            'type' => $permissionType,
        ];
        $permissionHistoryCreated = $PermissionsHistory->create($permissionHistory);

        $entityHistory = [
            'foreign_model' => 'PermissionsHistory',
            'foreign_key' => $permissionHistoryCreated->id,
            'crud' => $crud,
        ];
        $EntitiesHistory->create($entityHistory, $userAction);
    }

    /**
     * Simulate resources crud operation.
     * @param UserAccessControl $user user access control
     * @param string $resourceId resource id
     * @param string $crud crud
     * @return void
     */
    public function simulateResourceCrud(UserAccessControl $user, string $resourceId, string $crud)
    {
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $EntitiesHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');

        if ($crud == EntityHistory::CRUD_CREATE) {
            $userAction = UserAction::getInstance($user, 'Resources.add', 'POST resources');
        } else {
            $userAction = UserAction::getInstance($user, 'Resources.update', 'PUT resources');
        }

        $ActionLogs->create($userAction, 1);

        $entityHistory = [
            'foreign_model' => 'Resources',
            'foreign_key' => $resourceId,
            'crud' => $crud,
        ];
        $EntitiesHistory->create($entityHistory, $userAction);
    }

    /**
     * Simulate resource secret update operation.
     * @param UserAccessControl $user user
     * @param string $resourceId resource id
     * @return void
     */
    public function simulateResourceSecretUpdate(UserAccessControl $user, string $resourceId)
    {
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $EntitiesHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $SecretsHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.SecretsHistory');
        $userAction = UserAction::getInstance($user, 'Resources.update', 'PUT /resources/' . $resourceId . '.json');

        $ActionLogs->create($userAction, 1);

        $secretsHistory = [
            'id' => UuidFactory::uuid('secret.resource.id.' . $resourceId),
            'resource_id' => $resourceId,
            'user_id' => $user->userId(),
        ];
        $sh = $SecretsHistory->create($secretsHistory);

        $entityHistory = [
            'foreign_model' => 'SecretsHistory',
            'foreign_key' => $sh->id,
            'crud' => EntityHistory::CRUD_UPDATE,
        ];
        $EntitiesHistory->create($entityHistory, $userAction);
    }

    /**
     * Simulate multiple resource get with secrets.
     * @param UserAccessControl $user user
     * @param array $resourceIds resource ids
     * @return void
     * @throws \Exception in case the secret cannot be retrieved.
     */
    public function simulateMultipleResourceGetWithSecrets(UserAccessControl $user, array $resourceIds)
    {
        $ActionLogs = TableRegistry::getTableLocator()->get('Passbolt/Log.ActionLogs');
        $EntitiesHistory = TableRegistry::getTableLocator()->get('Passbolt/Log.EntitiesHistory');
        $SecretAccesses = TableRegistry::getTableLocator()->get('Passbolt/Log.SecretAccesses');
        $Secrets = TableRegistry::getTableLocator()->get('Secrets');

        $userAction = UserAction::getInstance($user, 'ResourcesIndex.index', 'GET /resources/.json');
        $ActionLogs->create($userAction, 1);

        foreach ($resourceIds as $resourceId) {
            $secret = $Secrets->find()->where([
                'resource_id' => $resourceId,
                'user_id' => $user->getId(),
            ])->first();

            if (!$secret) {
                throw new \Exception('Could not retrieve the secret for the given resource and user');
            }

            $sa = $SecretAccesses->create($secret, $user);

            $entityHistory = [
                'foreign_model' => 'SecretAccesses',
                'foreign_key' => $sa->id,
                'crud' => EntityHistory::CRUD_CREATE,
            ];
            $EntitiesHistory->create($entityHistory, $userAction);
        }
    }
}
