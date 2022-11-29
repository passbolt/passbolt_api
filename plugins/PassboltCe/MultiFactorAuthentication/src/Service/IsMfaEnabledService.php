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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Service;

use App\Model\Entity\User;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService;
use Throwable;

class IsMfaEnabledService
{
    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\GetMfaAccountSettingsService
     */
    private $getMfaAccountSettingsService;

    /**
     * @var \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService
     */
    private $getMfaOrgSettingsService;

    /**
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService|null $getMfaOrgSettingsService Service to retrieve MfaOrgSettings
     * @param \Passbolt\MultiFactorAuthentication\Service\GetMfaAccountSettingsService|null $getMfaAccountSettingsService Service to retrieve MfaAccountSettings
     */
    public function __construct(
        ?MfaOrgSettingsGetService $getMfaOrgSettingsService = null,
        ?GetMfaAccountSettingsService $getMfaAccountSettingsService = null
    ) {
        $this->getMfaAccountSettingsService = $getMfaAccountSettingsService ?? new GetMfaAccountSettingsService();
        $this->getMfaOrgSettingsService = $getMfaOrgSettingsService ?? new MfaOrgSettingsGetService();
    }

    /**
     * @param \App\Model\Entity\User $user User to check if mfa is enabled
     * @return bool
     * @throws \Exception
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
     * @param \App\Model\Entity\User $user User to get settings for
     * @return \Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings
     * @throws \Exception
     */
    private function getMfaAccountSettings(User $user)
    {
        return $this->getMfaAccountSettingsService->getSettingsForUser($user);
    }
}
