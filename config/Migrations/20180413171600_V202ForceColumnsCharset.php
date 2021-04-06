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
        $encoding= "utf8mb4";
        $collation = "utf8mb4_unicode_ci";
        switch($this->getAdapter()->getOptions()["adapter"]) {
            case "pgsql": {
                $encoding = "utf8";
                $collation = "utf8_unicode_ci";
                break;
                }
           default:
     	       $encoding= "utf8mb4";
               $collation = "utf8mb4_unicode_ci";
        }
   /*
        $this->table('authentication_tokens')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('token', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();
*/
        $this->table('comments')
           /* ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('parent_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('foreign_model', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('content', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])/*
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->save();

        $this->table('favorites')
         /*   ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('foreign_model', 'string', [ // not an uuid
                'default' => null,
                'limit' => 36,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('file_storage')
            /*->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('foreign_key', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('model', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('filename', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('mime_type', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('extension', 'string', [
                'default' => null,
                'limit' => 5,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('hash', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('path', 'string', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('adapter', 'string', [
                'comment' => 'Gaufrette Storage Adapter Class',
                'default' => null,
                'limit' => 32,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('gpgkeys')
           /* ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('armored_key', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('uid', 'string', [
                'default' => null,
                'limit' => 128,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('key_id', 'string', [
                'default' => null,
                'limit' => 8,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('fingerprint', 'string', [
                'default' => null,
                'limit' => 51,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('type', 'string', [
                'default' => null,
                'limit' => 16,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('groups')
           /* ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            /*->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->save();
/*
        $this->table('groups_users')
            ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('group_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();
*/
        $this->table('permissions')
  /*          ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('aco', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])/*
            ->changeColumn('aco_foreign_key', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('aro', 'string', [
                'default' => null,
                'limit' => 30,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])/*
            ->changeColumn('aro_foreign_key', 'uuid', [
                'default' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->save();

        $this->table('profiles')
            /*->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('first_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('last_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('resources')
            /*->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('username', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('uri', 'string', [
                'default' => null,
                'limit' => 1024,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])/*
            ->changeColumn('created_by', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('modified_by', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->save();

        $this->table('roles')
          /*  ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('description', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('secrets')
           /* ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('user_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('resource_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('data', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('user_agents')
          /*  ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('name', 'string', [
                'default' => null,
                'limit' => 512,
                'null' => true,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();

        $this->table('users')
           /* ->changeColumn('id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->changeColumn('role_id', 'uuid', [
                'default' => null,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])*/
            ->changeColumn('username', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
                'encoding' => $encoding,
                'collation' => $collation
            ])
            ->save();
    }
}
