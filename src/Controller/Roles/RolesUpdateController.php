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
 * @since         5.8.0
 */

namespace App\Controller\Roles;

use App\Controller\AppController;
use App\Service\Roles\RolesUpdateService;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;

class RolesUpdateController extends AppController
{
    /**
     * @param string $roleId Role identifier to update.
     * @return void
     */
    public function update(string $roleId): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        if (!Validation::uuid($roleId)) {
            throw new BadRequestException(__('The role identifier is not valid.'));
        }

        $result = (new RolesUpdateService())->update(
            $this->User->getAccessControl(),
            $roleId,
            $this->getRequest()->getData()
        );

        $this->success(__('The role was successfully updated.'), $result);
    }
}
