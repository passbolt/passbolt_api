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
 * @since         5.2.0
 */

namespace Passbolt\UserKeyPolicies\Service;

use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\UserKeyPolicies\Form\UserKeyPoliciesSettingsForm;
use Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto;
use Passbolt\UserKeyPolicies\UserKeyPoliciesPlugin;

class UserKeyPoliciesGetSettingsService
{
    use LocatorAwareTrait;

    /**
     * Get the user gpg settings policies
     *
     * @return \Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto
     */
    public function get(): UserKeyPoliciesSettingsDto
    {
        $settingsSource = $this->getSettingsSource();
        $userKeyPoliciesSettingsDto = $this->getUserKeyPoliciesSettingsFromSource($settingsSource);

        $form = new UserKeyPoliciesSettingsForm();
        if (!$form->execute($userKeyPoliciesSettingsDto->toArray())) {
            // Fallback to defaults in case of validation failure
            $userKeyPoliciesSettingsDto = UserKeyPoliciesSettingsDto::createFromDefault();
        }

        return $userKeyPoliciesSettingsDto;
    }

    /**
     * Returns the user key policies settings from the given source.
     *
     * @param string $settingsSource The target source to get data from.
     * @return \Passbolt\UserKeyPolicies\Model\Dto\UserKeyPoliciesSettingsDto
     */
    private function getUserKeyPoliciesSettingsFromSource(string $settingsSource): UserKeyPoliciesSettingsDto
    {
        $settings = [];

        switch ($settingsSource) {
            case UserKeyPoliciesSettingsDto::SOURCE_FILE:
                $rootConfigKey = UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY;
                $settings = [
                    'preferred_key_type' => Configure::read($rootConfigKey . '.' . 'preferred_key_type'),
                    'preferred_key_size' => Configure::read($rootConfigKey . '.' . 'preferred_key_size'),
                    'preferred_key_curve' => Configure::read($rootConfigKey . '.' . 'preferred_key_curve'),
                    'source' => UserKeyPoliciesSettingsDto::SOURCE_FILE,
                ];
                break;
            case UserKeyPoliciesSettingsDto::SOURCE_ENV:
                $settings = [
                    'preferred_key_type' => env(UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_TYPE),
                    'preferred_key_size' => env(UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_SIZE),
                    'preferred_key_curve' => env(UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_CURVE),
                    'source' => UserKeyPoliciesSettingsDto::SOURCE_ENV,
                ];
                break;
        }

        return UserKeyPoliciesSettingsDto::createFromDefault($settings);
    }

    /**
     * Get source of the settings.
     *
     * The priority is as following:
     * - "file" if settings are defined using the user gpg key policies setting in the config file (passbolt.php)
     * - "env" if settings are defined using the user gpg key policies setting in environment variable
     * - "default" if nothing is defined
     *
     * @return string
     */
    private function getSettingsSource(): string
    {
        if ($this->isSourceFile()) {
            return UserKeyPoliciesSettingsDto::SOURCE_FILE;
        } elseif ($this->isSourceEnv()) {
            return UserKeyPoliciesSettingsDto::SOURCE_ENV;
        }

        return UserKeyPoliciesSettingsDto::SOURCE_DEFAULT;
    }

    /**
     * Checks for each keys in the configuration, and returns true if found any of them set.
     *
     * @return bool
     */
    private function isSourceFile(): bool
    {
        $rootConfigKey = UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY;
        $allowedKeys = [
            'preferred_key_type',
            'preferred_key_size',
            'preferred_key_curve',
        ];

        foreach ($allowedKeys as $allowedKey) {
            if (Configure::check($rootConfigKey . '.' . $allowedKey)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks for each env variables in the environments, and returns true if any of them are set.
     *
     * @return bool
     */
    private function isSourceEnv(): bool
    {
        $allowedEnvKeys = [
            UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_TYPE,
            UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_SIZE,
            UserKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_CURVE,
        ];

        foreach ($allowedEnvKeys as $allowedEnvKey) {
            if (env($allowedEnvKey, false)) {
                return true;
            }
        }

        return false;
    }
}
