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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPolicies\Controller;

use App\Controller\AppController;
use Cake\Http\Exception\InternalErrorException;
use Cake\Log\Log;
use Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsInterface;

class PasswordPoliciesSettingsGetController extends AppController
{
    /**
     * Returns passwords policies settings.
     *
     * @param \Passbolt\PasswordPolicies\Service\PasswordPoliciesGetSettingsInterface $passwordPoliciesGetSettingsService Service.
     * @return void
     */
    public function get(PasswordPoliciesGetSettingsInterface $passwordPoliciesGetSettingsService)
    {
        try {
            $passwordPoliciesSettingsDto = $passwordPoliciesGetSettingsService->get();
            $this->success(__('The operation was successful.'), $passwordPoliciesSettingsDto->toArray());
        } catch (\Throwable $error) {
            Log::error($error->getMessage());
            throw new InternalErrorException(__('Could not retrieve the password policies.'));
        }
    }
}
