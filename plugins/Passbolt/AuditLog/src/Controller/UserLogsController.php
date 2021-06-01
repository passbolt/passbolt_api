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
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace Passbolt\AuditLog\Controller;

use App\Controller\AppController;
use App\Model\Entity\Resource;
use Cake\Datasource\Exception\PageOutOfBoundsException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use Passbolt\AuditLog\Utility\ActionLogsFinder;
use Passbolt\Folders\Model\Entity\Folder;

class UserLogsController extends AppController
{
    /**
     * Paginator options
     *
     * @var array
     */
    public $paginate = [
        'limit' => 5,
        'maxLimit' => 20,
        'whiteList' => ['limit', 'page'],
    ];

    /**
     * Initialize
     *
     * @throws \Exception If a component class cannot be found.
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * View action logs for a given resource.
     *
     * @param string $resourceId resource id
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource id has the wrong format
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot access the given resource, or if the resource does not exist
     */
    public function viewByResource(?string $resourceId = null)
    {
        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource identifier should be a valid UUID.'));
        }

        $this->viewByEntity(Resource::class, $resourceId);
    }

    /**
     * View action logs for a given folder.
     *
     * @param string $folderId folder id
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource id has the wrong format
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot access the given resource, or if the resource does not exist
     */
    public function viewByFolder(?string $folderId = null)
    {
        // Check request sanity
        if (!Validation::uuid($folderId)) {
            throw new BadRequestException(__('The folder id is not valid.'));
        }

        $this->viewByEntity(Folder::class, $folderId);
    }

    /**
     * @param string $entityType Entity model name.
     * @param string $entityId Uuid of the entity handled.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot access the given entity, or if the entity does not exist
     * @throws \Cake\Http\Exception\InternalErrorException if an entity else than a resource or a folder is handled.
     */
    private function viewByEntity(string $entityType, string $entityId): void
    {
        // Get pagination options.
        $options = $this->Paginator->mergeOptions('', $this->paginate);

        try {
            $actionLogFinder = new ActionLogsFinder();
            if ($entityType === Resource::class) {
                $userLogs = $actionLogFinder->findForResource($this->User->getAccessControl(), $entityId, $options);
            } elseif ($entityType === Folder::class) {
                $userLogs = $actionLogFinder->findForFolder($this->User->getAccessControl(), $entityId, $options);
            } else {
                throw new InternalErrorException(__('Resources or folders supported only.'));
            }
        } catch (PageOutOfBoundsException $e) {
            $userLogs = [];
        }

        $this->success(__('The operation was successful.'), $userLogs);
    }
}
