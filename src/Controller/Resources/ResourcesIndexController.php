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
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

/**
 * @property \App\Model\Table\ResourcesTable $Resources
 */
class ResourcesIndexController extends AppController
{
    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('BryanCrowe/ApiPagination.ApiPagination', [
            'model' => 'Resources',
        ]);
    }

    public $paginate = [
        'sortableFields' => [
            'Resources.name',
            'Resources.username',
            'Resources.uri',
            'Resources.modified',
        ],
        'order' => [
            'Resources.name' => 'asc', // Default sorted field
        ],
        'limit' => 1000000, // Default number of resources per page,
        'maxLimit' => 1000000, // Maximum amount of items returned (CakePHP default = 100)
    ];

    /**
     * Resource Index action
     *
     * @return void
     */
    public function index()
    {
        $this->loadModel('Resources');

        // Retrieve and sanity the query options.
        $whitelist = [
            'contain' => [
                'creator', 'favorite', 'modifier', 'secret', 'resource-type',
                'permission', 'permissions', 'permissions.user.profile', 'permissions.group',
            ],
            'filter' => ['is-favorite', 'is-shared-with-group', 'is-owned-by-me', 'is-shared-with-me', 'has-id'],
            'order' => ['Resource.modified'], // This is deprecated, use $paginate, left for retro-compatibility.
        ];

        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $whitelist['contain'][] = 'tag';
            $whitelist['filter'][] = 'has-tag';
        }
        if (Configure::read('passbolt.plugins.folders')) {
            $whitelist['filter'][] = 'has-parent';
        }
        $options = $this->QueryString->get($whitelist);

        // Retrieve the resources.
        $resources = $this->Resources->findIndex($this->User->id(), $options);
        $this->_logSecretAccesses($resources->all()->toArray());
        $this->paginate($resources);
        $this->success(__('The operation was successful.'), $resources);
    }

    /**
     * Log secrets accesses in secretAccesses table.
     *
     * @param array $resources resources
     * @return void
     */
    protected function _logSecretAccesses(array $resources)
    {
        if (!$this->Resources->getAssociation('Secrets')->hasAssociation('SecretAccesses')) {
            return;
        }

        foreach ($resources as $resource) {
            if (!isset($resource->secrets)) {
                continue;
            }

            foreach ($resource->secrets as $secret) {
                try {
                    $this->Resources
                        ->getAssociation('Secrets')
                        ->getAssociation('SecretAccesses')
                        ->create($secret, $this->User->getAccessControl());
                } catch (\Exception $e) {
                    throw new InternalErrorException(__('Could not log secret access entry.'));
                }
            }
        }
    }
}
