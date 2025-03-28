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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Service\Migration;

class MigrateAllV4ToV5ServiceCollector
{
    private static array $services = [];

    /**
     * Add new migrator service to collector.
     *
     * @param array|string $services Service(s) to add.
     * @return void
     */
    public static function add(array|string $services): void
    {
        if (is_string($services)) {
            $services = [$services];
        }

        foreach ($services as $service) {
            self::$services[] = $service;
        }
    }

    /**
     * @return array<string>
     */
    public static function get(): array
    {
        return self::$services;
    }

    /**
     * @return void
     */
    public static function clear(): void
    {
        self::$services = [];
    }
}
