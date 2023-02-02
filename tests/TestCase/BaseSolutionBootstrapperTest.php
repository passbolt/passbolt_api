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
namespace App\Test\TestCase;

use App\BaseSolutionBootstrapper;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\Core\PluginCollection;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

/**
 * BasePluginAdderTest class
 *
 * @covers \App\BaseSolutionBootstrapper
 * @group SolutionBootstrapper
 */
class BaseSolutionBootstrapperTest extends TestCase
{
    use FeaturePluginAwareTrait;
    use IntegrationTestTrait;

    public const EXPECTED_CE_PLUGINS = [
        'Passbolt/JwtAuthentication',
        'PassboltSeleniumApi',
        'PassboltTestData',
        'Passbolt/AccountSettings',
        'Passbolt/Import',
        'Passbolt/InFormIntegration',
        'Passbolt/Locale',
        'Passbolt/Export',
        'Passbolt/ResourceTypes',
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
    ];

    /**
     * @var \App\Application
     */
    public $app;

    public function setUp(): void
    {
        parent::setUp();
        $this->app = $this->createApp();
        $this->clearPlugins();
        DigestsPool::clearInstance();
        EmailNotificationSettings::flushCache();
    }

    public function tearDown(): void
    {
        $this->clearPlugins();
        unset($this->app);
        parent::tearDown();
    }

    public function testBaseSolutionBootstrapper_Application_Bootstrap(): void
    {
        $plugins = $this->arrangeAndGetPlugins();
        $expectedPluginList = array_merge(
            [
            'Migrations',
            'Authentication',
            'EmailQueue',
            'BryanCrowe/ApiPagination',
            ],
            self::EXPECTED_CE_PLUGINS,
            [
            'Bake',
            'CakephpFixtureFactories',
            ]
        );
        foreach ($expectedPluginList as $pluginName) {
            $this->assertSame($pluginName, $plugins->current()->getName());
            $plugins->next();
        }
    }

    public function testBaseSolutionBootstrapper_Application_Bootstrap_WebInstaller_Required(): void
    {
        Configure::write('passbolt.webInstaller.configured', false);
        $plugins = $this->arrangeAndGetPlugins();
        $expectedPluginList = [
            'Migrations',
            'Authentication',
            'EmailQueue',
            'BryanCrowe/ApiPagination',
            'Passbolt/WebInstaller',
            'Bake',
            'CakephpFixtureFactories',
        ];
        foreach ($expectedPluginList as $pluginName) {
            $this->assertSame($pluginName, $plugins->current()->getName());
            $plugins->next();
        }
    }

    protected function arrangeAndGetPlugins(): PluginCollection
    {
        $this->enableFeaturePlugin('Mobile');
        $this->enableFeaturePlugin('JwtAuthentication');
        $this->enableFeaturePlugin('SmtpSettings');
        $this->enableFeaturePlugin('SelfRegistration');
        // These two plugins are enabled by default if not defined
        Configure::delete('passbolt.plugins.multiFactorAuthentication.enabled');
        Configure::delete('passbolt.plugins.log.enabled');

        $this->app->setSolutionBootstrapper(new BaseSolutionBootstrapper());
        $this->app->bootstrap();
        $this->app->pluginBootstrap();
        $plugins = $this->app->getPlugins();
        $plugins->rewind();

        return $plugins;
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin()
    {
        $this->enableFeaturePlugin('mobile');

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Mobile');
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));

        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_With_Config()
    {
        $this->enableFeaturePlugin('mobile');

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Mobile', ['routes' => false, 'bootstrap' => false]);
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));

        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $this->assertFalse($plugin->isEnabled('bootstrap'));
        $this->assertFalse($plugin->isEnabled('routes'));
    }

    public function dataFortestBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin(): array
    {
        return [
            [],
            [false],
            [function () {

                return false;
            }],
        ];
    }

    /**
     * @dataProvider dataFortestBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin
     * @param callable|bool $isEnabledByDefault
     */
    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Disabled_Plugin($isEnabledByDefault = null)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('passbolt.plugins.mobile.enabled');
        $this->assertFalse($this->isFeaturePluginEnabled('mobile'));

        if ($isEnabledByDefault === null) {
            $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile');
        } else {
            $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        }
        $this->assertFalse($this->app->getPlugins()->has('Passbolt/Mobile'));
    }

    public function data_for_testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default()
    {
        return [
            [true],
            [function () {

                return true;
            }],
        ];
    }

    /**
     * @dataProvider data_for_testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default
     * @param callable|bool $isEnabledByDefault
     */
    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Enabled_Plugin_By_Default($isEnabledByDefault)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('passbolt.plugins.mobile.enabled');
        $this->assertFalse($this->isFeaturePluginEnabled('mobile'));

        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Non_Existing_Plugin()
    {
        $this->expectNotToPerformAssertions();
        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'foo');
    }

    public function testBaseSolutionBootstrapper_AddFeaturePlugin_On_Callable_Not_Returning_A_Boolean()
    {
        // Callable not returning a boolean makes the code crash.
        $callable = function () {
            return 'Foo';
        };

        $this->expectException(\TypeError::class);
        $this->app->getSolutionBootstrapper()->addFeaturePluginIfEnabled($this->app, 'Bar', [], $callable);
    }
}
