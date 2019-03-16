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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Network\Exception\InternalErrorException;
use Cake\Network\Exception\NotFoundException;
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
        'whiteList' => ['limit', 'page']
    ];

    /**
     * Initialize.
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
     */
    public function viewByResource(string $resourceId = null)
    {
        // TODO: check should be logged in and have access to the resource.

        // Get pagination options.
        $options = $this->Paginator->mergeOptions(null, $this->paginate);

        try {
            $actionLogFinder = new ActionLogsFinder();
            $userLogs = $actionLogFinder->findForResource($resourceId, $options);
        } catch (PageOutOfBoundsException $e) {
            $userLogs = [];
        }

        $this->success(__('The operation was successful.'), $userLogs);
    }
}
