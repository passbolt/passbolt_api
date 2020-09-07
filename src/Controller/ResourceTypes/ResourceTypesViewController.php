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
 * @since         3.0.0
 */

namespace App\Controller\ResourceTypes;

use App\Controller\AppController;
use Cake\Core\Exception\Exception;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class ResourceTypesViewController extends AppController
{
    /**
     * Resource Types View action
     *
     * @param string $id uuid Identifier of the resource type
     * @throws BadRequestException if the resource id is not a uuid
     * @throws NotFoundException if the resource does not exist
     * @return void
     */
    public function view($id)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }
        try {
            $resourceTypesTable = TableRegistry::getTableLocator()->get('ResourceTypes');
            $resourceType = $resourceTypesTable->get($id);
        } catch (Exception $exception) {
            throw new NotFoundException(__('The resource type does not exist.'));
        }

        $this->success(__('The operation was successful.'), $resourceType);
    }
}
