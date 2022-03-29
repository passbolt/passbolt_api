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
 * @since         3.5.0
 */
// @codingStandardsIgnoreStart
use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V360AddAccountRecoveryTables extends AbstractMigration
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
            ->addColumn('public_key_id', 'uuid', [
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
            ->addIndex('public_key_id', ['unique' => false])
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
            ->addColumn('fingerprint', 'char', [
                'default' => null,
                'limit' => 40,
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

        $this->table('account_recovery_user_settings', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
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
            ->addColumn('status', 'string', [
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
            ->addIndex('user_id', ['unique' => false])
            ->create();

        $this->table('account_recovery_private_keys', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
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
                'limit' => null,
                'null' => false,
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
            ->addIndex('user_id', ['unique' => false])
            ->create();

        $this->table('account_recovery_private_key_passwords', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('recipient_fingerprint', 'char', [
                'null' => false,
                'limit' => 40,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('recipient_foreign_model', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('private_key_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
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
            ->addIndex('recipient_fingerprint', ['unique' => false])
            ->create();

        $this->table('account_recovery_requests', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('user_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('armored_key', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('fingerprint', 'string', [
                'default' => null,
                'limit' => 40,
                'null' => true,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('authentication_token_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('status', 'string', [
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
            ->create();

        $this->table('account_recovery_responses', [
                'id' => false, 'primary_key' => ['id'], 'collation' => 'utf8mb4_unicode_ci'
            ])
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('account_recovery_request_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('responder_foreign_key', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci'
            ])
            ->addColumn('responder_foreign_model', 'string', [
                'limit' => 128,
                'null' => false,
            ])
            ->addColumn('data', 'text', [
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('status', 'string', [
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
            ->create();
    }
}
// @codingStandardsIgnoreEnd
