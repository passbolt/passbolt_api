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
namespace App;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Passbolt\WebInstaller\Middleware\WebInstallerMiddleware;

/**
 * A solution bootstrapper to:
 * - Load plugins required by a given solution
 * - Load/overwrite configs of a given solution
 */
class BaseSolutionBootstrapper
{
    use FeaturePluginAwareTrait;

    /**
     * Loads all the plugins relative to the solution
     *
     * @param \App\Application $app Application
     * @return void
     */
    public function addFeaturePlugins(Application $app): void
    {
        if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
            $app->addPlugin('PassboltSeleniumApi', ['bootstrap' => true, 'routes' => true]);
            $app->addPlugin('PassboltTestData', ['bootstrap' => true, 'routes' => false]);
        }

        $this->addFeaturePluginIfEnabled($app, 'JwtAuthentication');

        // Add webinstaller plugin if not configured.
        if (!WebInstallerMiddleware::isConfigured()) {
            $app->addPlugin('Passbolt/WebInstaller', ['bootstrap' => true, 'routes' => true]);

            return;
        }

        // Add Common plugins.
        $this->addFeaturePluginIfEnabled($app, 'Rbacs');
        $app->addPlugin('Passbolt/AccountSettings', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Passbolt/Import', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Passbolt/InFormIntegration', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Passbolt/Locale', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Passbolt/Export', ['bootstrap' => true, 'routes' => false]);
        $this->addFeaturePluginIfEnabled($app, 'PasswordExpiry');
        $this->addFeaturePluginIfEnabled($app, 'ResourceTypes');
        $this->addFeaturePluginIfEnabled($app, 'TotpResourceTypes', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Passbolt/RememberMe', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Passbolt/EmailNotificationSettings', ['bootstrap' => true, 'routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'EmailDigest');
        $app->addPlugin('Passbolt/Reports', ['bootstrap' => true, 'routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'Mobile');
        $this->addFeaturePluginIfEnabled($app, 'SelfRegistration');
        $app->addPlugin('Passbolt/PasswordGenerator', ['routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'SmtpSettings');

        $this->addFeaturePluginIfEnabled(
            $app,
            'MultiFactorAuthentication',
            ['bootstrap' => true, 'routes' => true],
            true
        );

        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        if (!isset($logEnabled) || $logEnabled) {
            $app->addPlugin('Passbolt/Log', ['bootstrap' => true, 'routes' => false]);
        }

        $folderEnabled = Configure::read('passbolt.plugins.folders.enabled');
        if (!isset($folderEnabled) || $folderEnabled) {
            $app->addPlugin('Passbolt/Folders', ['bootstrap' => true, 'routes' => true]);
        }

        $this->addFeaturePluginIfEnabled($app, 'PasswordPolicies');
    }

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
    final public function addFeaturePluginIfEnabled(
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
}
