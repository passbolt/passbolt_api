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

namespace App\Controller\Resources;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Resource;
use App\Model\Entity\Role;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class ResourcesDeleteController extends AppController
{
    /**
     * Resource Delete action
     *
     * @param string $id The identifier of the resource to delete.
     * @throws NotFoundException If the resource does not exist.
     * @throws NotFoundException If the resource is soft deleted.
     * @throws NotFoundException If the user does not have access to the resource.
     * @throws ForbiddenException If the user does not have the permission to delete the resource.
     * @throws BadRequestException If the resource id is not a valid uuid.
     * @throws InternalErrorException if the resource could not be saved for other reasons
     * @return void
     */
    public function delete($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        $this->loadModel('Resources');

        // Retrieve the resource to delete.
        try {
            $resource = $this->Resources->get($id);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // Get the list of users who have access to the resource
        // useful to do now to notify users later, since it wont be possible to after delete
        $Users = TableRegistry::getTableLocator()->get('Users');
        $options = ['contain' => ['Roles'], 'filter' => ['has-access' => [$resource->id]]];
        $users = $Users->findIndex(Role::USER, $options)->all();

        // Update the entity to delete=1 and drop associated permissions
        if (!$this->Resources->softDelete($this->User->id(), $resource)) {
            $this->_handleDeleteError($resource);
            throw new InternalErrorException(__('Could not delete the resource. Please try again later.'));
        }

        $this->_notifyUser($resource, $users);
        $this->success(__('The resource was deleted'));
    }

    /**
     * Manage delete errors.
     *
     * @param \Cake\Datasource\EntityInterface $resource entity
     * @throws NotFoundException
     * @throws ValidationException
     * @return void
     */
    protected function _handleDeleteError($resource)
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
            if ($this->Resources->hasAccess($this->User->id(), $resource->id)) {
                throw new ForbiddenException(__('You do not have the permission to delete this resource.'));
            }
            throw new NotFoundException(__('The resource does not exist.'));
        }
        throw new ValidationException(__('Could not delete the resource.'), $resource, $this->Resources);
    }

    /**
     * Send email notification
     *
     * @param resource $resource Resource
     * @param \Cake\Datasource\ResultSetInterface $users list of User entity who had access to the resource
     * @return void
     */
    protected function _notifyUser(Resource $resource, \Cake\Datasource\ResultSetInterface $users)
    {
        $event = new Event('ResourcesDeleteController.delete.success', $this, [
            'resource' => $resource,
            'deletedBy' => $this->User->id(),
            'users' => $users
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
