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
 * @since         4.2.0
 */

namespace Passbolt\PasswordPolicies\Service;

use App\Error\Exception\FormValidationException;
use Cake\Core\Configure;
use Passbolt\PasswordGenerator\PasswordGeneratorPlugin;
use Passbolt\PasswordPolicies\Form\PasswordPoliciesSettingsForm;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\PasswordPoliciesPlugin;

class PasswordPoliciesGetSettingsService implements PasswordPoliciesGetSettingsInterface
{
    /**
     * @inheritDoc
     */
    public function get(): PasswordPoliciesSettingsDto
    {
        $passwordPoliciesSettingsDto = $this->getSettingsFromFileOrEnv();

        $form = new PasswordPoliciesSettingsForm();
        if (!$form->execute($passwordPoliciesSettingsDto->toArray())) {
            throw new FormValidationException(__('Could not validate the password policies settings.'), $form);
        }

        return $passwordPoliciesSettingsDto;
    }

    /**
     * Get password policies from file or environment variables.
     *
     * @return \Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto
     */
    private function getSettingsFromFileOrEnv(): PasswordPoliciesSettingsDto
    {
        $settingsSource = $this->getSettingsSource();
        $defaultPasswordGenerator = $this->getPasswordGeneratorFromSource($settingsSource);
        $passwordPoliciesSettingsData = [
            'source' => $settingsSource,
            'default_generator' => $defaultPasswordGenerator,
        ];

        return PasswordPoliciesSettingsDto::createFromDefault($passwordPoliciesSettingsData);
    }

    /**
     * Get password generator type from the given settings source.
     *
     * @param string $settingsSource The target source
     * @return bool|string|null
     */
    private function getPasswordGeneratorFromSource(string $settingsSource)
    {
        switch ($settingsSource) {
            case PasswordPoliciesSettingsDto::SOURCE_FILE:
                /** @var string|null $defaultPasswordGenerator */
                $defaultPasswordGenerator = Configure::read(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY); // phpcs:ignore
                break;
            case PasswordPoliciesSettingsDto::SOURCE_ENV:
                $defaultPasswordGenerator = env(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY) ?? null;
                break;
            /**
             * @deprecated with v5.0, the legacy password generator setting has been replaced by a password policy setting.
             */
            case PasswordPoliciesSettingsDto::SOURCE_LEGACY_FILE:
                /** @var string|null $defaultPasswordGenerator */
                $defaultPasswordGenerator = Configure::read(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY); // phpcs:ignore
                break;
            case PasswordPoliciesSettingsDto::SOURCE_LEGACY_ENV:
                $defaultPasswordGenerator = env(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY) ?? null;
                break;
            default:
                $defaultPasswordGenerator = PasswordPoliciesSettingsDto::DEFAULT_PASSWORD_GENERATOR;
        }

        return $defaultPasswordGenerator;
    }

    /**
     * Get the settings source.
     *
     * The priority is as following:
     * - "file" if settings are defined using the password policy generator setting in the config file (passbolt.php)
     * - "env" if settings are defined using the password policy generator setting in environment variable
     * - "legacyFile" if settings are defined using the legacy password generator setting in the config file (passbolt.php)
     * - "legacyEnv" if settings are defined using the legacy password generator setting in environment variable
     * - "default" if nothing is defined
     *
     * @return string
     */
    private function getSettingsSource(): string
    {
        if (Configure::check(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY)) {
            return PasswordPoliciesSettingsDto::SOURCE_FILE;
        } elseif (!empty(getenv(PasswordPoliciesPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY))) {
            return PasswordPoliciesSettingsDto::SOURCE_ENV;
        } elseif (Configure::check(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_CONFIG_KEY)) {
            return PasswordPoliciesSettingsDto::SOURCE_LEGACY_FILE;
        } elseif (!empty(getenv(PasswordGeneratorPlugin::DEFAULT_PASSWORD_GENERATOR_ENV_KEY))) {
            return PasswordPoliciesSettingsDto::SOURCE_LEGACY_ENV;
        }

        return PasswordPoliciesSettingsDto::SOURCE_DEFAULT;
    }
}
