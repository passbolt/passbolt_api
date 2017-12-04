<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
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

namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Error\Exception\ValidationRuleException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\ForbiddenException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
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
     * @throws ValidationRuleException If an error occurred when patching or saving the group
     * @return void
     */
    public function dryRun($id)
    {
        $group = $this->_validateRequestParameter($id);
        $data = $this->_formatRequestData();

        // Patch and validate the group.
        $usersIdsBeforeUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $group = $this->_patchAndValidateGroupEntity($group, $data);

        // Dry run the save.
        $this->Groups->getConnection()->transactional(function () use ($group) {
            $saveResult = $this->Groups->save($group);
            if (!$saveResult) {
                $this->_handleValidationError($group);
            }

            return false;
        });

        // Retrieve the secrets to request to the client to encrypt.
        $secretsToRequest = $this->_getSecretsToRequest($group, $usersIdsBeforeUpdate);

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
     * @throws ValidationRuleException If an error occurred when patching or saving the group
     * @return void
     */
    public function update($id)
    {
        $group = $this->_validateRequestParameter($id);
        $data = $this->_formatRequestData();

        // Patch and validate the group.
        $usersIdsBeforeUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $group = $this->_patchAndValidateGroupEntity($group, $data);

        // Try to save the changes.
        $this->Groups->getConnection()->transactional(function () use ($group, $usersIdsBeforeUpdate, $data) {
            // Save the memberships changes.
            $saveResult = $this->Groups->save($group);
            if (!$saveResult) {
                $this->_handleValidationError($group);
            }
            // If not manager, do not need to continue.
            if (!$this->_isGroupManager) {
                return true;
            }
            // Patch the group resources secrets.
            $resources = $this->_patchAndValidateResourcesEntities($group, $usersIdsBeforeUpdate, Hash::get($data, 'secrets', []));
            // Save the group resources secrets.
            $saveResult = $this->_saveResourcesEntities($resources);
            if (!$saveResult) {
                $this->_handleValidationError($group, $resources);

                return false;
            }
        });

        $this->success(__('The operation was successful.'));
    }

    /**
     * Validate request parameter and return the target group entity
     *
     * @param string $id group uuid
     * @throws ForbiddenException if current group is not an admin
     * @throws BadRequestException if the group uuid id invalid
     * @throws NotFoundException if the group does not exist or is already deleted
     * @throws ValidationRuleException if the group is sole manager of a group
     * @throws ValidationRuleException if the group is sole owner of a shared resource
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
     * @throws ValidationRuleException If an error occurred when patching the entity.
     * @return \App\Model\Entity\Group
     */
    protected function _patchAndValidateGroupEntity(\App\Model\Entity\Group $group, array $data)
    {
        $patchEntityOptions = [
            'validate' => 'default'
        ];

        // Only administrator can update group name.
        if ($this->User->isAdmin()) {
            $patchEntityOptions['accessibleFields']['name'] = true;
        }

        // Only group manager can update the group memberships.
        if ($this->_isGroupManager) {
            // Add the groups_users options used when saving.
            $patchEntityOptions = array_merge_recursive([
                'associated' => [
                    'GroupsUsers' => [
                        'validate' => 'default',
                        'accessibleFields' => [
                            'group_id' => true,
                            'user_id' => true,
                            'is_admin' => true
                        ]
                    ]
                ]
            ]);

            // The changes won't be applied with the classic patchEntity method.
            // Instead use the patchEntitiesWithChanges which works directly on the entities array.
            try {
                $group->groups_users = $this->GroupsUsers->patchEntitiesWithChanges(
                    $group->groups_users,
                    Hash::get($data, 'groups_users', []),
                    $group->id
                );
                $group->dirty('groups_users', true);
            } catch (ValidationRuleException $e) {
                $group->setError('groups_users', $e->getErrors());
            }
        }

        $this->Groups->patchEntity($group, $data, $patchEntityOptions);
        $this->_handleValidationError($group);

        return $group;
    }

    /**
     * Patch the resources entities the groups has access with the requested changes.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $usersIdsBeforeUpdate The list of users identifies who were already members of the group before
     *        the entry point was called.
     * @param array $secrets The list of secrets provided by the client.
     * @return array The list of resources
     */
    protected function _patchAndValidateResourcesEntities(\App\Model\Entity\Group $group, array $usersIdsBeforeUpdate, array $secrets)
    {
        $resources = [];
        $requestedSecrets = $this->_getSecretsToRequest($group, $usersIdsBeforeUpdate);
        $secretsToRemove = $this->_getSecretsToRemove($group, $usersIdsBeforeUpdate);

        // Group the changes by resource.
        $changes = [];
        // The secrets to add.
        foreach ($requestedSecrets as $requestedSecret) {
            foreach ($secrets as $secret) {
                if ($secret['user_id'] == $requestedSecret['user_id']
                    && $secret['resource_id'] == $requestedSecret['resource_id']) {
                    $changes[$requestedSecret['resource_id']]['add'][] = $secret;
                }
            }
        };
        // The secret to remove.
        foreach ($secretsToRemove as $secret) {
            $changes[$secret['resource_id']]['remove'][] = $secret['user_id'];
        }

        // Patch the resources.
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
            } catch (ValidationRuleException $e) {
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
     * @return \App\Model\Entity\Group|bool False in case of error, otherwise the saved entities
     */
    protected function _saveResourcesEntities(array $resources = [])
    {
        $options = [
            'validate' => 'default',
            'accessibleFields' => [
                'secrets' => true
            ],
            'associated' => [
                'Secrets' => [
                    'validate' => 'default',
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
     * @throw ValidationRuleException If a validation rule did not validate
     * @return void
     */
    protected function _handleValidationError(\App\Model\Entity\Group $group, array $resources = [])
    {
        // If an error occurred on the group.
        $errors = $group->getErrors();
        if (!empty($errors)) {
            // @TODO hide some business rules: soft deleted, has access for example
            throw new ValidationRuleException(__('Could not validate group data.'), $errors, $this->Groups);
        }
        // If an error occurred on the resources.
        foreach ($resources as $resource) {
            $errors = $resource->getErrors();
            throw new ValidationRuleException(__('Could not validate group data.'), $errors, $this->Resources);
        }
    }

    /**
     * Get the secrets to request for encryption to the client.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $usersIdsBeforeUpdate The list of users identifies who were already members of the group before
     *        the entry point was called.
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
    protected function _getSecretsToRequest(\App\Model\Entity\Group $group, array $usersIdsBeforeUpdate)
    {
        $secretsToRequest = [];

        // Retrieve the users who are added to the group.
        $usersIdsAfterUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $addedUsersIds = array_diff($usersIdsAfterUpdate, $usersIdsBeforeUpdate);

        // If no users added to the group, no secrets need to be encrypted.
        if (empty($addedUsersIds)) {
            return $secretsToRequest;
        }

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($group->id)->all();

        // For all the resources the group has access retrieve the resources for which the added users do not have
        // already a secret for.
        foreach ($addedUsersIds as $userId) {
            foreach ($resources as $resource) {
                $secret = $this->Resources->Secrets->findByResourceUser($resource->id, $userId)->first();
                if (is_null($secret)) {
                    $secretsToRequest[] = ['resource_id' => $resource->id, 'user_id' => $userId];
                }
            }
        }

        return $secretsToRequest;
    }

    /**
     * Get the secrets to remove for the users who have been removed from the group.
     *
     * @param \App\Model\Entity\Group $group The group to update.
     * @param array $usersIdsBeforeUpdate The list of users identifies who were already members of the group before
     *        the entry point was called.
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
    protected function _getSecretsToRemove(\App\Model\Entity\Group $group, array $usersIdsBeforeUpdate)
    {
        $secretsToRemove = [];

        // Retrieve the users who are added to the group.
        $usersIdsAfterUpdate = Hash::extract($group->groups_users, '{n}.user_id');
        $removedUsersIds = array_diff($usersIdsBeforeUpdate, $usersIdsAfterUpdate);

        // If no users added to the group, no secrets need to be encrypted.
        if (empty($removedUsersIds)) {
            return $secretsToRemove;
        }

        // Retrieve the resources the group has access.
        $resources = $this->Resources->findAllByGroupAccess($group->id)->all();

        // For all the resources the group has access retrieve the resources for which the removed users do not
        // have anymore access to.
        foreach ($removedUsersIds as $userId) {
            foreach ($resources as $resource) {
                if (!$this->Resources->hasAccess($userId, $resource->id)) {
                    $secretsToRemove[] = ['resource_id' => $resource->id, 'user_id' => $userId];
                }
            }
        }

        return $secretsToRemove;
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
     * @todo Make this entry point output v2 compliant.
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
