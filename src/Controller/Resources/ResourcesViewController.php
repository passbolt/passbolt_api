<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
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
namespace App\Controller\Resources;

use App\Controller\AppController;
use Cake\Network\Exception\BadRequestException;
use Cake\Network\Exception\NotFoundException;
use Cake\Validation\Validation;

class ResourcesViewController extends AppController
{
    /**
     * Resource View action
     *
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @param string $id uuid Identifier of the resource
     * @return void
     */
    public function view($id)
    {
        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        $this->loadModel('Resources');

        // Retrieve and sanity the query options.
        $whitelist = ['contain' => ['secret', 'creator', 'modifier']];
        $options = $this->QueryString->get($whitelist);

        // If the result contains the secrets, include only the current user secret.
        if (isset($options['contain']['secret']) && $options['contain']['secret']) {
            $options['user_id'] = $this->User->id();
        }

        // Filter by resource id.
        $options['id'] = $id;

        // Retrieve the resource.
        $resource = $this->Resources->findView($options)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        return $this->success($resource);
    }
}
