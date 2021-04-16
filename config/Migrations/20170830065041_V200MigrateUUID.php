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
        // uuid maps to char(36) on mysql and to uuid on postgres
        // in order to auto populates id fields
        // not doing: email queue, file_storage

        $this->table('authentication_tokens')
            ->changeColumn('id', 'uuid')
            ->changeColumn('token', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('comments')
            ->changeColumn('id', 'uuid')
            ->changeColumn('parent_id', 'uuid', ['null' => true, 'default' => null])
            ->changeColumn('foreign_id', 'uuid', ['default' => null])
            ->changeColumn('created_by', 'uuid', ['default' => null,  'null' => true])
            ->changeColumn('modified_by','uuid', ['default' => null, 'null' => true])
            ->save();

        $this->table('favorites')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid', ['null' => true, 'default' => null])
            ->changeColumn('foreign_id','uuid')
            ->save();

        $this->table('gpgkeys')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('groups')
            ->changeColumn('id', 'uuid')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->save();

        $this->table('groups_users')
            ->changeColumn('id', 'uuid')
            ->changeColumn('group_id', 'uuid',['null' => true, 'default' => null])
            ->changeColumn('user_id', 'uuid', ['null' => true, 'default' => null])
            ->save();

        $this->table('permissions')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('permissions_types')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('profiles')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->save();

        $this->table('resources')
            ->changeColumn('id', 'uuid')
            ->changeColumn('created_by', 'uuid')
            ->changeColumn('modified_by', 'uuid')
            ->save();

        $this->table('roles')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('secrets')
            ->changeColumn('id', 'uuid')
            ->changeColumn('user_id', 'uuid')
            ->changeColumn('resource_id', 'uuid')
            ->save();

        $this->table('user_agents')
            ->changeColumn('id', 'uuid')
            ->save();

        $this->table('users')
            ->changeColumn('id', 'uuid')
            ->changeColumn('role_id', 'uuid')
            ->save();

    }

}
