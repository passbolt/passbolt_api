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

namespace App\Controller\Permissions;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Validation\Validation;

class PermissionsViewController extends AppController
{
    /**
     * View permissions defined for an aco instance.
     * Only support the entity Resource for now.
     *
     * @param string $acoForeignKey The target aco id.
     * @throws BadRequestException If the parameter acoForeignKey is null
     * @throws BadRequestException If the parameter acoForeignKey is not a valid uuid
     * @throws NotFoundException If the target resource doesn't exist
     * @throws NotFoundException If the target resource is soft deleted
     * @return void
     */
    public function viewAcoPermissions($acoForeignKey = null)
    {
        // Check request sanity
        if (is_null($acoForeignKey)) {
            throw new BadRequestException(__('The id is missing for model Resource.'));
        }
        if (!Validation::uuid($acoForeignKey)) {
            throw new BadRequestException(__('The id is not valid for model Resource.'));
        }

        // Retrieve and sanity the query options.
        $whitelist = ['contain' => ['group', 'user', 'user.profile']];
        $options = $this->QueryString->get($whitelist);

        $this->loadModel('Permissions');
        $this->loadModel('Resources');

        // Check that the user has access to the resource.
        $resource = $this->Resources->findView($this->User->id(), $acoForeignKey)->first();
        if (empty($resource)) {
            throw new NotFoundException(__('The resource does not exist.'));
        }

        // Retrieve the permissions.
        $permissions = $this->Permissions->findViewAcoPermissions($acoForeignKey, $options);
        $this->success(__('The operation was successful.'), $permissions);
    }
}
