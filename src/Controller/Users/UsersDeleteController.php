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
 * @since         2.0.0
 */
namespace App\Controller\Users;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Entity\Secret;
use App\Model\Entity\User;
use App\Model\Table\PermissionsTable;
use App\Service\Resources\ResourcesExpireResourcesServiceInterface;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * UsersDeleteController Class
 */
class UsersDeleteController extends AppController
{
    public const DELETE_SUCCESS_EVENT_NAME = 'UsersDeleteController.delete.success';

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @var \App\Model\Table\GroupsTable
     */
    protected $Groups;

    /**
     * @var \App\Model\Table\GroupsUsersTable
     */
    protected $GroupsUsers;

    /**
     * @var \App\Model\Table\PermissionsTable
     */
    protected $Permissions;

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Users = $this->fetchTable('Users');
        $this->Groups = $this->fetchTable('Groups');
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        $this->Permissions = $this->fetchTable('Permissions');
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * User delete dry run action
     *
     * @param string $id user uuid
     * @return void
     */
    public function dryrun(string $id)
    {
        $this->assertJson();

        $user = $this->_validateRequestData($id);
        $this->_validateDelete($user);
        $this->success(__('The user can be deleted.'));
    }

    /**
     * User delete action
     *
     * @param string $id user uuid
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService Service to expire resources that were consumed by users who lost access to them.
     * @return void
     * @throws \Exception if user cannot be deleted
     */
    public function delete(
        string $id,
        ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService
    ) {
        $this->assertJson();

        $user = $this->_validateRequestData($id);
        // keep a list of group the user was a member of. Useful to notify the group managers after the delete
        $groupIdsNotOnlyMember = $this->GroupsUsers
            ->findGroupsWhereUserNotOnlyMember($id)
            ->all()
            ->extract('group_id')
            ->toArray();

        $this->GroupsUsers->getConnection()->transactional(function () use ($user, $resourcesExpireResourcesService) {
            $this->_transferGroupsManagers($user);
            $this->_transferContentOwners($user);
            $this->_validateDelete($user);
            $entitiesChanges = $this->Users->softDelete($user, ['checkRules' => false]);
            if (!$entitiesChanges) {
                throw new InternalErrorException('Could not delete the user, please try again later.');
            }
            $deletedSecrets = $entitiesChanges->getDeletedEntities(Secret::class);
            $resourcesExpireResourcesService->expireResourcesForSecrets($deletedSecrets);
        });

        $this->_notifyUsers($user, $groupIdsNotOnlyMember);

        $this->success(__('The user has been deleted successfully.'));
    }

    /**
     * Assert request sanity and return the sanitized data
     *
     * @param string $id user uuid
     * @throws \Cake\Http\Exception\ForbiddenException if current user is not an admin
     * @throws \Cake\Http\Exception\BadRequestException if the user uuid id invalid
     * @throws \Cake\Http\Exception\BadRequestException if the user tries to delete themselves
     * @throws \Cake\Http\Exception\NotFoundException if the user does not exist or is already deleted
     * @return \App\Model\Entity\User $user entity
     */
    protected function _validateRequestData(string $id)
    {
        // Admin can delete all users
        if ($this->User->role() !== Role::ADMIN) {
            throw new ForbiddenException(__('You are not authorized to access that location.'));
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The user identifier should be a valid UUID.'));
        }
        // An admin cannot delete themselves
        if ($id === $this->User->id()) {
            throw new BadRequestException(__('You are not allowed to delete yourself.'));
        }

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findDelete($id, $this->User->role())->first();
        if (empty($user)) {
            throw new NotFoundException(__('The user does not exist or has been already deleted.'));
        }

        return $user;
    }

    /**
     * Validate the delete operation.
     *
     * @param \App\Model\Entity\User $user The target user
     * @throws \App\Error\Exception\CustomValidationException if the user is sole manager of a group
     * @throws \App\Error\Exception\CustomValidationException if the user is sole owner of a shared resource
     * @throws \App\Error\Exception\CustomValidationException if the user is sole manager of a group that is the
     *  sole owner of a shared resource
     * @return void
     */
    protected function _validateDelete(User $user)
    {
        // Check business rules
        // Returning the validation error is not meaningful enough so we may need to
        // build the proper anwer with list of incriminated groups / resources
        if (!$this->Users->checkRules($user, RulesChecker::DELETE)) {
            $errors = $user->getErrors();
            $body = [];
            $msg = __('The user cannot be deleted.');

            if (isset($errors['id']['soleManagerOfNonEmptyGroup'])) {
                $groupIds = $this->GroupsUsers
                    ->findNonEmptyGroupsWhereUserIsSoleManager($user->id)
                    ->all()
                    ->extract('group_id')
                    ->toArray();
                $findGroupsOptions = [];
                $findGroupsOptions['contain']['groups_users.user.profile'] = true;
                $groups = $this->Groups->findAllByIds($groupIds, $findGroupsOptions);
                $body['errors']['groups']['sole_manager'] = $groups;
                $msg .= ' ' . $errors['id']['soleManagerOfNonEmptyGroup'];
            }

            if (isset($errors['id']['soleOwnerOfSharedContent'])) {
                $acoType = PermissionsTable::RESOURCE_ACO;
                $resourcesIds = $this->Permissions
                    ->findSharedAcosByAroIsSoleOwner($acoType, $user->id, ['checkGroupsUsers' => true])
                    ->all()
                    ->extract('aco_foreign_key')->toArray();
                if ($resourcesIds) {
                    $findResourcesOptions = [];
                    $findResourcesOptions['contain']['permissions.user.profile'] = true;
                    $findResourcesOptions['contain']['permissions.group'] = true;
                    $resources = $this->Resources->findAllByIds($user->id, $resourcesIds, $findResourcesOptions);
                    $body['errors']['resources']['sole_owner'] = $resources;
                    $msg .= ' ' . $errors['id']['soleOwnerOfSharedContent'];
                }

                if (Configure::read('passbolt.plugins.folders.enabled')) {
                    $foldersIds = $this->Permissions
                        ->findSharedAcosByAroIsSoleOwner(PermissionsTable::FOLDER_ACO, $user->id, [
                            'checkGroupsUsers' => true,
                        ])
                        ->all()
                        ->extract('aco_foreign_key')
                        ->toArray();
                    if ($foldersIds) {
                        $findFoldersOptions = [];
                        $findFoldersOptions['contain']['permissions.user.profile'] = true;
                        $findFoldersOptions['contain']['permissions.group'] = true;
                        $findFoldersOptions['filter']['has-id'] = $foldersIds;
                        /** @var \Passbolt\Folders\Model\Table\FoldersTable $foldersTable */
                        $foldersTable = TableRegistry::getTableLocator()->get('Passbolt/Folders.Folders');
                        $folders = $foldersTable->findIndex($user->id, $findFoldersOptions);
                        $body['errors']['folders']['sole_owner'] = $folders;
                        $msg .= ' ' . $errors['id']['soleOwnerOfSharedContent'];
                    }
                }
            }

            $groupsToDeleteIds = $this->GroupsUsers
                ->findGroupsWhereUserOnlyMember($user->id)
                ->all()
                ->extract('group_id')
                ->toArray();
            if ($groupsToDeleteIds) {
                $groupsToDelete = $this->Groups->findAllByIds($groupsToDeleteIds);
                $body['groups_to_delete'] = $groupsToDelete;
            }

            throw new CustomValidationException($msg, $body);
        }
    }

    /**
     * Transfer the group managers which blocked the user delete
     *
     * @param \App\Model\Entity\User $user entity
     * @throws \Cake\Http\Exception\BadRequestException The groups that required a change are not all satisfied
     * @return void
     */
    protected function _transferGroupsManagers(User $user)
    {
        $managers = $this->request->getData('transfer.managers');
        if (empty($managers)) {
            return;
        }

        $groupsUsersIdsToUpdate = Hash::extract($managers, '{n}.id');
        foreach ($groupsUsersIdsToUpdate as $id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The groups users identifiers must be valid UUID.'));
            }
        }

        $groupsIdsToUpdate = Hash::extract($managers, '{n}.group_id');
        sort($groupsIdsToUpdate);

        $groupsIdsBlockingDelete = $this->GroupsUsers
            ->findNonEmptyGroupsWhereUserIsSoleManager($user->id)
            ->all()
            ->extract('group_id')
            ->toArray();
        sort($groupsIdsBlockingDelete);

        // If all the groups that are requiring a change are not satisfied, throw an exception.
        if ($groupsIdsToUpdate != $groupsIdsBlockingDelete) {
            throw new BadRequestException('The transfer is not authorized.');
        }

        // Update all the groups users given as parameter as long as they are relative
        // to a group which blocked the delete process.
        $this->GroupsUsers->updateAll([
            'is_admin' => true,
        ], [
            'id IN' => $groupsUsersIdsToUpdate,
            'group_id IN' => $groupsIdsBlockingDelete,
        ]);
    }

    /**
     * Transfer the content permissions which blocked the user delete
     *
     * @param \App\Model\Entity\User $user entity
     * @throws \Cake\Http\Exception\BadRequestException if the array of manager is
     * @return void
     */
    protected function _transferContentOwners(User $user)
    {
        $owners = $this->request->getData('transfer.owners');
        if (empty($owners)) {
            return;
        }

        $permissionsIdsToUpdate = Hash::extract($owners, '{n}.id');
        foreach ($permissionsIdsToUpdate as $id) {
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The permissions identifiers must be valid UUID.'));
            }
        }

        $contentIdsToUpdate = Hash::extract($owners, '{n}.aco_foreign_key');
        sort($contentIdsToUpdate);

        $contentIdBlockingDelete = $this->Permissions
            ->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $user->id, ['checkGroupsUsers' => true])
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();

        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $foldersIdsBlockingDelete = $this->Permissions
                ->findSharedAcosByAroIsSoleOwner(PermissionsTable::FOLDER_ACO, $user->id, ['checkGroupsUsers' => true])
                ->all()
                ->extract('aco_foreign_key')
                ->toArray();
            $contentIdBlockingDelete = array_merge($contentIdBlockingDelete, $foldersIdsBlockingDelete);
        }
        sort($contentIdBlockingDelete);

        // If all the resources that are requiring a change are not satisfied, throw an exception.
        if ($contentIdsToUpdate != $contentIdBlockingDelete) {
            throw new BadRequestException('The transfer is not authorized');
        }

        // Update all the permissions given as parameter as long as they are
        // relative to a content which blocked the delete process.
        $this->Permissions->updateAll([
            'type' => Permission::OWNER,
        ], [
            'id IN' => $permissionsIdsToUpdate,
            'aco_foreign_key IN' => $contentIdBlockingDelete,
        ]);
    }

    /**
     * Send email notification
     *
     * @param \App\Model\Entity\User $deletedUser entity
     * @param array $groupIds list of Group entity user was member of
     * @return void
     */
    protected function _notifyUsers(User $deletedUser, array $groupIds)
    {
        $event = new Event(static::DELETE_SUCCESS_EVENT_NAME, $this, [
            'user' => $deletedUser,
            'groupsIds' => $groupIds,
            'deletedBy' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
