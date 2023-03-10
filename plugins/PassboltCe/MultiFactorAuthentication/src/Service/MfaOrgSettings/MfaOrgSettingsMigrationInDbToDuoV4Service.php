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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings;

use App\Model\Entity\Role;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UserAccessControl;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettingsDuoBackwardCompatible;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * This service must extend TestCase so that we can mock the Duo healthcheck method.
 */
final class MfaOrgSettingsMigrationInDbToDuoV4Service
{
    use FeaturePluginAwareTrait;

    /**
     * Saves the MFA Organization settings in the DB
     *
     * @return void
     */
    public function migrate(): void
    {
        $uac = $this->getFirstAdminUac();
        // Skip if no admin exists in DB
        if (is_null($uac)) {
            return;
        }

        $duoSettings = $this->getDuoOrgSettingsInDb();
        // Skip if there are no Duo org settings in DB
        if (is_null($duoSettings)) {
            return;
        }

        $mfaOrgSettings = $this->getMfaOrgSettings($uac);
        (new MfaOrgSettingsSetService())->setOrgSettings($mfaOrgSettings, $uac, null, [
            'skipDuoHealtcheck' => true,
        ]);
    }

    /**
     * Reads the MFA settings in the configs
     *
     * @param \App\Utility\UserAccessControl $uac UAC
     * @return array
     */
    protected function getMfaOrgSettings(UserAccessControl $uac): array
    {
        return $this->getMfaOrgSettingsConfig($uac);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     * @return array
     */
    protected function getMfaOrgSettingsConfig(UserAccessControl $uac): array
    {
        $mfaOrgSettings = MfaSettings::get($uac)->getOrganizationSettings();
        // Because MfaOrgSettings check for Duo v4 configs, the settings must first be remaped
        // from v2 to v4 first (only if needed)
        $settings = $mfaOrgSettings->getSettings();
        $settings = MfaOrgSettingsDuoBackwardCompatible::remapSetDuoSettings($settings);
        // Then the MfaOrgSettings can be created which will validate the Duo v4 configs.
        $mfaOrgSettings = new MfaOrgSettings($settings);

        return $mfaOrgSettings->getConfig();
    }

    /**
     * @return array|null
     */
    protected function getDuoOrgSettingsInDb(): ?array
    {
        /** @var \App\Model\Table\OrganizationSettingsTable $OrganizationSettingsTable */
        $OrganizationSettingsTable = TableRegistry::getTableLocator()->get('OrganizationSettings');

        $mfaOrgSettings = $OrganizationSettingsTable->getByProperty(MfaSettings::MFA);
        if (is_null($mfaOrgSettings)) {
            return null;
        }
        $mfaSettings = json_decode($mfaOrgSettings->get('value'), true);

        return $mfaSettings[MfaSettings::PROVIDER_DUO] ?? null;
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
}
