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
 * @since         4.5.0
 */

namespace Passbolt\PasswordExpiry\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\BadRequestException;
use Cake\Validation\Validation;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryDeleteSettingsService;

class PasswordExpirySettingsDeleteController extends AppController
{
    /**
     * Delete password expiry settings
     *
     * @param string $id Setting ID
     * @return void
     */
    public function delete(string $id): void
    {
        $this->assertJson();
        $this->User->assertIsAdmin();

        // Check request sanity
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The identifier should be a valid UUID.'));
        }

        $service = new PasswordExpiryDeleteSettingsService();
        $service->delete($this->User->getExtendAccessControl(), $id);
        $this->success();
    }
}
