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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller;

abstract class MfaSetupDeleteController extends MfaController
{
    /**
     * Delete a provider setting
     *
     * @param string $provider provider name
     * @throws \Cake\Http\Exception\ForbiddenException if mfa cookie is missing or invalid
     * @return void
     */
    protected function _handleDelete(string $provider)
    {
        if ($this->mfaSettings->getAccountSettings() === null) {
            $this->success('No configuration found for this provider. Nothing to delete.');

            return;
        }

        // Disable provider
        $this->mfaSettings->getAccountSettings()->disableProvider($provider);
        $this->success('The configuration was deleted.');
    }
}
