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
 * @since         4.1.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V410AddRbacsTables extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('rbacs', [
                'id' => false,
                'primary_key' => ['id'],
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('role_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('control_function', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('foreign_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            // default rbacs values will not have a created_by or modified_by
            ->addColumn('created_by', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('modified_by', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addIndex([
                'role_id',
                'foreign_id',
            ],['unique' => true])
            ->create();

        $this->table('ui_actions', [
                'id' => false,
                'primary_key' => ['id'],
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('name', 'string', [
                'null' => false,
                'limit' => 255,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->create();
    }
}
// @codingStandardsIgnoreEnd
