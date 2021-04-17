<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;

class V270AddActionLogsTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('action_logs', ['id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'])
             ->addColumn('id', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
            ->addColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
            ])
            ->addColumn('action_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
            ])
            ->addColumn('context', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
             ->addColumn('status', 'integer', [
                 'default' => null,
                 'limit' => 1,
                 'null' => false,
             ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
             ->addIndex([
                 'id',
             ])
             ->addIndex([
                'user_id',
                'action_id',
                'status',
             ])
             ->create();
    }
}
// @codingStandardsIgnoreEnd