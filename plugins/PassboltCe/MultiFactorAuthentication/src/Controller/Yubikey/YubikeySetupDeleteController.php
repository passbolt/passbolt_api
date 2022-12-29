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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Controller\Yubikey;

use Passbolt\MultiFactorAuthentication\Controller\MfaSetupDeleteController;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class YubikeySetupDeleteController extends MfaSetupDeleteController
{
    /**
     * Delete Totp setup
     *
     * @return void
     */
    public function delete()
    {
        $this->_handleDelete(MfaSettings::PROVIDER_YUBIKEY);
    }
}
