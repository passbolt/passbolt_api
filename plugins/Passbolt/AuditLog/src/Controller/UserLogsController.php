<?php
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
use Cake\Datasource\Exception\PageOutOfBoundsException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;
use Passbolt\AuditLog\Utility\ActionLogsFinder;

class UserLogsController extends AppController
{
    /**
     * Paginator options
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
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * View action logs for a given resource.
     * @param string $resourceId resource id
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource id has the wrong format
     * @throws NotFoundException if the user cannot access the given resource, or if the resource does not exist
     */
    public function viewByResource(string $resourceId = null)
    {
        // Check request sanity
        if (!Validation::uuid($resourceId)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        // Get pagination options.
        $options = $this->Paginator->mergeOptions(null, $this->paginate);

        try {
            $actionLogFinder = new ActionLogsFinder();
            $userLogs = $actionLogFinder->findForResource($this->User->getAccessControl(), $resourceId, $options);
        } catch (PageOutOfBoundsException $e) {
            $userLogs = [];
        }

        $this->success(__('The operation was successful.'), $userLogs);
    }

    /**
     * View action logs for a given folder.
     * @param string $folderId folder id
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource id has the wrong format
     * @throws NotFoundException if the user cannot access the given resource, or if the resource does not exist
     */
    public function viewByFolder(string $folderId = null)
    {
        // Check request sanity
        if (!Validation::uuid($folderId)) {
            throw new BadRequestException(__('The folder id is not valid.'));
        }

        // Get pagination options.
        $options = $this->Paginator->mergeOptions(null, $this->paginate);

        try {
            $actionLogFinder = new ActionLogsFinder();
            $userLogs = $actionLogFinder->findForFolder($this->User->getAccessControl(), $folderId, $options);
        } catch (PageOutOfBoundsException $e) {
            $userLogs = [];
        }

        $this->success(__('The operation was successful.'), $userLogs);
    }
}
