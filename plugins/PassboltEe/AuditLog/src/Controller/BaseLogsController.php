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
 * @since         3.7.0
 */

namespace Passbolt\AuditLog\Controller;

use App\Controller\AppController;
use App\Error\Exception\FeaturePluginDisabledException;
use Cake\Datasource\Exception\PageOutOfBoundsException;
use Passbolt\AuditLog\Utility\ActionLogResultsParser;
use Passbolt\AuditLog\Utility\BaseActionLogsFinder;

abstract class BaseLogsController extends AppController
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
        'sortableFields' => [
            'ActionLogs.created',
        ],
        'order' => [
            'ActionLogs.created' => 'desc', // Default sorted field
        ],
    ];

    /**
     * Name of the model
     *
     * @return string
     */
    abstract public function getModelName(): string;

    /**
     * Initialize
     *
     * @throws \Exception If a component class cannot be found.
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('ApiPagination', [
            'model' => 'ActionLogs',
        ]);
    }

    /**
     * @param \Passbolt\AuditLog\Utility\BaseActionLogsFinder $logsFinder Log Finder
     * @param string $entityId Uuid of the entity handled.
     * @return void
     * @throws \Cake\Http\Exception\NotFoundException if the user cannot access the given entity, or if the entity does not exist
     */
    protected function viewByEntity(BaseActionLogsFinder $logsFinder, string $entityId): void
    {
        try {
            $logs = $logsFinder->find($this->User->getAccessControl(), $entityId);
            $this->paginate($logs);
            $resultParser = new ActionLogResultsParser($logs->all(), [lcfirst($this->getModelName()) => [$entityId]]);
            $logs = $resultParser->parse();
        } catch (PageOutOfBoundsException | FeaturePluginDisabledException $e) {
            $logs = [];
        }

        $this->success(__('The operation was successful.'), $logs);
    }
}
