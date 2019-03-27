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
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;

class ResourcesIndexController extends AppController
{
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
            'contain' => ['creator', 'favorite', 'modifier', 'permission', 'permissions.user.profile', 'permissions.group', 'secret'],
            'filter' => ['is-favorite', 'is-shared-with-group', 'is-owned-by-me', 'is-shared-with-me', 'has-id'],
            'order' => ['Resource.modified']
        ];

        if (Configure::read('passbolt.plugins.tags')) {
            $whitelist['contain'][] = 'tag';
            $whitelist['filter'][] = 'has-tag';
        }
        $options = $this->QueryString->get($whitelist);

        // Retrieve the resources.
        $resources = $this->Resources->findIndex($this->User->id(), $options);
        $this->_logSecretAccesses($resources->all()->toArray());
        $this->success(__('The operation was successful.'), $resources);
    }

    /**
     * Log secrets accesses in secretAccesses table.
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
