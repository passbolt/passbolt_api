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

namespace App\Controller\Share;

use App\Controller\AppController;
use App\Model\Entity\Permission;
use App\Model\Entity\Resource;
use App\Model\Table\PermissionsTable;
use App\Service\Resources\ResourcesShareService;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 * @property \App\Model\Table\UsersTable $Users
 */
class ShareController extends AppController
{
    public const SHARE_SUCCESS_EVENT_NAME = 'ShareController.share.success';

    /**
     * Share Dry Run action
     *
     * @param string $resourceId The identifier of the resource to dry run a share on
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @throws \Cake\Http\Exception\NotFoundException if the resource is soft deleted
     * @throws \Cake\Http\Exception\NotFoundException if the user does not have access to the resource
     * @throws \App\Error\Exception\ValidationException if the provided changes do not validate
     * @return void
     * @throws \Exception If an expected error occurred
     */
    public function dryRun(string $resourceId): void
    {
        $this->loadModel('Resources');
        $this->loadModel('Users');

        $uac = $this->User->getAccessControl();
        $this->_assertRequestParameters($resourceId);
        $data = $this->request->getData();
        $changes = Hash::get($data, 'permissions') ?? [];
        $resourcesShareService = new ResourcesShareService();
        $dryRunResult = $resourcesShareService->shareDryRun($uac, $resourceId, $changes);

        $output = $this->_formatDryRunResult($dryRunResult['added'], $dryRunResult['deleted']);
        $this->success(__('The operation was successful.'), $output);
    }

    /**
     * Share action
     *
     * @param string $resourceId The identifier of the resource to share
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @throws \Cake\Http\Exception\NotFoundException if the resource is soft deleted
     * @throws \Cake\Http\Exception\NotFoundException if the user does not have access to the resource
     * @throws \App\Error\Exception\ValidationException if the provided changes do not validate
     * @throws \Cake\Http\Exception\InternalErrorException if something else went wrong during the save
     * @return void
     * @throws \Exception If an expected error occurred
     */
    public function share(string $resourceId): void
    {
        $this->loadModel('Resources');
        $this->loadModel('Users');

        $uac = $this->User->getAccessControl();
        $this->_assertRequestParameters($resourceId);
        $data = $this->request->getData();
        $permissions = Hash::get($data, 'permissions') ?? [];
        $secrets = Hash::get($data, 'secrets') ?? [];

        $resourcesShareService = new ResourcesShareService();
        $resource = $resourcesShareService->share($uac, $resourceId, $permissions, $secrets);

        $this->_notifyUsers($resource, $data);
        $this->success(__('The operation was successful.'));
    }

    /**
     * Assert the request parameters.
     *
     * @param string $resourceId The identifier of the resource to share
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @throws \Cake\Http\Exception\NotFoundException if the resource is soft deleted
     * @throws \Cake\Http\Exception\NotFoundException if the user does not have access to the resource
     * @return void
     */
    protected function _assertRequestParameters(string $resourceId): void
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
        $acoType = PermissionsTable::RESOURCE_ACO;
        $userId = $this->User->id();
        if (!$this->Resources->Permissions->hasAccess($acoType, $resourceId, $userId, Permission::OWNER)) {
            throw new ForbiddenException(__('You are not authorized to share this resource.'));
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
    private function _formatDryRunResult(array $addedUsersIds, array $removedUsersIds): array
    {
        $result = [
            'changes' => [
                'added' => [],
                'removed' => [],
            ],
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
     * @param Resource $resource affected resource
     * @param array $data changes requested by resource owner
     * @return void
     */
    protected function _notifyUsers(Resource $resource, array $data): void
    {
        $event = new Event(static::SHARE_SUCCESS_EVENT_NAME, $this, [
            'resource' => $resource,
            'changes' => $data,
            'ownerId' => $this->User->id(),
        ]);
        $this->getEventManager()->dispatch($event);
    }
}
