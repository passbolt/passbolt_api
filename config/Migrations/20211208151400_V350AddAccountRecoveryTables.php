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
 * @since         3.4.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V350AddAccountRecoveryTables extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('account_recovery_organization_policies', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('account_recovery_organization_public_key_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('policy', 'string', [
                'default' => null,
                'limit' => 36,
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
            ->addIndex('account_recovery_organization_public_key_id', ['unique' => false])
            ->create();

        $this->table('account_recovery_organization_public_keys', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('armored_key', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('fingerprint', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
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
            ->addColumn('deleted', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
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
            ->addIndex('fingerprint', ['unique' => true])
            ->create();
    }
}
// @codingStandardsIgnoreEnd
