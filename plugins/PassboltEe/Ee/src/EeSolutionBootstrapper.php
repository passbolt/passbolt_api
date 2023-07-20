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
 * @since         3.10.0
 */
namespace Passbolt\Ee;

use App\Application;
use App\BaseSolutionBootstrapper;
use Cake\Core\Configure;
use Passbolt\WebInstaller\Middleware\WebInstallerMiddleware;

class EeSolutionBootstrapper extends BaseSolutionBootstrapper
{
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

        $app->addPlugin('Passbolt/Ee', ['bootstrap' => true, 'routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'JwtAuthentication');

        // Add tags plugin if not configured.
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
        $this->addFeaturePluginIfEnabled($app, 'ResourceTypes');
        $this->addFeaturePluginIfEnabled($app, 'TotpResourceTypes', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Passbolt/RememberMe', ['bootstrap' => true, 'routes' => false]);
        $app->addPlugin('Passbolt/EmailNotificationSettings', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Passbolt/EmailDigest', ['bootstrap' => true, 'routes' => true]);
        $app->addPlugin('Passbolt/Reports', ['bootstrap' => true, 'routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'Mobile');
        $this->addFeaturePluginIfEnabled($app, 'SelfRegistration');
        $app->addPlugin('Passbolt/PasswordGenerator', ['routes' => true]);
        $this->addFeaturePluginIfEnabled($app, 'SmtpSettings');

        $mfaEnabled = Configure::read('passbolt.plugins.multiFactorAuthentication.enabled');
        if (!isset($mfaEnabled) || $mfaEnabled) {
            $app->addPlugin('Passbolt/MultiFactorAuthentication', ['bootstrap' => true, 'routes' => true]);
        }

        $logEnabled = Configure::read('passbolt.plugins.log.enabled');
        if (!isset($logEnabled) || $logEnabled) {
            $app->addPlugin('Passbolt/Log', ['bootstrap' => true, 'routes' => false]);
            $app->addPlugin('Passbolt/AuditLog', ['bootstrap' => true, 'routes' => true]);
        }

        $ldapEnabled = Configure::read('passbolt.plugins.directorySync.enabled');
        if (!isset($ldapEnabled) || $ldapEnabled) {
            $app->addPlugin('Passbolt/DirectorySync', ['bootstrap' => true, 'routes' => true]);
        }

        $this->addFeaturePluginIfEnabled($app, 'Tags', [], true);

        $folderEnabled = Configure::read('passbolt.plugins.folders.enabled');
        if (!isset($folderEnabled) || $folderEnabled) {
            $app->addPlugin('Passbolt/Folders', ['bootstrap' => true, 'routes' => true]);
        }

        $this->addFeaturePluginIfEnabled($app, 'AccountRecovery', [], true);
        $this->addFeaturePluginIfEnabled($app, 'Sso');
        $this->addFeaturePluginIfEnabled($app, 'MfaPolicies');
        $this->addFeaturePluginIfEnabled($app, 'SsoRecover');
    }
}
