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
 * @since         4.3.0
 */

namespace Passbolt\UserPassphrasePolicies\Controller;

use App\Controller\AppController;
use Passbolt\UserPassphrasePolicies\Service\UserPassphrasePoliciesSetSettingsService;

class UserPassphrasePoliciesSetSettingsController extends AppController
{
    /**
     * Set the user passphrase policies.
     *
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException If the user is not an administrator.
     */
    public function post(): void
    {
        $this->User->assertIsAdmin();

        $service = new UserPassphrasePoliciesSetSettingsService();
        $settings = $service->createOrUpdate(
            $this->User->getExtendAccessControl(),
            $this->getRequest()->getData()
        );

        $this->success(__('The operation was successful.'), $settings);
    }
}
