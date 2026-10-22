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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Service\Cache;

use Cake\Cache\Cache;

abstract class AbstractScimCacheService
{
    /**
     * @var array<string, array>
     */
    private static array $cacheConfigurations = [];

    /**
     * @return string
     */
    abstract public static function getConfigKey(): string;

    /**
     * @return array
     */
    abstract public static function getConfigOptions(): array;

    /**
     * @return string
     */
    abstract public static function getPerformedKey(): string;

    /**
     * @return bool
     */
    public function isNotificationPerformed(): bool
    {
        return $this->read(static::getPerformedKey()) !== null;
    }

    /**
     * Write into the cache to mark the notification as sent.
     *
     * @return bool
     */
    public function markNotificationPerformed(): bool
    {
        if (!static::isEngineConfigured()) {
            static::configureEngine();
        }

        return Cache::write(static::getPerformedKey(), '1', static::getConfigKey());
    }

    /**
     * @param string $key Cache key.
     * @return string|null
     */
    protected function read(string $key): ?string
    {
        if (!static::isEngineConfigured()) {
            static::configureEngine();
        }

        $value = Cache::read($key, static::getConfigKey());

        return is_string($value) ? $value : null;
    }

    /**
     * @return bool
     */
    public static function isEngineConfigured(): bool
    {
        return isset(self::$cacheConfigurations[static::getConfigKey()]);
    }

    /**
     * Cache key prefix.
     *
     * @return string
     */
    public static function getPrefix(): string
    {
        return 'scim_';
    }

    /**
     * Configure the cache engine.
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

        self::$cacheConfigurations[static::getConfigKey()] = $config;
    }

    /**
     * Reset the class state and drop the engine.
     *
     * @return void
     */
    public static function reset(): void
    {
        if (!static::isEngineConfigured()) {
            static::configureEngine();
        }

        Cache::clear(static::getConfigKey());
        Cache::drop(static::getConfigKey());
        unset(self::$cacheConfigurations[static::getConfigKey()]);
    }
}
