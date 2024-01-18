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

namespace Passbolt\PasswordExpiryPolicies\Controller;

use App\Controller\AppController;
use Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesResourcesExpiryUpdateService;

class PasswordExpiryPoliciesResourcesExpiryUpdateController extends AppController
{
    /**
     * Create/update password expiry settings
     *
     * @param \Passbolt\PasswordExpiryPolicies\Service\Resources\PasswordExpiryPoliciesResourcesExpiryUpdateService $updateService Validation form
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the logged-in user does not have update permission on all the resources in the payload
     * @throws \Passbolt\PasswordExpiryPolicies\Controller\BadRequestException if the data passed in the payload is not valid
     */
    public function post(PasswordExpiryPoliciesResourcesExpiryUpdateService $updateService)
    {
        $this->assertJson();

        $resources = $updateService->updateMany(
            $this->User->getAccessControl(),
            $this->getRequest()->getData()
        );

        $this->success(__('The operation was successful.'), $resources);
    }
}
