<?php
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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Model\Entity\User;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Throwable;

class IsMfaEnabledService
{
    /**
     * @var MfaOrgSettings
     */
    private $mfaOrgSettings;

    /**
     * @var GetMfaAccountSettingsService
     */
    private $mfaAccountSettingsService;

    /**
     * IsMfaEnabledService constructor.
     * @param MfaOrgSettings $mfaOrgSettings settings
     * @param GetMfaAccountSettingsService $mfaAccountSettingsService service
     */
    public function __construct(MfaOrgSettings $mfaOrgSettings, GetMfaAccountSettingsService $mfaAccountSettingsService)
    {
        $this->mfaOrgSettings = $mfaOrgSettings;
        $this->mfaAccountSettingsService = $mfaAccountSettingsService;
    }

    /**
     * @param User $user User to check if mfa is enabled
     * @return bool
     */
    public function isEnabledForUser(User $user)
    {
        if (!$this->mfaOrgSettings->isEnabled()) {
            return false;
        }

        try {
            $providersEnabledForOrgAndUser = array_intersect(
                $this->mfaOrgSettings->getEnabledProviders(),
                $this->mfaAccountSettingsService->getSettingsForUser($user)->getEnabledProviders()
            );
        } catch (Throwable $t) {
            $providersEnabledForOrgAndUser = [];
        }

        return count($providersEnabledForOrgAndUser) > 0;
    }
}
