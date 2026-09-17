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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsDeleteService;

class OfflineSettingsDeleteController extends AppController
{
    /**
     * Disable Offline Mode by deleting its `organization_settings` row.
     *
     * @param string $id The `organization_settings.id` of the row to delete.
     * @return void
     */
    public function delete(string $id): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The identifier should be a valid UUID.'));
        }

        (new OfflineSettingsDeleteService())->delete($this->User->getExtendAccessControl(), $id);

        $this->success(__('The operation was successful.'));
    }
}
