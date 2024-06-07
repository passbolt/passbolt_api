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
 * @since         4.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\ProviderError;

use Cake\Cache\Cache;
use Cake\Cache\Engine\NullEngine;
use Passbolt\Sso\Service\Cache\SsoProviderErrorCacheService;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @covers SsoProviderErrorCacheService
 */
class SsoProviderErrorCacheServiceTest extends SsoTestCase
{
    private SsoProviderErrorCacheService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoProviderErrorCacheService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        Cache::clear(SsoProviderErrorCacheService::getConfigKey()); // clear stale/old cache values
        SsoProviderErrorCacheService::reset();
        unset($this->service);

        parent::tearDown();
    }

    public function testSsoProviderErrorCacheService_Read(): void
    {
        SsoProviderErrorCacheService::configureEngine();
        Cache::write('test', '123', SsoProviderErrorCacheService::getConfigKey());

        $value = $this->service->read('test');

        $this->assertSame('123', $value);
    }

    public function testSsoProviderErrorCacheService_Read_DifferentKey(): void
    {
        SsoProviderErrorCacheService::configureEngine();
        Cache::write('foo', 'the value', SsoProviderErrorCacheService::getConfigKey());

        $value = $this->service->read('bar');

        $this->assertNull($value);
    }

    public function testSsoProviderErrorCacheService_Read_NullValueWhenCacheIsNotSet(): void
    {
        $defaultCacheConfig = Cache::getConfig('default');

        $value = $this->service->read('test');

        $this->assertNull($value);
        $cacheConfig = Cache::getConfig(SsoProviderErrorCacheService::getConfigKey());
        $this->assertNotNull($cacheConfig);
        $this->assertSame($defaultCacheConfig['className'], $cacheConfig['className']);
        $this->assertSame('+1 day', $cacheConfig['duration']);
        if (isset($defaultCacheConfig['prefix'])) {
            $this->assertSame($defaultCacheConfig['prefix'] . '_sso_', $cacheConfig['prefix']);
        } else {
            $this->assertSame('sso_', $cacheConfig['prefix']);
        }
    }

    public function testSsoProviderErrorCacheService_Read_DifferentCacheEngine(): void
    {
        // Set a different cache engine
        $originalDefaultConfig = Cache::getConfig('default');
        Cache::drop('default');
        $defaultCacheConfig = ['className' => NullEngine::class, 'prefix' => 'acme'];
        Cache::setConfig('default', $defaultCacheConfig);

        $this->service->read('test');

        $cacheConfig = Cache::getConfig(SsoProviderErrorCacheService::getConfigKey());
        $this->assertNotNull($cacheConfig);
        $this->assertSame($defaultCacheConfig['className'], $cacheConfig['className']);
        $this->assertSame('+1 day', $cacheConfig['duration']);
        $this->assertSame('acme_sso_', $cacheConfig['prefix']);

        // Clean up
        Cache::drop('default');
        Cache::setConfig('default', $originalDefaultConfig);
    }

    public function testSsoProviderErrorCacheService_Write(): void
    {
        $result = $this->service->write('test', 123);

        $this->assertTrue($result);
        $this->assertSame(123, Cache::read('test', SsoProviderErrorCacheService::getConfigKey()));
    }
}
