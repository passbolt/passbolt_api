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
 * @since         2.0.0
 */

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Group;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class GroupsUpdateController extends AppController
{
    // Is the current user manager of the group ?
    protected $_isGroupManager = false;

    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Groups');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Resources');
        $this->loadModel('Secrets');

        return parent::beforeFilter($event);
    }

    /**
     * Group Update Dry Run action
     *
     * @param string $id The identifier of the group to update.
     * @throws InternalErrorException If an unexpected error occurred when saving the group
     * @throws ForbiddenException If the user is not an admin
     * @throws ValidationException If an error occurred when patching or saving the group
     * @return void
     */
    public function dryRun($id)
    {
        $group = $this->_validateRequestParameter($id);
        $groupUserOriginal = $this->_extractGroupUsersData($group);
        $data = $this->_formatRequestData();

        // Patch and validate the group.
        $group = $this->_patchAndValidateGroupEntity($group, $data);

        // Dry run the save.
        $this->Groups->getConnection()->transactional(function () use ($group) {
            $saveResult = $this->Groups->save($group);
            if ($saveResult === false) {
                $this->_handleValidationError($group);
            }

            return false;
        });

        // Retrieve the secrets to request to the client to encrypt.
        $secretsToRequest = $this->_getGrantedAccess($group, $groupUserOriginal);

        // Retrieve the current user secrets that the client will encrypt for the new users.
        $userSecrets = $this->_getUserSecrets($secretsToRequest);

        // Format the result and
        $result = $this->_formatDryRunResult($secretsToRequest, $userSecrets);
        $this->success(__('The operation was successful.'), $result);
    }

    /**
     * Group Update action
     *
     * @param string $id The identifier of the group to update.
     * @throws InternalErrorException If an unexpected error occurred when saving the group
     * @throws ForbiddenException If the user is not an admin
     * @throws ValidationException If an error occurred when patching or saving the group
     * @return void
     */
    public function update($id)
    {
        $group = $this->_validateRequestParameter($id);
        $groupUserOriginal = $this->_extractGroupUsersData($group);
        $data = $this->_formatRequestData();

        // Patch and validate the group.
        $group = $this->_patchAndValidateGroupEntity($group, $data);
        list($addedGroupsUsers, $updatedGroupsUsers, $removedGroupsUsers)
            = $this->_getGroupsUsersChanges($groupUserOriginal, $group->groups_users);

        // Try to save the changes.
        $this->Groups->getConnection()->transactional(function () use ($group, $groupUserOriginal, $data) {
            // Save the memberships changes.
            $saveResult = $this->Groups->save($group);
            if ($saveResult === false) {
                $this->_handleValidationError($group);
            }
            // Patch the group resources secrets.
            $resources = $this->_patchAndValidateResourcesEntities($group, $groupUserOriginal, Hash::get($data, 'secrets', []));
            // Save the group resources secrets.
            $saveResult = $this->_saveResourcesEntities($resources);
            if ($saveResult === false) {
                $this->_handleValidationError($group, $resources);

                return false;
            }
            // Remove associated data for users who lost access to the resources shared with the group.
            $this->_deleteLostAccessAssociatedData($group, $groupUserOriginal);
        });

        // Notify the users
        $this->_notifyUsers($group, $addedGroupsUsers, $updatedGroupsUsers, $removedGroupsUsers);

        // The v1 expect the updated group to be returned.
        $viewOptions = [
            'contain' => ['group_user' => 1, 'group_user.user.profile' => 1]
        ];
        $group = $this->Groups->findView($id, $viewOptions)->first();
        $this->success(__('The operation was successful.'), $group);
    }

    /**
     * Extract the group users data in an array, it will be useful to compare data before and after the update
     * is performed.
     *
     * @param Group $group The target group
     * @return array
     */
    protected function _extractGroupUsersData(Group $group)
    {
        return array_reduce($group->groups_users, function ($carry, $item) {
            $carry[] = $item->toArray();

            return $carry;
        }, []);
    }

    /**
     * Retrieve and organize the changes by change type that occurred on a list of group user entity.
     *
     * @param array $originalGroupsUsers The list of original groups users
     * @param array $groupsUsers The list of groups users after patching the list of entity
     * @return array
     */
    protected function _getGroupsUsersChanges(array $originalGroupsUsers, array $groupsUsers)
    {
        // Added groups users
        $addedGroupsUsers = array_reduce($groupsUsers, function ($carry, $item) {
            if ($item->isNew()) {
                $carry[] = $item;
            }

            return $carry;
        }, []);

        // Removed groups users
        $updatedGroupsUsers = array_reduce($groupsUsers, function ($carry, $item) {
            if ($item->isDirty() && !$item->isNew()) {
                $carry[] = $item;
            }

            return $carry;
        }, []);

        // Updated groups users
        $removedGroupsUsers = array_reduce($originalGroupsUsers, function ($carry, $item) use ($groupsUsers) {
            $extract = hash::extract($groupsUsers, "{n}[id={$item['id']}]");
            if (empty($extract)) {
                $carry[] = $item;
            }

            return $carry;
        }, []);

        return [$addedGroupsUsers, $updatedGroupsUsers, $removedGroupsUsers];
    }

    /**
     * Remove the resource associated data for the users who lost access to the resources shared with the group.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupsUsersBeforeUpdate The list of group user as it was before the update process start
     * @return void
     */
    protected function _deleteLostAccessAssociatedData(\App\Model\Entity\Group $group, array $groupsUsersBeforeUpdate)
    {
        $removedAccess = $this->_getRemovedAccess($group, $groupsUsersBeforeUpdate);
        $resourcesLostAccess = [];

        // Regroup the removed access by resource.
        foreach ($removedAccess as $access) {
            $resourcesLostAccess[$access['resource_id']][] = $access['user_id'];
        }

        // Remove resource associated data.
        foreach ($resourcesLostAccess as $resourceId => $usersId) {
            $this->Resources->deleteLostAccessAssociatedData($resourceId, $usersId);
        }
    }

    /**
     * Notify the users that they group have been deleted
     *
     * @param \App\Model\Entity\Group $group The updated group
     * @param array $addedGroupsUsers changes requested by group editor
     * @param array $updatedGroupsUsers the list of users updated for the group
     * @param array $removedGroupsUsers the list of users removed from the group
     * @return void
     */
    protected function _notifyUsers(
        Group $group,
        array $addedGroupsUsers,
        array $updatedGroupsUsers,
        array $removedGroupsUsers
    ) {
        $event = new Event('GroupsUpdateController.update.success', $this, [
            'group' => $group,
            'addedGroupsUsers' => $addedGroupsUsers,
            'updatedGroupsUsers' => $updatedGroupsUsers,
            'removedGroupsUsers' => $removedGroupsUsers,
            'userId' => $this->User->id()
        ]);
        $this->getEventManager()->dispatch($event);
    }

    /**
     * Validate request parameter and return the target group entity
     *
     * @param string $id group uuid
     * @throws ForbiddenException if current group is not an admin
     * @throws BadRequestException if the group uuid id invalid
     * @throws NotFoundException if the group does not exist or is already deleted
     * @throws ValidationException if the group is sole manager of a group
     * @throws ValidationException if the group is sole owner of a shared resource
     * @return \App\Model\Entity\Group $group entity
     */
    protected function _validateRequestParameter($id)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group id is not valid.'));
        }

        // Retrieve the group to update and all its groups users.
        try {
            $group = $this->Groups->get($id, ['contain' => ['GroupsUsers']]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        // Cannot update a soft deleted group.
        if ($group->deleted) {
            throw new NotFoundException(__('The group does not exist.'));
        }

        // If the user is not manager of the group nor admin
        $this->_isGroupManager = $this->GroupsUsers->isManager($this->User->id(), $id);
        if (!$this->_isGroupManager && !$this->User->isAdmin()) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }

        return $group;
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();

        // Group given in V1 format.
        if (isset($data['Group'])) {
            $data = array_merge_recursive($data, Hash::extract($data, 'Group'));
            unset($data['Group']);
        }
        // GroupsUsers given in V1 format.
        if (!empty($data['GroupUsers'])) {
            $data['groups_users'] = Hash::extract($data['GroupUsers'], '{n}.GroupUser');
            unset($data['GroupUsers']);
        }
        // Secrets given in V1 format.
        if (isset($data['Secrets'])) {
            $data['secrets'] = Hash::extract($data['Secrets'], '{n}.Secret');
            unset($data['Secrets']);
        }

        return $data;
    }

    /**
     * Patch the group entity with the user input.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $data The user input
     * @throws ValidationException If an error occurred when patching the entity.
     * @return \App\Model\Entity\Group
     */
    protected function _patchAndValidateGroupEntity(\App\Model\Entity\Group $group, array $data)
    {
        $groupPatchOptions = [];
        $groupsUsersPatchOptions = [];

        // Handle modify_by
        $groupPatchOptions['accessibleFields']['modified_by'] = true;
        $data['modified_by'] = $this->User->id();

        // An administrator can update the group name.
        // An administrator can update the group members roles.
        if ($this->User->isAdmin()) {
            $groupPatchOptions['accessibleFields']['name'] = true;
            $groupsUsersPatchOptions = array_merge_recursive($groupsUsersPatchOptions, [
                'allowedOperations' => [
                    'add' => false,
                    'update' => true,
                    'delete' => true,
                ]
            ]);
        }

        // A group manager can update the group members roles.
        // A group manager can add or delete group members.
        if ($this->_isGroupManager) {
            $groupsUsersPatchOptions = array_merge_recursive($groupsUsersPatchOptions, [
                'allowedOperations' => [
                    'add' => true,
                    'update' => true,
                    'delete' => true,
                ]
            ]);
        }

        // Apply the changes to the groups_users entities.
        try {
            $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges(
                $group->groups_users,
                Hash::get($data, 'groups_users', []),
                $group->id,
                $groupsUsersPatchOptions
            );
            $group->setDirty('groups_users', true);
        } catch (ValidationException $e) {
            $group->setError('groups_users', $e->getErrors());
        }

        // Patch the group.
        $this->Groups->patchEntity($group, $data, $groupPatchOptions);
        $this->_handleValidationError($group);

        return $group;
    }

    /**
     * Patch the resources entities the groups has access with the requested changes.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupsUsersBeforeUpdate The list of group user as it was before the update process start
     * @param array $secrets The list of secrets provided by the client.
     * @return array The list of resources
     */
    protected function _patchAndValidateResourcesEntities(\App\Model\Entity\Group $group, array $groupsUsersBeforeUpdate, array $secrets)
    {
        $resources = [];
        $addedAccess = $this->_getGrantedAccess($group, $groupsUsersBeforeUpdate);
        $removedAccess = $this->_getRemovedAccess($group, $groupsUsersBeforeUpdate);

        // Group the changes by resource.
        $changes = [];
        // The secrets to add.
        foreach ($addedAccess as $access) {
            foreach ($secrets as $secret) {
                if ($secret['user_id'] == $access['user_id']
                    && $secret['resource_id'] == $access['resource_id']) {
                    $changes[$access['resource_id']]['add'][] = $secret;
                }
            }
        };
        // The secret to remove.
        foreach ($removedAccess as $access) {
            $changes[$access['resource_id']]['remove'][] = $access['user_id'];
        }

        // Apply the changes to the resources secrets entities.
        foreach ($changes as $resourceId => $change) {
            $resource = $this->Resources->get($resourceId, ['contain' => ['Secrets']]);
            try {
                $resource->secrets = $this->Secrets->patchEntitiesWithChanges(
                    $resource->id,
                    $resource->secrets,
                    Hash::get($change, 'add', []),
                    Hash::get($change, 'remove', [])
                );
                $resource->setDirty('secrets', true);
            } catch (ValidationException $e) {
                $group->setError('secrets', $e->getErrors());
                $this->_handleValidationError($group);
            }
            $resources[] = $resource;
        }

        return $resources;
    }

    /**
     * Save the resources secrets.
     *
     * @param array $resources The list of resources to save
     * @return array|bool False in case of error, otherwise the list of saved entities
     */
    protected function _saveResourcesEntities(array $resources = [])
    {
        $options = [
            'accessibleFields' => [
                'secrets' => true
            ],
            'associated' => [
                'Secrets' => [
                    'accessibleFields' => [
                        'resource_id' => true,
                        'user_id' => true,
                        'data' => true,
                    ]
                ]
            ]
        ];

        return $this->Resources->saveMany($resources, $options);
    }

    /**
     * Manage validation errors.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $resources The resources that are updated along with the group.
     * @throw ValidationException If a validation rule did not validate
     * @return void
     */
    protected function _handleValidationError(\App\Model\Entity\Group $group, array $resources = [])
    {
        // If an error occurred on the group.
        $errors = $group->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate group data.'), $group, $this->Groups);
        }
        // If an error occurred on the resources.
        foreach ($resources as $resource) {
            $errors = $resource->getErrors();
            throw new ValidationException(__('Could not validate group data.'), $group, $this->Resources);
        }
    }

    /**
     * Get the granted access after updating the groups users.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupsUsersBeforeUpdate The list of group user as it was before the update process start
     * @return array A list of secrets to request to the client
     *
     * Format:
     * [
     *   [
     *     'resource_id' => uuid,
     *     'user_id' => uuid
     *   ],
     *   ...
     * ]
     */
    protected function _getGrantedAccess(\App\Model\Entity\Group $group, array $groupsUsersBeforeUpdate = [])
    {
        $addedAccess = [];

        // Retrieve the users who are added to the group.
        $usersIdsBeforeUpdate = Hash::extract($groupsUsersBeforeUpdate, '{n}.user_id');
        $usersIdsAfterUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $addedUsersIds = array_diff($usersIdsAfterUpdate, $usersIdsBeforeUpdate);

        // If no users added to the group, no secrets need to be encrypted.
        if (empty($addedUsersIds)) {
            return $addedAccess;
        }

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($group->id)->all();

        // For all the resources the group has access retrieve the resources for which the added users do not have
        // already a secret for.
        foreach ($addedUsersIds as $userId) {
            foreach ($resources as $resource) {
                $secret = $this->Resources->Secrets->findByResourceUser($resource->id, $userId)->first();
                if (is_null($secret)) {
                    $addedAccess[] = ['resource_id' => $resource->id, 'user_id' => $userId];
                }
            }
        }

        return $addedAccess;
    }

    /**
     * Get the removed access after updating the groups users.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $groupsUsersBeforeUpdate The list of group user as it was before the update process start
     * @return array A list of secrets to remove
     *
     * Format:
     * [
     *   [
     *     'resource_id' => uuid,
     *     'user_id' => uuid
     *   ],
     *   ...
     * ]
     */
    protected function _getRemovedAccess(\App\Model\Entity\Group $group, array $groupsUsersBeforeUpdate = [])
    {
        $accessRemoved = [];

        // Retrieve the users who are removed from the groups
        $usersIdsBeforeUpdate = Hash::extract($groupsUsersBeforeUpdate, '{n}.user_id');
        $usersIdsAfterUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $removedUsersIds = array_diff($usersIdsBeforeUpdate, $usersIdsAfterUpdate);

        // If no users added to the group, no secrets need to be encrypted.
        if (empty($removedUsersIds)) {
            return $accessRemoved;
        }

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($group->id)->all();

        // For all the resources the group has access retrieve the resources for which the removed users do not
        // have anymore access to.
        foreach ($removedUsersIds as $userId) {
            foreach ($resources as $resource) {
                if (!$this->Resources->hasAccess($userId, $resource->id)) {
                    $accessRemoved[] = ['resource_id' => $resource->id, 'user_id' => $userId];
                }
            }
        }

        return $accessRemoved;
    }

    /**
     * Retrieve the current user secrets for a list of resource.
     *
     * @param array $secretsToRequest The list of secrets to request.
     * @return \Cake\ORM\ResultSet|array
     */
    protected function _getUserSecrets(array $secretsToRequest = [])
    {
        $result = [];
        $resourcesIds = array_unique(Hash::extract($secretsToRequest, '{n}.resource_id'));

        if (!empty($resourcesIds)) {
            $result = $this->Secrets->findByResourcesUser($resourcesIds, $this->User->id())
                ->all()
                ->toArray();
        }

        return $result;
    }

    /**
     * Format the result.
     *
     * This entry point is used by the plugin app, and due to the V1 legacy the output body must be
     * formatted as following:
     *
     * [
     *   'SecretsNeeded' => [
     *     [
     *       'Secret' => [
     *         'resource_id' => uuid,
     *         'user_id' => uuid
     *       ],
     *       ...
     *     ]
     *   ],
     *   'Secrets' => [
     *     [
     *       'Secret' => [
     *         'id' => uuid,
     *         'resource_id' => uuid,
     *         'user_id' => uuid,
     *         'data' => gpg_armored_text
     *       ],
     *     ],
     *     ...
     * ]
     *
     * @param array $secretsToRequest The list of secrets to request for encryption to the client.
     * @param array $userSecrets The current user secrets that will be used to encrypt the new secrets.
     * @return array
     */
    private function _formatDryRunResult(array $secretsToRequest, array $userSecrets)
    {
        $result = [
            'dry-run' => [
                'SecretsNeeded' => [],
                'Secrets' => []
            ]
        ];

        // Format the content.
        foreach ($secretsToRequest as $secret) {
            $result['dry-run']['SecretsNeeded'][] = ['Secret' => $secret];
        }
        foreach ($userSecrets as $secret) {
            $result['dry-run']['Secrets'][] = ['Secret' => [$secret]];
        }

        return $result;
    }
}
