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
 * @since         4.10.0
 */

namespace Passbolt\ResourceTypes\Controller;

use App\Controller\AppController;
use Passbolt\ResourceTypes\Service\ResourceTypesDeleteService;

class ResourceTypesDeleteController extends AppController
{
    /**
     * Delete a resource type.
     *
     * @param string $id The identifier of resource type to delete.
     * @throws \Cake\Http\Exception\BadRequestException
     * @throws \Cake\Http\Exception\NotFoundException
     * @return void
     */
    public function delete(string $id)
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        (new ResourceTypesDeleteService())->delete($this->User->getAccessControl(), $id);
        $this->success(__('The operation was successful.'));
    }
}
