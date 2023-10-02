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

namespace App\Controller\Resources;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use App\Model\Table\PermissionsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * ResourcesDeleteController Class
 */
class ResourcesDeleteController extends AppController
{
    public const DELETE_SUCCESS_EVENT_NAME = 'ResourcesDeleteController.delete.success';

    /**
     * @var \App\Model\Table\ResourcesTable
     */
    protected $Resources;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Resources = $this->fetchTable('Resources');
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Resource Delete action
     *
     * @param string $id The identifier of the resource to delete.
     * @throws \Cake\Http\Exception\NotFoundException If the resource does not exist.
     * @throws \Cake\Http\Exception\NotFoundException If the resource is soft deleted.
     * @throws \Cake\Http\Exception\NotFoundException If the user does not have access to the resource.
     * @throws \Cake\Http\Exception\ForbiddenException If the user does not have the permission to delete the resource.
     * @throws \Cake\Http\Exception\BadRequestException If the resource id is not a valid uuid.
     * @throws \Cake\Http\Exception\InternalErrorException if the resource could not be saved for other reasons
     * @return void
     */
    public function delete(string $id): void
    {
        $this->assertJson();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        // Retrieve the resource to delete.
        try {
            $resource = $this->Resources->get($id);
            $originalResource = clone $resource;
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // Get the list of users who have access to the resource
        // useful to do now to notify users later, since it wont be possible to after delete
        // The logged in user will not be notified.
        $options = ['contain' => ['role'], 'filter' => ['has-access' => [$resource->id]]];
        $users = $this->Users
            ->findIndex(Role::USER, $options)
            ->find('locale')
            ->where(['Users.id !=' => $this->User->id()])
            ->all();

        // Update the entity to delete=1, clear uri/desc/username and drop associated permissions
        if (!$this->Resources->softDelete($this->User->id(), $resource)) {
            $this->_handleDeleteError($resource);
            throw new InternalErrorException('Could not delete the resource. Please try again later.');
        }

        $this->_notifyUser($originalResource, $users);
        $this->success(__('The resource has been deleted successfully.'));
    }

    /**
     * Manage delete errors.
     *
     * @param \App\Model\Entity\Resource $resource entity
     * @throws \Cake\Http\Exception\NotFoundException
     * @throws \App\Error\Exception\ValidationException
     * @return void
     */
    protected function _handleDeleteError(Resource $resource): void
    {
        $errors = $resource->getErrors();
        if (empty($errors)) {
            return;
        }
        if (isset($errors['deleted']['is_not_soft_deleted'])) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        if (isset($errors['id']['has_access'])) {
            // If the user has a read access return a 403, otherwise return a 404 to avoid data leak.
            $acoType = PermissionsTable::RESOURCE_ACO;
            if ($this->Resources->Permissions->hasAccess($acoType, $resource->id, $this->User->id())) {
                throw new ForbiddenException(__('You do not have the permission to delete this resource.'));
            }
            throw new NotFoundException(__('The resource does not exist.'));
        }
        throw new ValidationException(__('Could not delete the resource.'), $resource, $this->Resources);
    }

    /**
     * Send email notification
     *
     * @param \App\Model\Entity\Resource $resource Resource
     * @param \Cake\Datasource\ResultSetInterface $users Users who had access to the resource, deleter excluded
     * @return void
     */
    protected function _notifyUser(Resource $resource, ResultSetInterface $users): void
    {
        $event = new Event(static::DELETE_SUCCESS_EVENT_NAME, $this, [
            'resource' => $resource,
            'deletedBy' => $this->User->id(),
            'users' => $users,
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
