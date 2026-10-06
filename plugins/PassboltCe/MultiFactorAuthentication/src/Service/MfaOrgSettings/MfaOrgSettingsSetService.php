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
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

class MfaOrgSettingsSetService
{
    /**
     * @param array $data data in the payload
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param \Duo\DuoUniversal\Client|null $duoClient Duo SDK Client
     * @param array $options Options used to save & validate organisation settings
     * @return array Organization Settings configs
     * @throws \Cake\Http\Exception\InternalErrorException if the UAC changed during the request (improbable)
     * @throws \App\Error\Exception\CustomValidationException in case of validation error
     */
    public function setOrgSettings(
        array $data,
        UserAccessControl $uac,
        ?Client $duoClient = null,
        array $options = [],
    ): array {
        $mfaSettings = MfaSettings::get($uac);
        // Allow some flexibility in inputs names
        $provider = MfaSettings::PROVIDER_DUO;
        $hostname = MfaOrgSettings::DUO_API_HOSTNAME;
        if (isset($data[$provider]['hostname'])) {
            $data[$provider][$hostname] = $data[$provider]['hostname'];
        }

        $orgSettings = $mfaSettings->getOrganizationSettings();

        // Get settings before updating them
        $currentProviders = $orgSettings->getSettings()['providers'];

        // Update settings
        $newProviders = $orgSettings->save($data, $uac, $duoClient, $options);

        [$isMFADisabled, $disabledProviders] = $this->getProvidersToCleanUp($currentProviders, $newProviders);

        if (!empty($disabledProviders)) {
            if ($isMFADisabled) {
                $this->cleanupAllProviders();
            } else {
                $this->cleanupMultipleProviders($disabledProviders);
            }
        }

        return $mfaSettings->getOrganizationSettings()->getConfig();
    }

    /**
     * Flush all related MFA user settings
     *
     * @return void
     */
    public function cleanupAllProviders(): void
    {
        // Drop all user MFA settings
        $AccountSettings = TableRegistry::getTableLocator()->get('AccountSettings');
        $AccountSettings->deleteAll(['property' => 'mfa']);

        // Disable all authentication tokens type MFA
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $AuthenticationTokens->updateAll(['active' => false], ['type' => 'mfa']);
    }

    /**
     * Cleanup multiple providers and related MFA user settings
     *
     * @param array $disabledProviders providers to be disabled
     * @return void
     */
    public function cleanupMultipleProviders(array $disabledProviders): void
    {
        // For each users inner joined with an MFA account settings
        $Users = TableRegistry::getTableLocator()->get('Users');
        $usersWithMfaSettings = $Users->find()
            ->select('id')
            ->innerJoinWith('AccountSettings', function (Query $q) {
                return $q->where(['property' => 'mfa']);
            });

        /** @var \App\Model\Entity\User $user */
        foreach ($usersWithMfaSettings as $user) {
            $uac = new UserAccessControl(Role::USER, $user->id); // The role here is never read
            $accountSetting = MfaAccountSettings::get($uac);
            foreach ($disabledProviders as $provider) {
                $accountSetting->disableProvider($provider);
            }
        }
    }

    /**
     * Get cleanup type and providers to be disabled
     *
     * @param array $currentProviders current enabled providers
     * @param array $newProviders user provided input
     * @return array cleanup type and array of disabled providers
     */
    private function getProvidersToCleanUp(array $currentProviders, array $newProviders): array
    {
        $isMFADisabled = false;
        $disabledProviders = array_diff($currentProviders, $newProviders);

        if (count($disabledProviders) === count($currentProviders)) {
            $isMFADisabled = true;
        }

        return [$isMFADisabled, $disabledProviders];
    }
}
