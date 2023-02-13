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
namespace App\Test\Lib;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\PluginCollection;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use Passbolt\EmailDigest\Utility\Digest\DigestsPool;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

abstract class SolutionBootstrapperTestCase extends TestCase
{
    use FeaturePluginAwareTrait;
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
        DigestsPool::clearInstance();
        EmailNotificationSettings::flushCache();
    }

    public function tearDown(): void
    {
        $this->clearPlugins();
        unset($this->app);
        parent::tearDown();
    }

    protected function assertPluginList(PluginCollection $plugins, array $expectedPlugins)
    {
        $plugins->rewind();
        foreach ($expectedPlugins as $pluginName) {
            $this->assertSame($pluginName, $plugins->current()->getName());
            $plugins->next();
        }
        $this->assertSame(count($expectedPlugins), $plugins->count());
    }

    protected function removePluginFromList(array $list, string $pluginName): array
    {
        return Hash::filter($list, function ($v) use ($pluginName) {
            if ($v == $pluginName) {
                return false;
            }

            return true;
        });
    }

}
