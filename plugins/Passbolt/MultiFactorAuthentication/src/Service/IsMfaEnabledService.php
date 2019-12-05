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
use Exception;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Throwable;

class IsMfaEnabledService
{
    /**
     * @var GetMfaAccountSettingsService
     */
    private $getMfaAccountSettingsService;
    /**
     * @var GetMfaOrgSettingsService
     */
    private $getMfaOrgSettingsService;

    /**
     * @param GetMfaOrgSettingsService $getMfaOrgSettingsService Service to retrieve MfaOrgSettings
     * @param GetMfaAccountSettingsService $getMfaAccountSettingsService Service to retrieve MfaAccountSettings
     */
    public function __construct(GetMfaOrgSettingsService $getMfaOrgSettingsService, GetMfaAccountSettingsService $getMfaAccountSettingsService)
    {
        $this->getMfaAccountSettingsService = $getMfaAccountSettingsService;
        $this->getMfaOrgSettingsService = $getMfaOrgSettingsService;
    }

    /**
     * @param User $user User to check if mfa is enabled
     * @return bool
     * @throws Exception
     */
    public function isEnabledForUser(User $user)
    {
        $mfaOrgSettings = $this->getMfaOrgSettingsService->get();

        if (!$mfaOrgSettings->isEnabled()) {
            return false;
        }

        try {
            $providersEnabledForOrgAndUser = array_intersect(
                $mfaOrgSettings->getEnabledProviders(),
                $this->getMfaAccountSettings($user)->getEnabledProviders()
            );
        } catch (Throwable $t) {
            $providersEnabledForOrgAndUser = [];
        }

        return count($providersEnabledForOrgAndUser) > 0;
    }

    /**
     * @param User $user User to get settings for
     * @return MfaAccountSettings
     * @throws Exception
     */
    private function getMfaAccountSettings(User $user)
    {
        return $this->getMfaAccountSettingsService->getSettingsForUser($user);
    }
}
