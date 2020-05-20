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
use App\Model\Entity\Resource;
use App\Model\Table\ResourcesTable;
use App\Service\Resources\ResourcesUpdateService;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

/**
 * @property ResourcesTable Resources
 */
class ResourcesUpdateController extends AppController
{
    /**
     * Resource Update action
     *
     * @param string $id The identifier of the resource to update.
     * @return void
     * @throws NotFoundException If the resource is soft deleted.
     * @throws NotFoundException If the user does not have access to the resource.
     * @throws BadRequestException If the resource id is not a valid uuid.
     * @throws \Exception If an unexpected error occurred
     * @throws NotFoundException If the resource does not exist.
     */
    public function update($id)
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The resource id is not valid.'));
        }

        $uac = $this->User->getAccessControl();
        $resourceUpdateService = new ResourcesUpdateService();
        $data = $this->getData();

        /** @var Resource $resource */
        $resource = $resourceUpdateService->update($uac, $id, $data);

        // Retrieve the updated resource.
        $options = [
            'contain' => ['creator' => true, 'favorite' => true, 'modifier' => true, 'secret' => true, 'permission' => true],
        ];
        if (Configure::read('passbolt.plugins.tags.enabled')) {
            $options['contain']['tag'] = true;
        }
        $this->loadModel('Resources');
        $output = $this->Resources->findView($this->User->id(), $resource->id, $options)->first();

        $this->success(__('The resource `{0}` has been updated successfully.', $resource->name), $output);
    }

    /**
     * Get and format request data formatted for API v1 to API v2 format if needed.
     *
     * @return array
     */
    protected function getData()
    {
        $output = [];
        $data = $this->request->getData();

        if (isset($data['Resource'])) {
            $output = $data['Resource'];
            if (isset($data['Secret'])) {
                $output['secrets'] = $data['Secret'];
            }
        } else {
            $output = $data;
        }

        return $output;
    }
}
