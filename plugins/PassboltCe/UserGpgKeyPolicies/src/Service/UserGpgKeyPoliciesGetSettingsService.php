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
 * @since         5.1.1
 */

namespace Passbolt\UserGpgKeyPolicies\Service;

use App\Error\Exception\FormValidationException;
use Cake\Core\Configure;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\UserGpgKeyPolicies\Form\UserGpgKeyPoliciesSettingsForm;
use Passbolt\UserGpgKeyPolicies\Model\Dto\UserGpgKeyPoliciesSettingsDto;
use Passbolt\UserGpgKeyPolicies\UserGpgKeyPoliciesPlugin;

class UserGpgKeyPoliciesGetSettingsService
{
    use LocatorAwareTrait;

    /**
     * Get the user gpg settings policies
     * @return \Passbolt\UserGpgKeyPolicies\Model\Dto\UserGpgKeyPoliciesSettingsDto
     * @throw {FormValidationException} If the gpg key policies settings do not validate
     */
    public function get(): UserGpgKeyPoliciesSettingsDto
    {
        $settingsSource = $this->getSettingsSource();
        $userGpgKeyPoliciesSettingsDto = $this->getUserGpgKeyPoliciesSettingsFromSource($settingsSource);

        $form = new UserGpgKeyPoliciesSettingsForm();
        if (!$form->execute($userGpgKeyPoliciesSettingsDto->toArray())) {
            throw new FormValidationException(__('Could not validate the user gpg key policies settings.'), $form);
        }

        return $userGpgKeyPoliciesSettingsDto;
    }

    /**
     * Get the user gpg key policies settings from the given source.
     *
     * @param string $settingsSource The target source
     * @return \Passbolt\UserGpgKeyPolicies\Model\Dto\UserGpgKeyPoliciesSettingsDto
     */
    private function getUserGpgKeyPoliciesSettingsFromSource(string $settingsSource): UserGpgKeyPoliciesSettingsDto
    {
        $settingsRawDto = [];

        switch ($settingsSource) {
            case UserGpgKeyPoliciesSettingsDto::SOURCE_FILE:
                $settingsRawDto = [
                    'preferred_key_type' => Configure::read(UserGpgKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY),
                    'source' => UserGpgKeyPoliciesSettingsDto::SOURCE_FILE
                ];
                break;
            case UserGpgKeyPoliciesSettingsDto::SOURCE_ENV:
                $settingsRawDto = [
                    'preferred_key_type' => env(UserGpgKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY) ?? null,
                    'source' => UserGpgKeyPoliciesSettingsDto::SOURCE_ENV
                ];
                break;
        }

        return UserGpgKeyPoliciesSettingsDto::createFromDefault($settingsRawDto);
    }

    /**
     * Get the settings source.
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
        if (Configure::check(UserGpgKeyPoliciesPlugin::PREFERRED_KEY_TYPE_CONFIG_KEY)) {
            return UserGpgKeyPoliciesSettingsDto::SOURCE_FILE;
        } elseif (!empty(getenv(UserGpgKeyPoliciesPlugin::PREFERRED_KEY_TYPE_ENV_KEY))) {
            return UserGpgKeyPoliciesSettingsDto::SOURCE_ENV;
        }

        return UserGpgKeyPoliciesSettingsDto::SOURCE_DEFAULT;
    }
}
