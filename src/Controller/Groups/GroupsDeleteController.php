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
namespace App\Controller\Groups;

use App\Controller\AppController;
use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Group;
use App\Model\Entity\Permission;
use App\Model\Entity\Role;
use App\Model\Entity\Secret;
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
 * GroupsDeleteController Class
 */
class GroupsDeleteController extends AppController
{
    public const DELETE_SUCCESS_EVENT_NAME = 'GroupsDeleteController.delete.success';

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
        $this->Groups = $this->fetchTable('Groups');
        $this->GroupsUsers = $this->fetchTable('GroupsUsers');
        $this->Permissions = $this->fetchTable('Permissions');
        $this->Resources = $this->fetchTable('Resources');
    }

    /**
     * Group delete dry run action
     *
     * @param string $id group uuid
     * @return void
     */
    public function dryRun(string $id): void
    {
        $this->assertJson();

        $group = $this->_validateRequestData($id);
        $this->_validateDelete($group);
        $resources = $this->Resources->findAllByGroupAccess($id);
        $this->success(__('The group can be deleted.'), $resources);
    }

    /**
     * Group delete action
     *
     * @param string $id group uuid
     * @param \App\Service\Resources\ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService Service to expire resources that were consumed by users who lost access to them.
     * @return void
     * @throws \Exception
     */
    public function delete(
        string $id,
        ResourcesExpireResourcesServiceInterface $resourcesExpireResourcesService
    ) {
        $this->assertJson();
        $group = null;

        $this->GroupsUsers->getConnection()->transactional(function () use ($id, &$group, $resourcesExpireResourcesService) { //phpcs:ignore
            $group = $this->_validateRequestData($id);
            $this->_transferContentOwners($group);
            $this->_validateDelete($group);
            $entitiesChanges = $this->Groups->softDelete($group, ['checkRules' => false]);
            if (!$entitiesChanges) {
                throw new InternalErrorException('Could not delete the group, please try again later.');
            }
            $deletedSecrets = $entitiesChanges->getDeletedEntities(Secret::class);
            $resourcesExpireResourcesService->expireResourcesForSecrets($deletedSecrets);
        });

        $this->_notifyUsers($group);
        $this->success(__('The group was deleted successfully.'));
    }

    /**
     * Assert request sanity and return the sanitized data
     *
     * @param string $id group uuid
     * @throws \Cake\Http\Exception\ForbiddenException if current group is not an admin
     * @throws \Cake\Http\Exception\BadRequestException if the group uuid id invalid
     * @throws \Cake\Http\Exception\NotFoundException if the group does not exist or is already deleted
     * @return \App\Model\Entity\Group $group entity
     */
    protected function _validateRequestData(string $id)
    {
        // Admin can delete all groups
        if ($this->User->role() !== Role::ADMIN) {
            if (!$this->GroupsUsers->isManager($this->User->id(), $id)) {
                throw new ForbiddenException(__('You are not authorized to access that location.'));
            }
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The group identifier should be a valid UUID.'));
        }

        /** @var \App\Model\Entity\Group $group */
        $group = $this->Groups->findView($id, ['contain' => ['groups_users' => true]])->first();
        if (empty($group)) {
            throw new NotFoundException(__('The group does not exist or has been already deleted.'));
        }

        return $group;
    }

    /**
     * Validate the delete operation
     *
     * @param \App\Model\Entity\Group $group The target group
     * @throws \App\Error\Exception\CustomValidationException if the group is sole owner of a shared resource
     * @return void
     */
    protected function _validateDelete(Group $group)
    {
        // Check business rules
        // Returning the validation error is not meaningful enough so we need to
        // build the proper answer with list of incriminated resources that needs transfer
        // of ownership if any
        if (!$this->Groups->checkRules($group, RulesChecker::DELETE)) {
            $errors = $group->getErrors();
            $msg = __('The group cannot be deleted.') . ' ';

            if (isset($errors['id']['soleOwnerOfSharedContent'])) {
                $resourcesIds = $this->Permissions
                    ->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)
                    ->all()
                    ->extract('aco_foreign_key')
                    ->toArray();
                $body = [];
                if ($resourcesIds) {
                    $findResourcesOptions = [];
                    $findResourcesOptions['contain']['permissions.user.profile'] = true;
                    $findResourcesOptions['contain']['permissions.group'] = true;
                    $resources = $this->Resources->findAllByIds($group->id, $resourcesIds, $findResourcesOptions);
                    $body['errors']['resources']['sole_owner'] = $resources;
                    $msg .= $errors['id']['soleOwnerOfSharedContent'];
                }

                if (Configure::read('passbolt.plugins.folders.enabled')) {
                    $foldersIds = $this->Permissions
                        ->findSharedAcosByAroIsSoleOwner(PermissionsTable::FOLDER_ACO, $group->id)
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
                        $folders = $foldersTable->findIndex($group->id, $findFoldersOptions);
                        $body['errors']['folders']['sole_owner'] = $folders;
                        $msg .= $errors['id']['soleOwnerOfSharedContent'];
                    }
                }
                throw new CustomValidationException($msg, $body);
            }
        }
    }

    /**
     * Transfer the resources permissions which blocked the group delete
     *
     * @param \App\Model\Entity\Group $group entity
     * @throws \Cake\Http\Exception\BadRequestException if the array of manager is
     * @return void
     */
    protected function _transferContentOwners(Group $group): void
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
            ->findSharedAcosByAroIsSoleOwner(PermissionsTable::RESOURCE_ACO, $group->id)
            ->all()
            ->extract('aco_foreign_key')
            ->toArray();
        if (Configure::read('passbolt.plugins.folders.enabled')) {
            $foldersIdsBlockingDelete = $this->Permissions
                ->findSharedAcosByAroIsSoleOwner(PermissionsTable::FOLDER_ACO, $group->id)
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

        // Update all the permissions given as parameter as long as they are relative to a resource
        // which blocked the delete process.
        $this->Permissions->updateAll([
            'type' => Permission::OWNER,
        ], [
            'id IN' => $permissionsIdsToUpdate,
            'aco_foreign_key IN' => $contentIdBlockingDelete,
        ]);
    }

    /**
     * Notify the users that their group has been deleted
     *
     * @param \App\Model\Entity\Group $group Group
     * @return void
     */
    protected function _notifyUsers(Group $group)
    {
        $event = new Event(static::DELETE_SUCCESS_EVENT_NAME, $this, [
            'group' => $group,
            'userId' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
