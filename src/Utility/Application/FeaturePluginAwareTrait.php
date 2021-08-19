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
use Cake\Core\PluginApplicationInterface;

trait FeaturePluginAwareTrait
{
    /**
     * Adds a plugin to the application according to the feature flag configuration.
     *
     * In order to enable a plugin, you may set is as enabled in the config/default.php file
     * under the passbolt.plugins config namespace key.
     *
     * The name of the plugin is without the "Passbolt/" prefix, either upper- or lower-case first.
     * By default, a feature (aka plugin) is disabled. You may force the enabling as parameter by passing a boolean
     * or a callable returning a boolean.
     *
     * @param \Cake\Core\PluginApplicationInterface $app Application
     * @param string $name Name of the plugin (without the "Passbolt/" prefix)
     * @param array $config Plugin loading config, will be merged with ['bootstrap' => true, 'routes' => true]
     * @param bool|callable $isEnabledByDefault Boolean or callback indicating if the plugin should be loaded by default, if not priorly enabled in configurations. False by default.
     * @return self
     * @throws \TypeError if the callable $isEnabledByDefault does not return a boolean.
     */
    public function addFeaturePluginIfEnabled(
        PluginApplicationInterface $app,
        string $name,
        array $config = [],
        $isEnabledByDefault = false
    ): self {
        $config = array_merge(['bootstrap' => true, 'routes' => true], $config);

        if (is_callable($isEnabledByDefault)) {
            $isEnabledByDefault = $isEnabledByDefault();
        }

        if ($this->isFeaturePluginEnabled($name, $isEnabledByDefault)) {
            $fullPluginName = 'Passbolt/' . ucfirst($name);
            $app->addPlugin($fullPluginName, $config);
        }

        return $this;
    }

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
