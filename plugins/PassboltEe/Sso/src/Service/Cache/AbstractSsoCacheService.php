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

namespace Passbolt\Sso\Service\Cache;

use Cake\Cache\Cache;

abstract class AbstractSsoCacheService
{
    private static ?array $cacheConfiguration = null;

    /**
     * Cache configuration key.
     *
     * @return string
     */
    abstract public static function getConfigKey(): string;

    /**
     * Return cache configuration to modify (i.e. duration) on top of default cache config.
     *
     * @return array
     */
    abstract public static function getConfigOptions(): array;

    /**
     * @param string $key Cache key.
     * @return mixed Returns `null` if cache is empty.
     */
    public function read(string $key)
    {
        if (!self::isEngineConfigured()) {
            self::configureEngine();
        }

        return Cache::read($key, static::getConfigKey());
    }

    /**
     * @param string $key Cache key.
     * @param mixed $value Value to write to the cache - anything except a resource.
     * @return bool True if the data was successfully cached, false on failure
     */
    public function write(string $key, $value): bool
    {
        if (!self::isEngineConfigured()) {
            self::configureEngine();
        }

        return Cache::write($key, $value, static::getConfigKey());
    }

    /**
     * Checks if cache engine is configured or not.
     *
     * @return bool
     */
    public static function isEngineConfigured(): bool
    {
        return self::$cacheConfiguration !== null;
    }

    /**
     * Prefix to set before actual cache key. It is recommended convention to end this with an underscore `_`.
     *
     * @return string
     */
    public static function getPrefix(): string
    {
        return 'sso_';
    }

    /**
     * Configures cache engine.
     *
     * @return void
     */
    public static function configureEngine(): void
    {
        $defaultCacheConfig = Cache::getConfig('default');
        $prefix = static::getPrefix();

        if (isset($defaultCacheConfig['prefix']) && is_string($defaultCacheConfig['prefix'])) {
            $prefix = $defaultCacheConfig['prefix'] . '_' . $prefix;
        }

        $config = array_merge($defaultCacheConfig, static::getConfigOptions(), ['prefix' => $prefix]);
        Cache::setConfig(static::getConfigKey(), $config);

        self::$cacheConfiguration = $config;
    }

    /**
     * Reset singleton state.
     * - Drops configured cache engine for this
     * - Sets configuration prop to `null`
     *
     * @return void
     */
    public static function reset(): void
    {
        Cache::drop(static::getConfigKey());
        self::$cacheConfiguration = null;
    }
}
