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

class V202ForceColumnsCharset extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('authentication_tokens')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('token', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('comments')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('parent_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('foreign_key', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('content', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('created_by', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('modified_by', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('favorites')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('foreign_key', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('foreign_model', 'string', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('file_storage')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('foreign_key', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('model', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('filename', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('mime_type', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('extension', 'string', [
                'default' => null,
                'limit' => 5,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('hash', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('path', 'string', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('adapter', 'string', [
                'comment' => 'Gaufrette Storage Adapter Class',
                'default' => null,
                'limit' => 32,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('gpgkeys')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('armored_key', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('uid', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('key_id', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('fingerprint', 'string', [
                'default' => null,
                'limit' => 51,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('type', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('groups')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('modified_by', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('groups_users')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('group_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('permissions')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('aco', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('aco_foreign_key', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('aro', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('aro_foreign_key', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('profiles')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('first_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('last_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('resources')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('username', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('uri', 'string', [
                'default' => null,
                'limit' => 1024,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('created_by', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('modified_by', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('roles')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('secrets')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('user_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('resource_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('user_agents')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 512,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();

        $this->table('users')
            ->changeColumn('id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('role_id', 'char', [
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->changeColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci'
            ])
            ->save();
    }
}
