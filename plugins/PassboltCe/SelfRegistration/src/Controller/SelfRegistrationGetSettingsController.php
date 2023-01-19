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
use Passbolt\SelfRegistration\Service\SelfRegistrationGetSettingsService;

class SelfRegistrationGetSettingsController extends AppController
{
    /**
     * Self Registration GET action
     *
     * @return void
     * @throws \Cake\Http\Exception\InternalErrorException if the settings in the DB are not valid
     */
    public function getSettings(): void
    {
        $this->User->assertIsAdmin();

        $settings = (new SelfRegistrationGetSettingsService())->getSettings();

        $this->success(__('The operation was successful.'), $settings);
    }
}
