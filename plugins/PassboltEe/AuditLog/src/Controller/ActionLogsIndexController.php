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
 * @since         3.11.0
 */

namespace Passbolt\AuditLog\Controller;

use App\Controller\AppController;

class ActionLogsIndexController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'ActionLogs',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'ActionLogs.created',
        ],
        'order' => [
            'ActionLogs.created' => 'desc', // Default sorted field
        ],
    ];

    /**
     *  Action logs index view
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the date passed could not be parsed
     * @throws \Cake\Http\Exception\BadRequestException if the userId is not a valid UUID
     */
    public function index(): void
    {
        $this->User->assertIsAdmin();

        // Whitelisted filters and contain parameters
        $options = $this->QueryString->get([
            'filter' => ['has-users', 'is-success', 'created-after', 'created-before'],
            'contain' => ['user', 'user.profile'],
            'order' => ['created'],
        ]);

        /** @var \Passbolt\Log\Model\Table\ActionLogsTable $LogsTable */
        $LogsTable = $this->fetchTable('Passbolt/Log.ActionLogs');
        $logs = $LogsTable->index($options);

        $this->paginate($logs);
        $this->success(__('The operation was successful.'), $logs);
    }
}
