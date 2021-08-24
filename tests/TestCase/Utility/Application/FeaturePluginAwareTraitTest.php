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
namespace App\Test\TestCase\Utility\Application;

use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class FeaturePluginAwareTraitTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var \App\Application
     */
    public $app;

    public function setUp(): void
    {
        parent::setUp();
        $this->app = $this->createApp();
        $this->clearPlugins();
    }

    public function tearDown(): void
    {
        $this->app->disableFeaturePlugin('mobile');
        unset($this->app);
        parent::tearDown();
    }

    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Enabled_Plugin()
    {
        $this->app->enableFeaturePlugin('mobile');

        $this->app->addFeaturePluginIfEnabled($this->app, 'Mobile');
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));

        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Enabled_Plugin_With_Config()
    {
        $this->app->enableFeaturePlugin('mobile');

        $this->app->addFeaturePluginIfEnabled($this->app, 'Mobile', ['routes' => false, 'bootstrap' => false]);
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));

        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $this->assertFalse($plugin->isEnabled('bootstrap'));
        $this->assertFalse($plugin->isEnabled('routes'));
    }

    public function dataFortestFeaturePluginAwareTraitAddFeaturePlugin_On_Disabled_Plugin()
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
     * @dataProvider dataFortestFeaturePluginAwareTraitAddFeaturePlugin_On_Disabled_Plugin
     * @param callable|bool $isEnabledByDefault
     */
    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Disabled_Plugin($isEnabledByDefault = null)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('passbolt.plugins.mobile.enabled');
        $this->assertFalse($this->app->isFeaturePluginEnabled('mobile'));

        if ($isEnabledByDefault === null) {
            $this->app->addFeaturePluginIfEnabled($this->app, 'mobile');
        } else {
            $this->app->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        }
        $this->assertFalse($this->app->getPlugins()->has('Passbolt/Mobile'));
    }

    public function dataFortestFeaturePluginAwareTraitAddFeaturePlugin_On_Enabled_Plugin_By_Default()
    {
        return [
            [true],
            [function () {

                return true;
            }],
        ];
    }

    /**
     * @dataProvider dataFortestFeaturePluginAwareTraitAddFeaturePlugin_On_Enabled_Plugin_By_Default
     * @param callable|bool $isEnabledByDefault
     */
    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Enabled_Plugin_By_Default($isEnabledByDefault)
    {
        // If not defined, the feature flag is considered as false by default.
        Configure::delete('passbolt.plugins.mobile.enabled');
        $this->assertFalse($this->app->isFeaturePluginEnabled('mobile'));

        $this->app->addFeaturePluginIfEnabled($this->app, 'mobile', [], $isEnabledByDefault);
        $this->assertTrue($this->app->getPlugins()->has('Passbolt/Mobile'));
        $this->assertSame('Passbolt/Mobile', $this->app->getPlugins()->get('Passbolt/Mobile')->getName());
        $plugin = $this->app->getPlugins()->get('Passbolt/Mobile');
        $this->assertTrue($plugin->isEnabled('bootstrap'));
        $this->assertTrue($plugin->isEnabled('routes'));
    }

    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Non_Existing_Plugin()
    {
        $this->expectNotToPerformAssertions();
        $this->app->addFeaturePluginIfEnabled($this->app, 'foo');
    }

    public function testFeaturePluginAwareTraitAddFeaturePlugin_On_Callable_Not_Returning_A_Boolean()
    {
        // Callable not returning a boolean makes the code crash.
        $callable = function () {
            return 'Foo';
        };

        $this->expectException(\TypeError::class);
        $this->app->addFeaturePluginIfEnabled($this->app, 'Bar', [], $callable);
    }
}
