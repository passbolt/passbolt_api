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
 * @since         4.1.0
 */
namespace Passbolt\Scim\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\Scim\Service\ScimDeleteSettingsService;

class ScimDeleteSettingsController extends AppController
{
    /**
     * SCIM DELETE action
     *
     * @param string|null $id ID of the setting to delete
     * @return void
     */
    public function deleteSettings(?string $id): void
    {
        $this->User->assertIsAdmin();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The SCIM setting id should be a valid UUID.'));
        }

        $service = new ScimDeleteSettingsService();
        $service->deleteSettings($this->User->getAccessControl(), $id);
        $this->success(__('The operation was successful.'));
    }
}
