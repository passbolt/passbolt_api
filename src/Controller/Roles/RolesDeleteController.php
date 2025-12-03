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
use App\Service\Roles\RolesDeleteService;

class RolesDeleteController extends AppController
{
    /**
     * @param string $roleId Role identifier to delete.
     * @return void
     */
    public function delete(string $roleId): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();
        (new RolesDeleteService())->delete($this->User->getAccessControl(), $roleId);
        $this->success(__('The role was deleted.'));
    }
}
