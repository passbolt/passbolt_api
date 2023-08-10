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
 * @since         3.8.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V380AddSsoTables extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {

        $this->table('sso_settings', [
                'id' => false,
                'primary_key' => ['id'],
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('provider', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => MysqlAdapter::TEXT_MEDIUM,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('status', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
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
            ->addColumn('created_by', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('modified_by', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addIndex('id', ['unique' => true])
            ->create();

        $this->table('sso_keys', [
                'id' => false,
                'primary_key' => ['id'],
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('user_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => 512,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
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
            ->addColumn('created_by', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('modified_by', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addIndex('id', ['unique' => true])
            ->create();

    }
}
// @codingStandardsIgnoreEnd
