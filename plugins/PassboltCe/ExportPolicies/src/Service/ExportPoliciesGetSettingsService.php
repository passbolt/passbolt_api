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
 * @since         5.10.0
 */

namespace Passbolt\ExportPolicies\Service;

use Cake\Core\Configure;
use Passbolt\ExportPolicies\ExportPoliciesPlugin;
use Passbolt\ExportPolicies\Form\ExportPoliciesSettingsForm;
use Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto;

class ExportPoliciesGetSettingsService
{
    /**
     * Get the export policies settings.
     *
     * @return \Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto
     */
    public function get(): ExportPoliciesSettingsDto
    {
        $settingsSource = $this->getSettingsSource();
        $exportPoliciesSettingsDto = $this->getExportPoliciesSettingsFromSource($settingsSource);

        $form = new ExportPoliciesSettingsForm();
        if (!$form->execute($exportPoliciesSettingsDto->toArray())) {
            // Fallback to defaults in case of validation failure
            $exportPoliciesSettingsDto = ExportPoliciesSettingsDto::createFromDefault();
        }

        return $exportPoliciesSettingsDto;
    }

    /**
     * Returns the export policies settings from the given source.
     *
     * @param string $settingsSource The target source to get data from.
     * @return \Passbolt\ExportPolicies\Model\Dto\ExportPoliciesSettingsDto
     */
    private function getExportPoliciesSettingsFromSource(string $settingsSource): ExportPoliciesSettingsDto
    {
        $settings = [];

        switch ($settingsSource) {
            case ExportPoliciesSettingsDto::SOURCE_FILE:
                $settings = [
                    'allow_csv_format' => Configure::read(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY), // phpcs:ignore
                    'source' => ExportPoliciesSettingsDto::SOURCE_FILE,
                ];
                break;
            case ExportPoliciesSettingsDto::SOURCE_ENV:
                $settings = [
                    'allow_csv_format' => getenv(ExportPoliciesPlugin::ALLOW_CSV_FORMAT_ENV_KEY),
                    'source' => ExportPoliciesSettingsDto::SOURCE_ENV,
                ];
                break;
        }

        return ExportPoliciesSettingsDto::createFromDefault($settings);
    }

    /**
     * Get source of the settings.
     *
     * The priority is as following:
     * - "env" if settings are defined in environment variables
     * - "default" if nothing is defined
     *
     * @return string
     */
    private function getSettingsSource(): string
    {
        if ($this->isSourceFile()) {
            return ExportPoliciesSettingsDto::SOURCE_FILE;
        } elseif ($this->isSourceEnv()) {
            return ExportPoliciesSettingsDto::SOURCE_ENV;
        }

        return ExportPoliciesSettingsDto::SOURCE_DEFAULT;
    }

    /**
     * Checks for each env variable in the environments, and returns true if any of them are set.
     *
     * @return bool
     */
    private function isSourceEnv(): bool
    {
        return !empty(getenv(ExportPoliciesPlugin::ALLOW_CSV_FORMAT_ENV_KEY));
    }

    /**
     * Checks the key in the configuration, and returns true if found it set.
     *
     * @return bool
     */
    private function isSourceFile(): bool
    {
        return Configure::check(ExportPoliciesPlugin::EXPORT_POLICIES_ALLOW_CSV_FORMAT_CONFIG_KEY);
    }
}
