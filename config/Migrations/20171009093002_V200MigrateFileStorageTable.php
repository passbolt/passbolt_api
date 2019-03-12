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

class V200MigrateFileStorageTable extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // initial_migration & fixing_mime_type_field
        $this->table('file_storage')->drop()->save();
        $this->table('file_storage', ['id' => false, 'primary_key' => 'id'])
            ->addColumn('id', 'char', ['limit' => 36])
            ->addColumn('user_id', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->addColumn('foreign_key', 'char', ['limit' => 36, 'null' => true, 'default' => null])
            ->addColumn('model', 'string', ['limit' => 128, 'null' => true, 'default' => null])
            ->addColumn('filename', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('filesize', 'integer', ['limit' => 16, 'null' => true, 'default' => null])
            ->addColumn('mime_type', 'string', ['limit' => 128, 'null' => true, 'default' => null])
            ->addColumn('extension', 'string', ['limit' => 5, 'null' => true, 'default' => null])
            ->addColumn('hash', 'string', ['limit' => 64, 'null' => true, 'default' => null])
            ->addColumn('path', 'string', ['null' => true, 'default' => null])
            ->addColumn('adapter', 'string', ['limit' => 32, 'null' => true, 'default' => null])
            ->addColumn('created', 'datetime', ['null' => true, 'default' => null])
            ->addColumn('modified', 'datetime', ['null' => true, 'default' => null])
            ->create();
    }
}
