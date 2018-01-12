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
     * @param string $id uuid Identifier of the resource
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
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
        $whitelist = ['contain' => ['creator', 'favorite', 'modifier', 'permission', 'secret']];
        $options = $this->QueryString->get($whitelist);

        // Retrieve the resource.
        $resource = $this->Resources->findView($this->User->id(), $id, $options)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }
        $this->success(__('The operation was successful.'), $resource);
    }
}
