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
use App\Model\Entity\Permission;
use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class ResourcesUpdateController extends AppController
{
    /**
     * Resource Update action
     *
     * @param string $id The identifier of the resource to update.
     * @throws NotFoundException If the resource does not exist.
     * @throws NotFoundException If the resource is soft deleted.
     * @throws NotFoundException If the user does not have access to the resource.
     * @throws BadRequestException If the resource id is not a valid uuid.
     * @return void
     */
    public function update($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        $this->loadModel('Resources');

        // Retrieve the resource to update and all its secrets.
        try {
            $resource = $this->Resources->get($id, ['contain' => ['Secrets']]);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // The user can access the resource.
        if (!$this->Resources->hasAccess($this->User->id(), $id, Permission::UPDATE)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // Patch and validate the entity
        $this->_patchAndValidateEntity($resource);

        // Save the entity
        if (!$this->Resources->save($resource)) {
            $this->_handleValidationError($resource);
            throw new InternalErrorException(__('The resource could not be updated. Try again later.'));
        }

        // Retrieve the updated resource.
        $options = [
            'contain' => ['creator' => true, 'favorite' => true, 'modifier' => true, 'secret' => true, 'permission' => true]
        ];
        $output = $this->Resources->findView($this->User->id(), $resource->id, $options)->first();

        $this->_notifyUser($resource);
        $this->success(__('The resource has been updated successfully.'), $output);
    }

    /**
     * Build the resource entity from user input
     *
     * @param \Cake\Datasource\EntityInterface $resource Resource
     * @return void
     */
    protected function _patchAndValidateEntity(EntityInterface $resource)
    {
        $data = $this->_formatRequestData();

        // Enforce data.
        $data['modified_by'] = $this->User->id();

        // Retrieve the existing secrets ids and use to ensure that cakephp will update the existing secretes.
        if (!empty($data['secrets'])) {
            foreach ($data['secrets'] as $key => $dataSecret) {
                if (isset($dataSecret['user_id'])) {
                    $arr = Hash::extract($resource->secrets, "{n}[user_id={$dataSecret['user_id']}].id");
                    $data['secrets'][$key]['id'] = reset($arr);
                }
            }
        }

        // Build entity and perform basic check
        $resource = $this->Resources->patchEntity($resource, $data, [
            'accessibleFields' => [
                'name' => true,
                'username' => true,
                'uri' => true,
                'description' => true,
                'modified_by' => true,
                'secrets' => true,
            ],
            'associated' => [
                'Secrets' => [
                    'validate' => 'saveResource',
                    'accessibleFields' => [
                        'id' => true,
                        'user_id' => true,
                        'data' => true
                    ]
                ]
            ]
        ]);

        // Handle validation errors if any at this stage.
        $this->_handleValidationError($resource);
    }

    /**
     * Format request data formatted for API v1 to API v2 format if needed.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $output = [];
        $data = $this->request->getData();

        if (isset($data['Resource'])) {
            $output = $data['Resource'];
            if (isset($data['Secret'])) {
                $output['secrets'] = $data['Secret'];
            }
        } else {
            $output = $data;
        }

        return $output;
    }

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $resource entity
     * @throws NotFoundException
     * @throws ValidationException
     * @return void
     */
    protected function _handleValidationError($resource)
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            if (isset($errors['id']['resource_is_not_soft_deleted'])
                || isset($errors['id']['has_resource_access'])) {
                throw new NotFoundException(__('The resource does not exist.'));
            }

            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->Resources);
        }
    }

    /**
     * Send email notification
     *
     * @param \App\Model\Entity\Resource $resource Resource
     * @return void
     */
    protected function _notifyUser(\App\Model\Entity\Resource $resource)
    {
        $event = new Event('ResourcesUpdateController.update.success', $this, [
            'resource' => $resource
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
