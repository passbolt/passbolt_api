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
 * @since         3.3.0
 */
namespace App\Utility\Application;

use Cake\Core\Configure;

trait FeaturePluginAwareTrait
{
    /**
     * @param string $name Plugin name, either upper case or lower case first (without the "Passbolt/" prefix)
     * @param bool $isEnabledByDefault Should be loaded by default, if not priorly configured. False by default.
     * @return bool
     */
    public function isFeaturePluginEnabled(string $name, bool $isEnabledByDefault = false): bool
    {
        return Configure::read($this->getPluginEnabledConfigurationKey($name), $isEnabledByDefault);
    }

    /**
     * @param string $name Plugin name, either upper case or lower case first (without the "Passbolt/" prefix)
     * @return void
     */
    public function enableFeaturePlugin(string $name): void
    {
        Configure::write($this->getPluginEnabledConfigurationKey($name), true);
    }

    /**
     * @param string $name Plugin name, either upper case or lower case first (without the "Passbolt/" prefix)
     * @return void
     */
    public function disableFeaturePlugin(string $name): void
    {
        Configure::write($this->getPluginEnabledConfigurationKey($name), false);
    }

    /**
     * @param string $name Plugin name, either upper case or lower case first (without the "Passbolt/" prefix)
     * @return string
     */
    protected function getPluginEnabledConfigurationKey(string $name): string
    {
        $name = lcfirst($name);

        return "passbolt.plugins.{$name}.enabled";
    }
}
