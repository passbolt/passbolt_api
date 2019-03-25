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

class V270AddEntitiesHistoryTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('entities_history', ['id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'])
             ->addColumn('id', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->addColumn('action_log_id', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->addColumn('foreign_model', 'string', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->addColumn('foreign_key', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->addColumn('crud', 'char', [
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
                 'action_log_id',
                 'foreign_model',
                 'foreign_key',
                 'crud'
             ])
             ->create();
    }
}
// @codingStandardsIgnoreEnd