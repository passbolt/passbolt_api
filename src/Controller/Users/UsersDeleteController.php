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
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\RulesChecker;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class UsersDeleteController extends AppController
{
    /**
     * Before filter
     *
     * @param Event $event An Event instance
     * @return \Cake\Http\Response|null
     */
    public function beforeFilter(Event $event)
    {
        $this->loadModel('Users');
        $this->loadModel('Resources');
        $this->loadModel('Groups');
        $this->loadModel('GroupsUsers');
        $this->loadModel('Permissions');

        return parent::beforeFilter($event);
    }

    /**
     * User delete dry run action
     *
     * @param string $id user uuid
     * @return void
     */
    public function dryrun($id)
    {
        $user = $this->_validateRequestData($id);
        $this->_validateDelete($user);
        $this->success(__('The user can be deleted.'));
    }

    /**
     * User delete action
     *
     * @param string $id user uuid
     * @return void
     */
    public function delete($id)
    {
        $user = $this->_validateRequestData($id);
        // keep a list of group the user was a member of. Useful to notify the group managers after the delete
        $groupIdsNotOnlyMember = $this->GroupsUsers->findGroupsWhereUserNotOnlyMember($id)->extract('group_id')->toArray();

        $this->GroupsUsers->getConnection()->transactional(function () use ($user) {
            $this->_transferGroupsManagers($user);
            $this->_transferResourcesOwners($user);
            $this->_validateDelete($user);
            if (!$this->Users->softDelete($user, ['checkRules' => false])) {
                throw new InternalErrorException(__('Could not delete the user, please try again later.'));
            }
        });

        $this->_notifyUsers($user, $groupIdsNotOnlyMember);
        $this->success(__('The user was deleted successfully.'));
    }

    /**
     * Assert request sanity and return the sanitized data
     *
     * @param string $id user uuid
     * @throws ForbiddenException if current user is not an admin
     * @throws BadRequestException if the user uuid id invalid
     * @throws BadRequestException if the user tries to delete themselves
     * @throws NotFoundException if the user does not exist or is already deleted
     * @return User $user entity
     */
    protected function _validateRequestData($id)
    {
        // Admin can delete all users
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The user id must be a valid uuid.'));
        }
        // An admin cannot delete themeselves
        if ($id === $this->User->id()) {
            throw new BadRequestException(__('You are not allowed to delete yourself.'));
        }
        $user = $this->Users->findDelete($id, $this->User->role())->first();
        if (empty($user)) {
            throw new NotFoundException(__('The user does not exist or has been already deleted.'));
        }

        return $user;
    }

    /**
     * Validate the delete operation.
     * @param User $user The target user
     * @throws CustomValidationException if the user is sole manager of a group
     * @throws CustomValidationException if the user is sole owner of a shared resource
     * @throws CustomValidationException if the user is sole manager of a group that is the sole owner of a shared resource
     * @return void
     */
    protected function _validateDelete($user)
    {
        // Check business rules
        // Returning the validation error is not meaningful enough so we may need to
        // build the proper anwer with list of incriminated groups / resources
        if (!$this->Users->checkRules($user, RulesChecker::DELETE)) {
            $errors = $user->getErrors();
            $body = [];
            $msg = __('The user cannot be deleted.') . ' ';

            if (isset($errors['id']['soleManagerOfNonEmptyGroup'])) {
                $groupIds = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($user->id)->extract('group_id')->toArray();
                $findGroupsOptions = [];
                $findGroupsOptions['contain']['group_user.user.profile'] = true;
                $groups = $this->Groups->findAllByIds($groupIds, $findGroupsOptions);
                $body['errors']['groups']['sole_manager'] = $groups;
                $msg .= $errors['id']['soleManagerOfNonEmptyGroup'];
            }

            if (isset($errors['id']['soleOwnerOfSharedResource'])) {
                $resourcesIds = $this->Permissions->findSharedResourcesUserIsSoleOwner($user->id, true)->extract('aco_foreign_key')->toArray();
                $findResourcesOptions = [];
                $findResourcesOptions['contain']['permissions.user.profile'] = true;
                $findResourcesOptions['contain']['permissions.group'] = true;
                $resources = $this->Resources->findAllByIds($user->id, $resourcesIds, $findResourcesOptions);
                $body['errors']['resources']['sole_owner'] = $resources;
                $msg .= $errors['id']['soleOwnerOfSharedResource'];
            }

            $groupsToDeleteIds = $this->GroupsUsers->findGroupsWhereUserOnlyMember($user->id)->extract('group_id')->toArray();
            if ($groupsToDeleteIds) {
                $groupsToDelete = $this->Groups->findAllByIds($groupsToDeleteIds);
                $body['groups_to_delete'] = $groupsToDelete;
            }

            throw new CustomValidationException($msg, $body);
        }
    }

    /**
     * Transfer the group managers which blocked the user delete
     * @param {User} $user entity
     * @throws BadRequestException The groups that required a change are not all satisfied
     * @return void
     */
    protected function _transferGroupsManagers($user)
    {
        $managers = $this->request->getData('transfer.managers');
        if (empty($managers)) {
            return;
        }

        $groupsUsersIdsToUpdate = Hash::extract($managers, '{n}.id');
        foreach ($groupsUsersIdsToUpdate as $id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The groups users ids must be valid uuids.'));
            }
        }

        $groupsIdsToUpdate = Hash::extract($managers, '{n}.group_id');
        sort($groupsIdsToUpdate);

        $groupsIdsBlockingDelete = $this->GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($user->id)->extract('group_id')->toArray();
        sort($groupsIdsBlockingDelete);

        // If all the groups that are requiring a change are not satisfied, throw an exception.
        if ($groupsIdsToUpdate != $groupsIdsBlockingDelete) {
            throw new BadRequestException('The transfer is not authorized.');
        }

        // Update all the groups users given as parameter as long as they are relative to a group which blocked the delete process.
        $this->GroupsUsers->updateAll(['is_admin' => true], ['id IN' => $groupsUsersIdsToUpdate, 'group_id IN' => $groupsIdsBlockingDelete]);
    }

    /**
     * Transfer the resources permissions which blocked the user delete
     * @param {User} $user entity
     * @throws BadRequestException if the array of manager is
     * @return void
     */
    protected function _transferResourcesOwners($user)
    {
        $owners = $this->request->getData('transfer.owners');
        if (empty($owners)) {
            return;
        }

        $permissionsIdsToUpdate = Hash::extract($owners, '{n}.id');
        foreach ($permissionsIdsToUpdate as $id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The permissions ids must be valid uuids.'));
            }
        }

        $resourcesIdsToUpdate = Hash::extract($owners, '{n}.aco_foreign_key');
        sort($resourcesIdsToUpdate);

        $resourcesIdsBlockingDelete = $this->Permissions->findSharedResourcesUserIsSoleOwner($user->id, true)->extract('aco_foreign_key')->toArray();
        sort($resourcesIdsBlockingDelete);

        // If all the resources that are requiring a change are not satisfied, throw an exception.
        if ($resourcesIdsToUpdate != $resourcesIdsBlockingDelete) {
            throw new BadRequestException('The transfer is not authorized');
        }

        // Update all the permissions given as parameter as long as they are relative to a resource which blocked the delete process.
        $this->Permissions->updateAll(['type' => Permission::OWNER], ['id IN' => $permissionsIdsToUpdate, 'aco_foreign_key IN' => $resourcesIdsBlockingDelete]);
    }

    /**
     * Send email notification
     *
     * @param User $user entity
     * @param array $groupIds list of Group entity user was member of
     * @return void
     */
    protected function _notifyUsers(User $user, array $groupIds)
    {
        $event = new Event('UsersDeleteController.delete.success', $this, [
            'user' => $user,
            'groupsIds' => $groupIds,
            'deletedBy' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
