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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Controller;

use App\Controller\AppController;
use Passbolt\SelfRegistration\Service\SelfRegistrationSetSettingsService;

class SelfRegistrationSetSettingsController extends AppController
{
    /**
     * Self Registration POST/PUT action
     *
     * @return void
     */
    public function setSettings(): void
    {
        $this->User->assertIsAdmin();

        $service = new SelfRegistrationSetSettingsService($this->User->getAccessControl());
        $settings = $service->saveSettings($this->getRequest()->getData());

        $this->success(__('The operation was successful.'), $settings);
    }
}
