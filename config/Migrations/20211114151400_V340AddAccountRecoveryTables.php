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

class V340AddAccountRecoveryTables extends AbstractMigration
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
            ])
            ->addColumn('policy', 'string', [
                'default' => null,
                'limit' => 36,
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
            ])
            ->addColumn('modified_by', 'char', [
                'null' => false,
            ])
            ->addIndex(
                'id',
                ['unique' => true]
            )
            ->create();
    }
}
// @codingStandardsIgnoreEnd
