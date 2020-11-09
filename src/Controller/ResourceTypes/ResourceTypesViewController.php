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
 * @since         3.0.0
 */

namespace App\Controller\ResourceTypes;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property ResourceTypesTable ResourceTypes
 */
class ResourceTypesViewController extends AppController
{
    /**
     * Resource Types View action
     *
     * @param string $id uuid Identifier of the resource type
     * @throws \Cake\Http\Exception\NotFoundException if plugin is disabled by admin
     * @throws \Cake\Http\Exception\BadRequestException if the resource id is not a uuid
     * @throws \Cake\Http\Exception\NotFoundException if the resource does not exist
     * @return void
     */
    public function view(string $id)
    {
        if (!Configure::read('passbolt.plugins.resourceTypes.enabled')) {
            throw new NotFoundException();
        }
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        try {
            $this->loadModel('ResourceTypes');
            $resourceType = $this->ResourceTypes->get($id);
        } catch (Exception $exception) {
            throw new NotFoundException(__('The resource type does not exist.'));
        }

        $this->success(__('The operation was successful.'), $resourceType);
    }
}
