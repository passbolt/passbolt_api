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
 * @since         2.13.0
 */

namespace App\Service\Resources;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Permissions\PermissionsUpdatePermissionsService;
use App\Service\Permissions\UserHasPermissionService;
use App\Service\Secrets\SecretsUpdateSecretsService;
use App\Utility\UserAccessControl;
use Cake\Datasource\ModelAwareTrait;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

class ResourcesShareService
{
    use ModelAwareTrait;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    private $GroupsUsers;

    /**
     * @var \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService
     */
    private $permissionsGetUsersIdsHavingAccessToService;

    /**
     * @var \App\Service\Permissions\PermissionsUpdatePermissionsService
     */
    private $permissionsUpdatePermissionsService;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    private $Resources;

    /**
     * @var \App\Service\Secrets\SecretsUpdateSecretsService
     */
    private $secretsUpdateSecretsService;

    /**
     * @var \App\Service\Permissions\UserHasPermissionService
     */
    private $userHasPermissionService;

    /**
     * Instantiate the service.
     */
    public function __construct()
    {
        $this->loadModel('GroupsUsers');
        $this->loadModel('Resources');
        $this->permissionsGetUsersIdsHavingAccessToService = new PermissionsGetUsersIdsHavingAccessToService();
        $this->permissionsUpdatePermissionsService = new PermissionsUpdatePermissionsService();
        $this->secretsUpdateSecretsService = new SecretsUpdateSecretsService();
        $this->userHasPermissionService = new UserHasPermissionService();
    }

    /**
     * Share a resource by updating its permissions.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $resourceId The target resource
     * @param array $changes The list of permissions changes to apply
     * @param array $secrets The list of new secrets to add
     * @return Resource
     * @throws \Exception
     */
    public function share(UserAccessControl $uac, string $resourceId, array $changes = [], array $secrets = [])
    {
        $resource = $this->getResource($resourceId);

        $this->Resources->getConnection()->transactional(function () use ($uac, $resource, $changes, $secrets) {
            $updatePermissionsResult = $this->updatePermissions($uac, $resource, $changes);
            $this->updateSecrets($uac, $resource, $secrets);
            $this->postAccessesGranted($uac, $updatePermissionsResult['added']);
            $this->postAccessesRevoked($uac, $resource, $updatePermissionsResult['removed']);
        });

        return $resource;
    }

    /**
     * Retrieve the resource.
     *
     * @param string $resourceId The resource identifier to retrieve.
     * @return Resource
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     */
    private function getResource(string $resourceId): Resource
    {
        /** @var Resource|null $resource */
        $resource = $this->Resources->findByIdAndDeleted($resourceId, false)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        return $resource;
    }

    /**
     * Update the permissions of a resource.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param Resource $resource The target resource
     * @param array $changes The list of permissions changes to apply
     * @return array
     * [
     *   added => <array> List of added permissions
     *   deleted => <array> List of deleted permissions
     *   updated => <array> List of updated permissions
     * ]
     * @throws \Exception If something unexpected occurred
     */
    private function updatePermissions(UserAccessControl $uac, Resource $resource, array $changes)
    {
        $result = [];
        try {
            $result = $this->permissionsUpdatePermissionsService
                ->updatePermissions($uac, PermissionsTable::RESOURCE_ACO, $resource->id, $changes);
        } catch (CustomValidationException $e) {
            $resource->setError('permissions', $e->getErrors());
            $this->handleValidationErrors($resource);
        }

        return $result;
    }

    /**
     * Handle resource validation errors.
     *
     * @param Resource $resource The target resource
     * @return void
     * @throws \App\Error\Exception\ValidationException If the provided data does not validate.
     */
    private function handleValidationErrors(Resource $resource)
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->Resources);
        }
    }

    /**
     * Update the secrets.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param Resource $resource The target resource
     * @param array $data The list of secrets to add
     * @return void
     * @throws \Exception
     */
    private function updateSecrets(UserAccessControl $uac, Resource $resource, array $data)
    {
        try {
            $this->secretsUpdateSecretsService->updateSecrets($uac, $resource->id, $data);
        } catch (CustomValidationException $e) {
            $resource->setError('secrets', $e->getErrors());
            $this->handleValidationErrors($resource);
        }
    }

    /**
     * Post accesses granted.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param array $addedPermissions The list of added permissions
     * @return void
     */
    private function postAccessesGranted(UserAccessControl $uac, array $addedPermissions = [])
    {
        foreach ($addedPermissions as $addedPermission) {
            $this->notifyAccessGranted($uac, $addedPermission);
        }
    }

    /**
     * Trigger an event to notify other components about the granted permissions.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Permission $permission The granted permission
     * @return void
     */
    private function notifyAccessGranted(UserAccessControl $uac, Permission $permission)
    {
        $eventData = ['permission' => $permission, 'accessControl' => $uac];
        $event = new Event('Service.ResourcesShare.afterAccessGranted', $this, $eventData);
        $this->Resources->getEventManager()->dispatch($event);
    }

    /**
     * Post accesses revoked.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param Resource $resource The target permissions
     * @param array $deletedPermissions The list of deleted permissions
     * @return void
     */
    private function postAccessesRevoked(UserAccessControl $uac, Resource $resource, array $deletedPermissions = [])
    {
        foreach ($deletedPermissions as $deletedPermission) {
            $this->notifyAccessRevoked($uac, $deletedPermission);
            if ($deletedPermission->aro === PermissionsTable::GROUP_ARO) {
                $this->postGroupAccessRevoked($resource, $deletedPermission->aro_foreign_key);
            } else {
                $this->postUserAccessRevoked($resource, $deletedPermission->aro_foreign_key);
            }
        }
    }

    /**
     * Trigger an event to notify other components about the revoked permissions.
     *
     * @param \App\Utility\UserAccessControl $uac The operator
     * @param \App\Model\Entity\Permission $permission The revoked permission
     * @return void
     */
    private function notifyAccessRevoked(UserAccessControl $uac, Permission $permission)
    {
        $eventData = ['permission' => $permission, 'accessControl' => $uac];
        $event = new Event('Service.ResourcesShare.afterAccessRevoked', $this, $eventData);
        $this->Resources->getEventManager()->dispatch($event);
    }

    /**
     * Post group access revoked treatment.
     *
     * @param Resource $resource The target resource
     * @param string $groupId The target group
     * @return void
     * @throws \Exception
     */
    private function postGroupAccessRevoked(Resource $resource, string $groupId)
    {
        $grousUsersIds = $this->GroupsUsers->findByGroupId($groupId)->all()->extract('user_id')->toArray();
        foreach ($grousUsersIds as $groupUserId) {
            $this->postUserAccessRevoked($resource, $groupUserId);
        }
    }

    /**
     * Post user access revoked treatment. If user doesn't have anymore access to a resource:
     * - Remove the user secrets for this resource.
     * - Remove the user favorites for this resource.
     * - Trigger an event to notify other plugin about the revoked access.
     *
     * @param Resource $resource The target resource
     * @param string $userId The target user
     * @return void
     */
    private function postUserAccessRevoked(Resource $resource, string $userId)
    {
        // If the user still has access to the folder, don't alter the user tree.
        $hasAccess = $this->userHasPermissionService->check(PermissionsTable::FOLDER_ACO, $resource->id, $userId);
        if ($hasAccess) {
            return;
        }

        $this->Resources->deleteLostAccessAssociatedData($resource->id, [$userId]);
    }

    /**
     * Simulate the share of a resource in order to retrieve the users that will require the secret to be encrypted for.
     *
     * The function returns an associative array that contains a list of new users who will have access to the resource,
     * and a list of users who will lose their access. These lists will be used to encrypt the secrets for the users
     * who get access and to remove the secrets of the users who lost their access.
     *
     * [
     *   'added' => [uuid],
     *   'removed' => [uuid]
     * ]
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param string $resourceId The target resource
     * @param array|null $changes The list of changes to apply
     * @return array
     * @throws \Exception
     */
    public function shareDryRun(UserAccessControl $uac, string $resourceId, ?array $changes = []): array
    {
        $resource = $this->getResource($resourceId);
        $userIdsHavingAccessBefore = $this->permissionsGetUsersIdsHavingAccessToService
            ->getUsersIdsHavingAccessTo($resourceId);
        $userIdsHavingAccessAfter = [];

        $this->Resources->getConnection()->transactional(
            function () use ($uac, $resource, $changes, &$userIdsHavingAccessAfter) {
                $this->updatePermissions($uac, $resource, $changes);
                $userIdsHavingAccessAfter = $this->permissionsGetUsersIdsHavingAccessToService
                    ->getUsersIdsHavingAccessTo($resource->id);

                // Don't commit the transaction.
                return false;
            }
        );

        // Extract the users that will require the secrets to be encrypted for.
        $usersIdsToAddSecretFor = array_diff($userIdsHavingAccessAfter, $userIdsHavingAccessBefore);
        // Extract the users the secrets will be deleted for.
        $usersIdsToDeleteSecretFor = array_diff($userIdsHavingAccessBefore, $userIdsHavingAccessAfter);

        return [
            'added' => $usersIdsToAddSecretFor,
            'deleted' => $usersIdsToDeleteSecretFor,
        ];
    }
}
