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
 * @since         5.14.0
 */
namespace Passbolt\PasskeyAuthentication\Controller;

use Cake\Http\Exception\BadRequestException;
use Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService;

class PasskeyOrgSettingsPostController extends PasskeyController
{
    /**
     * Enable or disable passkey login for the organization (admin only).
     *
     * @param \Passbolt\PasskeyAuthentication\Service\PasskeyOrganizationSettingsService $settings Settings service
     * @return void
     */
    public function post(PasskeyOrganizationSettingsService $settings): void
    {
        $this->User->assertIsAdmin();
        $enabled = $this->getRequest()->getData('enabled');
        if (!is_bool($enabled)) {
            throw new BadRequestException(__('The "enabled" field must be a boolean.'));
        }
        $settings->setEnabled($this->User->getAccessControl(), $enabled);

        $this->success(__('The operation was successful.'), [
            'enabled' => $settings->isEnabled(),
        ]);
    }
}
