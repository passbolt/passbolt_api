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
 * @since         3.12.0
 */
namespace App\Utility\Migrations;

trait MigrationsAwareTrait
{
    /**
     * Check if a migration was already run.
     *
     * @param string $name Migration name
     * @return bool
     */
    public function isMigrationAlreadyRun(string $name): bool
    {
        /** @var \Migrations\CakeAdapter $adapter */
        $adapter = $this->getAdapter();

        return $adapter
            ->getCakeConnection()
            ->selectQuery()
            ->select('*')
            ->from('phinxlog')
            ->where(['migration_name' => $name])
            ->rowCountAndClose() > 0;
    }
}
