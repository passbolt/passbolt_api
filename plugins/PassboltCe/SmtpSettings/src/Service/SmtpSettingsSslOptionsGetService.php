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
 * @since         4.7.0
 */
namespace Passbolt\SmtpSettings\Service;

use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Hash;
use Passbolt\SmtpSettings\Form\CustomSslOptionsForm;

class SmtpSettingsSslOptionsGetService
{
    /**
     * Is configuration options are default or not.
     *
     * @var bool|null
     */
    private ?bool $default = null;

    /**
     * Returns empty array if values set are defaults as settings those again won't have any effect.
     * Otherwise, returns set configurations mapped as PHP SSL context options format (https://www.php.net/manual/en/context.ssl.php#refsect1-context.ssl-options).
     *
     * @return array
     * @throws \Cake\Http\Exception\BadRequestException When configuration validation fails.
     */
    public function get(): array
    {
        $configSslOptions = $this->getConfigOptions();

        if ($this->checkDefaultOptions($configSslOptions)) {
            return [];
        }

        return $this->getMappedOptions($configSslOptions);
    }

    /**
     * Returns `true` if SSL options set in configuration are default, `false` otherwise.
     *
     * @return bool|null
     * @throws \Cake\Http\Exception\BadRequestException When configuration validation fails.
     */
    public function isDefault(): ?bool
    {
        if (is_null($this->default)) {
            $configSslOptions = $this->getConfigOptions();

            $this->checkDefaultOptions($configSslOptions);
        }

        return $this->default;
    }

    /**
     * @return array
     * @thows BadRequestException Any configuration value set is not of valid type.
     */
    private function getConfigOptions(): array
    {
        $values = Configure::read('passbolt.plugins.smtpSettings.security', []);

        $form = new CustomSslOptionsForm();
        $valid = $form->validate($values);
        if (!$valid) {
            $errors = Hash::flatten($form->getErrors());
            $errorMessage = __('Invalid `passbolt.plugins.smtpSettings.security` configuration values.');
            $errorMessage .= ' ' . __('Errors: ') . implode('; ', $errors);

            throw new BadRequestException($errorMessage);
        }

        return $values;
    }

    /**
     * Checks if SSL options set in configuration are defaults.
     *
     * @param array $configOptions SSL options set in configuration.
     * @return bool
     */
    private function checkDefaultOptions(array $configOptions): bool
    {
        if (count($configOptions) !== 4) {
            $this->default = false;

            return false;
        }

        $defaults = [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false,
            'cafile' => null,
        ];

        $result = $this->getMappedOptions($configOptions);

        $this->default = empty(array_diff_assoc($defaults, $result));

        return $this->default;
    }

    /**
     * @param array $configSslOptions Config SSL options.
     * @return array
     */
    private function getMappedOptions(array $configSslOptions): array
    {
        $result = [];
        $mapping = $this->getMapping();

        foreach ($configSslOptions as $key => $value) {
            if (!isset($mapping[$key])) {
                // skip if mapping not found
                continue;
            }

            $result[$mapping[$key]] = $value;
        }

        return $result;
    }

    /**
     * @return string[]
     */
    private function getMapping(): array
    {
        return [
            'sslVerifyPeer' => 'verify_peer',
            'sslVerifyPeerName' => 'verify_peer_name',
            'sslAllowSelfSigned' => 'allow_self_signed',
            'sslCafile' => 'cafile',
        ];
    }
}
