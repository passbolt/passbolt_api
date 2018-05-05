<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.1.0
 */

use Migrations\AbstractMigration;

class V210AddAccountSettingsTable extends AbstractMigration
{
    /**
     * Up
     *
     * @throws Exception if trying to migrate without all tables
     * @return void
     */
    public function up()
    {
        $this->table('account_settings', ['id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'])
            ->addColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('property_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('property', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
            ])
            ->addColumn('value', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
            ])
            ->addIndex(['user_id', 'property_id'])
            ->create();
    }
}
