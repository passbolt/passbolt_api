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

namespace App\Controller\Share;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class ShareController extends AppController
{
    /**
     * Share Dry Run action
     *
     * @param string $resourceId The identifier of the resource to dry run a share on
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @throws NotFoundException if the resource is soft deleted
     * @throws NotFoundException if the user does not have access to the resource
     * @throws ValidationException if the provided changes do not validate
     * @return void
     */
    public function dryRun($resourceId)
    {
        $this->loadModel('Resources');
        $this->loadModel('Users');

        // Check request sanity
        $this->_assertRequestParameters($resourceId);

        // Retrieve the resource along with its permissions.
        $resource = $this->Resources->get($resourceId, ['contain' => 'Permissions']);

        // Patch and validate the entity
        $data = $this->_formatRequestData();
        $result = $this->Resources->shareDryRun($resource, $data['permissions']);
        $this->_handleValidationError($resource);

        $output = $this->_formatDryRunResult($result['added'], $result['removed']);
        $this->success(__('The operation was successful.'), $output);
    }

    /**
     * Share action
     *
     * @param string $resourceId The identifier of the resource to share
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @throws NotFoundException if the resource is soft deleted
     * @throws NotFoundException if the user does not have access to the resource
     * @throws ValidationException if the provided changes do not validate
     * @throws InternalErrorException if something else went wrong during the save
     * @return void
     */
    public function share($resourceId)
    {
        $this->loadModel('Resources');
        $this->loadModel('Users');

        // Check request sanity
        $this->_assertRequestParameters($resourceId);

        // Retrieve the resource along with its permissions.
        $resource = $this->Resources->get($resourceId, ['contain' => ['Permissions', 'Secrets']]);

        // Patch and validate the entity
        $data = $this->_formatRequestData();
        if (!$this->Resources->share($resource, $data['permissions'], $data['secrets'])) {
            $this->_handleValidationError($resource);
            throw new InternalErrorException(__('Could not update the password permissions. Please try again later.'));
        }

        $this->_notifyUsers($resource, $data);
        $this->success(__('The operation was successful.'));
    }

    /**
     * Assert the request parameters.
     *
     * @param string $resourceId The identifier of the resource to share
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @throws NotFoundException if the resource is soft deleted
     * @throws NotFoundException if the user does not have access to the resource
     * @return void
     */
    protected function _assertRequestParameters($resourceId)
    {
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        // Retrieve the resource to simulate the share with.
        try {
            $resource = $this->Resources->get($resourceId);
        } catch (RecordNotFoundException $e) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        // The resource is not soft deleted.
        if ($resource->deleted) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        // The user can access the resource.
        if (!$this->Resources->hasAccess($this->User->id(), $resourceId, Permission::OWNER)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
    }

    /**
     * Get and format the request data.
     *
     * @return array
     */
    protected function _formatRequestData()
    {
        $data = $this->request->getData();
        $result = [
            'permissions' => Hash::get($data, 'permissions', []),
            'secrets' => Hash::get($data, 'secrets', []),
        ];
        // Permissions given in V1 format.
        if (isset($data['Permissions'])) {
            $result['permissions'] = Hash::extract($data['Permissions'], '{n}.Permission');
        }
        // Secrets given in V1 format.
        if (isset($data['Secrets'])) {
            $result['secrets'] = Hash::extract($data['Secrets'], '{n}.Secret');
        }

        return $result;
    }

    /**
     * Manage validation errors.
     *
     * @param \Cake\Datasource\EntityInterface $resource The resource to share
     * @throws NotFoundException
     * @throws ValidationException
     * @return void
     */
    protected function _handleValidationError($resource)
    {
        $errors = $resource->getErrors();
        if (!empty($errors)) {
            throw new ValidationException(__('Could not validate resource data.'), $resource, $this->Resources);
        }
    }

    /**
     * Format the result.
     *
     * This entry point is used by the plugin app, and due to the V1 legacy the output body must be
     * formatted as following:
     *
     * [
     *   'changes' => [
     *     'added' => [
     *       ['User' => ['id' => uuid]],
     *       ...
     *     ],
     *     'removed' => [
     *       ['User' => ['id' => uuid]],
     *       ...
     *     ]
     *   ]
     * ]
     *
     * @param array $addedUsersIds The identifiers of the users the secret need to be encrypted
     * @param array $removedUsersIds The identifiers of the users the secret need to be deleted
     * @return array
     */
    private function _formatDryRunResult($addedUsersIds, $removedUsersIds)
    {
        $result = [
            'changes' => [
                'added' => [],
                'removed' => []
            ]
        ];

        // Format the content.
        foreach ($addedUsersIds as $userId) {
            $result['changes']['added'][] = ['User' => ['id' => $userId]];
        }
        foreach ($removedUsersIds as $userId) {
            $result['changes']['removed'][] = ['User' => ['id' => $userId]];
        }

        return $result;
    }

    /**
     * Notify users
     *
     * @param resource $resource affected resource
     * @param array $data changes requested by resource owner
     * @return void
     */
    protected function _notifyUsers(Resource $resource, array $data)
    {
        $event = new Event('ShareController.share.success', $this, [
            'resource' => $resource,
            'changes' => $data,
            'ownerId' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
