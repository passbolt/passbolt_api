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
 * @since         4.5.0
 */

namespace Passbolt\Log\Strategy;

use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\ORM\Locator\LocatorAwareTrait;
use Exception;
use Passbolt\Log\Model\Entity\ActionLog;

/**
 * This strategy was developed for SIEM action logging
 *
 * In your configuration, e.g config/passbolt.php
 *
 *  'Log' => [
 *      'actionLogsOnFile' => [
 *          'enabled' => true,
 *          'strategy' => \Passbolt\Log\Strategy\ActionLogsUsernameQueryStrategy::class,
 *      ],
 *  ],
 */
class ActionLogsUsernameQueryStrategy extends ActionLogsAbstractQueryStrategy
{
    use LocatorAwareTrait;

    protected array $actionConfig = [
        'ResourcesView.view' => ['name' => 'password_access', 'details' => ' accessed password'],
        'SecretsView.view' => ['name' => 'password_access', 'details' => ' accessed password'],
        'ResourcesAdd.add' => ['name' => 'password_add', 'details' => ' created password'],
        'ResourcesDelete.delete' => ['name' => 'password_delete', 'details' => ' deleted password'],
        'ResourcesUpdate.update' => ['name' => 'password_update', 'details' => ' updated password'],
        'AuthLogout.logout' => ['name' => 'user_logout', 'details' => ' logout'],
        'AuthLogin.loginPost' => ['name' => 'user_login', 'details' => ' login'],
        'Share.share' => ['name' => 'share', 'details' => ' shared password'],
    ];

    /**
     * @inheritDoc
     */
    public function query(ActionLog $actionLog): string|false
    {
        try {
            // Get the action name
            $actionName = $this->getActionName($actionLog->action_id);

            // Check if the action is in the list of allowed actions and if there is a user_id associated with the action_logs
            if (!isset($this->actionConfig[$actionName]) || !isset($actionLog->user_id)) {
                return false;
            }

            // Get user details
            $userDetails = $this->getUserDetails($actionLog->user_id);

            // Initialize additional context
            $itemDetails = $this->actionConfig[$actionName]['details'];
            $extraDetails = [];

            // Handle actions based on the action name
            switch ($actionName) {
                case 'ResourcesView.view':
                case 'SecretsView.view':
                case 'ResourcesAdd.add':
                case 'ResourcesDelete.delete':
                case 'ResourcesUpdate.update':
                    $extraDetails = $this->getResourceDetails($actionLog->id);
                    break;
                case 'Share.share':
                    $extraDetails = $this->handleShareAction($actionLog->id);
                    break;
                case 'AuthLogout.logout':
                case 'AuthLogin.loginPost':
                    // No extra details needed for login/logout
                    break;
                default:
                    // If the action is not recognized, return false
                    return false;
            }

            // Get human-readable action name
            $humanReadableActionName = $this->actionConfig[$actionName]['name'] ?? $actionName;

            // Format the log entry
            $formattedLog = array_merge([
                'timestamp' => Time::parse($actionLog->created)->i18nFormat('yyyy-MM-dd HH:mm:ss'),
                'user' => $userDetails['username'],
                'action' => $humanReadableActionName,
                'context' => $userDetails['fullName'] . $itemDetails,
                'status' => $actionLog->status,
            ], $extraDetails);

            return json_encode($formattedLog);
        } catch (Exception $e) {
            $msg = 'Error in ActionLogsUsernameQueryStrategy: ';
            $msg .= $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine();
            Log::error($msg);

            return false;
        }
    }

    /**
     * Get the action name for a given action ID.
     *
     * @param string $actionId action ID
     * @return string
     */
    protected function getActionName(string $actionId): string
    {
        /** @var \Passbolt\Log\Model\Table\ActionsTable $actionsTable */
        $actionsTable = $this->fetchTable('Actions');
        $action = $actionsTable->get($actionId);

        return $action->name;
    }

    /**
     * Get user details for a given user ID.
     *
     * @param string $userId user ID
     * @return array
     */
    protected function getUserDetails(string $userId): array
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Entity\User $user */
        $user = $usersTable->findById($userId)->contain('Profiles')->firstOrFail();

        return [
            'username' => $user->username,
            'fullName' => $user->profile->first_name . ' ' . $user->profile->last_name . ' (' . $user->username . ')',
        ];
    }

    /**
     * Get resource details from entities history for a given action log ID and model.
     *
     * @param string $actionLogId action log ID
     * @return array
     */
    protected function getResourceDetails(string $actionLogId): array
    {
        /** @var \Passbolt\Log\Model\Table\EntitiesHistoryTable $entitiesHistoryTable */
        $entitiesHistoryTable = $this->fetchTable('EntitiesHistory');
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');
        /** @var \Passbolt\Log\Model\Table\SecretAccessesTable $secretAccessesTable */
        $secretAccessesTable = $this->fetchTable('SecretAccesses');

        /** @var \Passbolt\Log\Model\Entity\EntityHistory $entityHistory */
        $entityHistory = $entitiesHistoryTable->find()
            ->where(['action_log_id' => $actionLogId])
            ->firstOrFail();

        // Determine the resource ID based on the foreign model
        if ($entityHistory->foreign_model === 'Resources') {
            $resourceId = $entityHistory->foreign_key;
        } elseif ($entityHistory->foreign_model === 'SecretAccesses') {
            $secretAccess = $secretAccessesTable->get($entityHistory->foreign_key);
            $resourceId = $secretAccess->resource_id;
        } else {
            throw new Exception('Unsupported foreign_model: ' . $entityHistory->foreign_model);
        }

        // Fetch the resource
        $resource = $resourcesTable->get($resourceId);

        return [
            'resource_id' => $resource->id,
            'resource_name' => $resource->name,
            'resource_username' => $resource->username,
            'resource_uri' => $resource->uri,
        ];
    }

    /**
     * Handle the share action log and determine changes in permissions.
     *
     * @param string $actionLogId action log ID
     * @return array
     */
    protected function handleShareAction(string $actionLogId): array
    {
        /** @var \Passbolt\Log\Model\Table\EntitiesHistoryTable $entitiesHistoryTable */
        $entitiesHistoryTable = $this->fetchTable('EntitiesHistory');
        /** @var \Passbolt\Log\Model\Table\PermissionsHistoryTable $permissionsHistoryTable */
        $permissionsHistoryTable = $this->fetchTable('PermissionsHistory');
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = $this->fetchTable('Users');
        /** @var \App\Model\Table\GroupsTable $groupsTable */
        $groupsTable = $this->fetchTable('Groups');
        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');

        // Fetch the entities history for the action log
        $entitiesHistory = $entitiesHistoryTable->find()
            ->where(['action_log_id' => $actionLogId, 'foreign_model' => 'PermissionsHistory'])
            ->all();

        $addedPermissions = [];
        $removedPermissions = [];
        $changedPermissions = [];

        // Process each entities history entry
        foreach ($entitiesHistory as $entityHistory) {
            $permissionsHistory = $permissionsHistoryTable->get($entityHistory->foreign_key);

            switch ($entityHistory->crud) {
                case 'c':
                    $addedPermissions[] = $permissionsHistory;
                    break;
                case 'd':
                    $removedPermissions[] = $permissionsHistory;
                    break;
                case 'u':
                    $changedPermissions[] = $permissionsHistory;
                    break;
            }
        }

        $context = ' shared password';

        // Permission type mappings
        $permissionTypes = [
            1 => 'CAN_READ',
            7 => 'CAN_UPDATE',
            15 => 'OWNER',
        ];

        // Add details about added permissions
        if (!empty($addedPermissions)) {
            $context .= ' with:';
            foreach ($addedPermissions as $perm) {
                if ($perm->aro === 'User') {
                    $user = $usersTable->get($perm->aro_foreign_key, contain: ['Profiles']);
                    $context .= ' ' . $user->username . ' (' . $user->profile->first_name . ' ';
                    $context .= $user->profile->last_name . ') ' . $permissionTypes[$perm->type];
                } elseif ($perm->aro === 'Group') {
                    $group = $groupsTable->get($perm->aro_foreign_key);
                    $context .= ' group \'' . $group->name . '\' ' . $permissionTypes[$perm->type];
                }
            }
        }

        // Add details about removed permissions
        if (!empty($removedPermissions)) {
            $context .= ' and removed permissions for:';
            foreach ($removedPermissions as $perm) {
                if ($perm->aro === 'User') {
                    $user = $usersTable->get($perm->aro_foreign_key, ['contain' => ['Profiles']]);
                    $context .= ' ' . $user->username . ' (' . $user->profile->first_name . ' ';
                    $context .= $user->profile->last_name . ') ' . $permissionTypes[$perm->type];
                } elseif ($perm->aro === 'Group') {
                    $group = $groupsTable->get($perm->aro_foreign_key);
                    $context .= ' group \'' . $group->name . '\' ' . $permissionTypes[$perm->type];
                }
            }
        }

        // Add details about changed permissions
        if (!empty($changedPermissions)) {
            $context .= ' and updated permissions for:';
            foreach ($changedPermissions as $perm) {
                if ($perm->aro === 'User') {
                    $user = $usersTable->get($perm->aro_foreign_key, ['contain' => ['Profiles']]);
                    $context .= ' ' . $user->username . ' (' . $user->profile->first_name . ' ';
                    $context .= $user->profile->last_name . ') ' . $permissionTypes[$perm->type];
                } elseif ($perm->aro === 'Group') {
                    $group = $groupsTable->get($perm->aro_foreign_key);
                    $context .= ' group \'' . $group->name . '\' ' . $permissionTypes[$perm->type];
                }
            }
        }

        $resourceId = $addedPermissions[0]->aco_foreign_key ?? $removedPermissions[0]->aco_foreign_key
            ?? $changedPermissions[0]->aco_foreign_key ?? null;
        $resource = $resourcesTable->get($resourceId);

        return [
            'context' => $context,
            'resource_id' => $resource->id,
            'resource_name' => $resource->name,
        ];
    }
}
