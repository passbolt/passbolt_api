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
 * @since         2.0.0
 */

use Migrations\AbstractMigration;

class V200MigrateUUID extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Cakephp requires CHAR(36) and not VARCHAR(36)
        // in order to auto populates id fields

        // not doing: email queue, file_storage

        $this->table('authentication_tokens')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('token', 'char', ['limit' => 36])
            ->changeColumn('user_id', 'char', ['limit' => 36])
            ->save();

        $this->table('comments')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('parent_id', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->changeColumn('foreign_id', 'char', ['limit' => 36, 'default' => null])
            ->changeColumn('created_by', 'char', ['default' => null, 'limit' => 36, 'null' => true])
            ->changeColumn('modified_by', 'char', ['default' => null, 'limit' => 36, 'null' => true])
            ->save();

        $this->table('favorites')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('user_id', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->changeColumn('foreign_id', 'char', ['limit' => 36])
            ->save();

        $this->table('gpgkeys')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('user_id', 'char', ['limit' => 36])
            ->save();

        $this->table('groups')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('created_by', 'char', ['limit' => 36])
            ->changeColumn('modified_by', 'char', ['limit' => 36])
            ->save();

        $this->table('groups_users')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('group_id', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->changeColumn('user_id', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->save();

        $this->table('permissions')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->save();

        $this->table('permissions_types')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->save();

        $this->table('profiles')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('user_id', 'char', ['limit' => 36])
            ->save();

        $this->table('resources')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('created_by', 'char', ['limit' => 36])
            ->changeColumn('modified_by', 'char', ['limit' => 36])
            ->save();

        $this->table('roles')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->save();

        $this->table('secrets')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('user_id', 'char', ['limit' => 36])
            ->changeColumn('resource_id', 'char', ['limit' => 36])
            ->save();

        $this->table('user_agents')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->save();

        $this->table('users')
            ->changeColumn('id', 'char', ['limit' => 36])
            ->changeColumn('role_id', 'char', ['limit' => 36])
            ->save();
    }
}
