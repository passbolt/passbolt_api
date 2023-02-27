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
 * @since         3.7.3
 */

namespace Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings;

use App\Model\Entity\Role;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

final class MfaOrgSettingsMigrationToDbService
{
    /**
     * Saves the MFA Organization settings in the DB, if not previously found in the DbB
     *
     * @return void
     */
    public function migrate(): void
    {
        // Skip if some settings are found in the DB
        if ($this->isMfaOrgSettingsInDb()) {
            return;
        }

        $uac = $this->getFirstAdminUac();
        if (is_null($uac)) {
            return;
        }

        $mfaOrgSettings = $this->getMfaOrgSettings($uac);
        $this->storeMfaSettingsInDb($uac, $mfaOrgSettings);
    }

    /**
     * Reads the MFA settings in the configs
     * If TOTP was previously intentionally deactivated with an env variable,
     * it should not be added to the settings.
     *
     * @param \App\Utility\UserAccessControl $uac UAC
     * @return array
     */
    protected function getMfaOrgSettings(UserAccessControl $uac): array
    {
        $mfaOrgSettings = MfaSettings::get($uac)->getOrganizationSettings()->getConfig();
        $providers = $mfaOrgSettings['providers'] ?? [];
        unset($mfaOrgSettings['providers']);

        // Add TOTP if it was not deactivated by env, and if not already in the list of providers
        if (!$this->isTotpDeactivatedWithEnvVariable() && !in_array(MfaSettings::PROVIDER_TOTP, $providers)) {
            $providers[] = MfaSettings::PROVIDER_TOTP;
        }

        // Map the provider in the proper format
        $providersInConfig = [];
        foreach ($providers as $provider) {
            $providersInConfig[$provider] = true;
        }

        $mfaOrgSettings['providers'] = $providersInConfig;

        return $mfaOrgSettings;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param array $mfaOrgSettings Settings to store
     * @return void
     */
    protected function storeMfaSettingsInDb(
        UserAccessControl $uac,
        array $mfaOrgSettings
    ): void {
        (new MfaOrgSettingsSetService())->setOrgSettings($mfaOrgSettings, $uac, null, [
            'skipDuoHealtcheck' => true,
        ]);
    }

    /**
     * @return bool
     */
    protected function isMfaOrgSettingsInDb(): bool
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettingsTable */
        $OrganizationSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');

        return $OrganizationSettingsTable->getByProperty(MfaSettings::MFA) !== null;
    }

    /**
     * @return \App\Utility\UserAccessControl|null
     */
    protected function getFirstAdminUac(): ?UserAccessControl
    {
        /** @var \App\Model\Table\UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        $admin = $Users->findFirstAdmin();
        if (is_null($admin)) {
            return null;
        } else {
            return new UserAccessControl(Role::ADMIN, $admin->get('id'));
        }
    }

    /**
     * Since the TOTP is now deactivated by default, we need to detect if it had previously
     * been deactivated on purpose.
     *
     * @return bool
     */
    protected function isTotpDeactivatedWithEnvVariable(): bool
    {
        $env = filter_var(env('PASSBOLT_PLUGINS_MFA_PROVIDERS_TOTP', true), FILTER_VALIDATE_BOOLEAN);

        return $env === false;
    }
}
