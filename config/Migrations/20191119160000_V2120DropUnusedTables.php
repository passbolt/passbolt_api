<?php
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
 * @since         2.12.0
 */

use Migrations\AbstractMigration;

class V2120DropUnusedTables extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        if ($this->hasTable('controller_logs')) {
            $this->table('controller_logs')
                ->drop()
                ->save();
        }

        if ($this->hasTable('schema_migrations')) {
            $this->table('schema_migrations')
                ->drop()
                ->save();
        }

        if ($this->hasTable('cake_sessions')) {
            $this->table('cake_sessions')
                ->drop()
                ->save();
        }

        if ($this->hasTable('permissions_types')) {
            $this->table('permissions_types')
                ->drop()
                ->save();
        }
    }
}
