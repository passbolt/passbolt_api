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
 * @since         5.17.0
 */

use Migrations\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class V5170AddWebauthnCredentialsTable extends AbstractMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('webauthn_credentials', [
            'id' => false,
            'primary_key' => ['id'],
            'collation' => 'utf8mb4_unicode_ci',
        ]);

        $table
            ->addColumn('id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('user_id', 'uuid', [
                'null' => false,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('type', 'string', [
                'null' => false,
                'limit' => 16,
                'encoding' => 'ascii',
                'collation' => 'ascii_general_ci',
            ])
            ->addColumn('credential_id', 'string', [
                'null' => false,
                'limit' => 1364,
                'encoding' => 'ascii',
                'collation' => 'ascii_bin',
            ])
            ->addColumn('data', 'text', [
                'null' => false,
                'limit' => MysqlAdapter::TEXT_MEDIUM,
            ])
            ->addColumn('sign_count', 'biginteger', [
                'null' => false,
                'signed' => false,
                'default' => 0,
            ])
            ->addColumn('name', 'string', [
                'null' => true,
                'limit' => 255,
            ])
            ->addColumn('last_used', 'datetime', [
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'null' => false,
            ])
            ->addIndex(['user_id'])
            ->addIndex(['credential_id', 'type'], [
                'unique' => true,
            ])
            ->create();
    }
}
