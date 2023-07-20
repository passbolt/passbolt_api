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
namespace Passbolt\Ee\Test\TestCase;

use App\Test\Lib\SolutionBootstrapperTestCase;
use App\Test\TestCase\BaseSolutionBootstrapperTest;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Core\PluginCollection;
use Cake\TestSuite\IntegrationTestTrait;
use Passbolt\Ee\EeSolutionBootstrapper;

/**
 * EeFeaturePluginAdder class
 *
 * @covers \Passbolt\Ee\EeSolutionBootstrapper
 * @group SolutionBootstrapper
 */
class EeSolutionBootstrapperTest extends SolutionBootstrapperTestCase
{
    use FeaturePluginAwareTrait;
    use IntegrationTestTrait;

    public const EXPECTED_EE_PLUGINS = [
        'Passbolt/Ee',
        'Passbolt/JwtAuthentication',
        'Passbolt/Rbacs',
        'Passbolt/AccountSettings',
        'Passbolt/Import',
        'Passbolt/InFormIntegration',
        'Passbolt/Locale',
        'Passbolt/Export',
        'Passbolt/ResourceTypes',
        'Passbolt/TotpResourceTypes',
        'Passbolt/RememberMe',
        'Passbolt/EmailNotificationSettings',
        'Passbolt/EmailDigest',
        'Passbolt/Reports',
        'Passbolt/Mobile',
        'Passbolt/SelfRegistration',
        'Passbolt/PasswordGenerator',
        'Passbolt/SmtpSettings',
        'Passbolt/MultiFactorAuthentication',
        'Passbolt/Log',
        'Passbolt/AuditLog',
        'Passbolt/DirectorySync',
        'Passbolt/Tags',
        'Passbolt/Folders',
        'Passbolt/AccountRecovery',
        'Passbolt/Sso',
        'Passbolt/MfaPolicies',
        'Passbolt/SsoRecover',
    ];

    public function testEeSolutionBootstrapper_Application_Bootstrap(): void
    {
        Configure::delete('passbolt.webInstaller.configured');
        $plugins = $this->arrangeAndGetPlugins();
        $expectedPluginList = array_merge(
            [
                'Migrations',
                'Authentication',
                'EmailQueue',
                'BryanCrowe/ApiPagination',
                'PassboltSeleniumApi',
                'PassboltTestData',
            ],
            self::EXPECTED_EE_PLUGINS,
            [
                'Bake',
                'CakephpFixtureFactories',
                'Cake/TwigView',
            ]
        );
        $this->assertPluginList($plugins, $expectedPluginList);
        $this->assertPluginListContains($plugins, BaseSolutionBootstrapperTest::EXPECTED_CE_PLUGINS);
    }

    public function testEeSolutionBootstrapper_Application_Bootstrap_WebInstaller_Required(): void
    {
        Configure::write('passbolt.webInstaller.configured', false);
        $plugins = $this->arrangeAndGetPlugins();
        $expectedPluginList = [
            'Migrations',
            'Authentication',
            'EmailQueue',
            'BryanCrowe/ApiPagination',
            'PassboltSeleniumApi',
            'PassboltTestData',
            'Passbolt/Ee',
            'Passbolt/JwtAuthentication',
            'Passbolt/WebInstaller',
            'Bake',
            'CakephpFixtureFactories',
            'Cake/TwigView',
        ];
        $this->assertPluginList($plugins, $expectedPluginList);
    }

    protected function arrangeAndGetPlugins(): PluginCollection
    {
        $this->enableFeaturePlugin('Rbacs');
        $this->enableFeaturePlugin('Mobile');
        $this->enableFeaturePlugin('JwtAuthentication');
        $this->enableFeaturePlugin('SmtpSettings');
        $this->enableFeaturePlugin('SelfRegistration');
        $this->enableFeaturePlugin('Tags');
        $this->enableFeaturePlugin('AccountRecovery');
        $this->enableFeaturePlugin('Sso');
        $this->enableFeaturePlugin('MfaPolicies');
        $this->enableFeaturePlugin('SsoRecover');
        // These plugins are enabled by default if not defined
        Configure::delete('passbolt.plugins.ee.enabled');
        Configure::delete('passbolt.plugins.multiFactorAuthentication.enabled');
        Configure::delete('passbolt.plugins.log.enabled');
        Configure::delete('passbolt.plugins.directorySync.enabled');
        Configure::delete('passbolt.plugins.folders.enabled');

        $this->app->setSolutionBootstrapper(new EeSolutionBootstrapper());
        $this->app->bootstrap();
        $this->app->pluginBootstrap();

        return $this->app->getPlugins();
    }
}
