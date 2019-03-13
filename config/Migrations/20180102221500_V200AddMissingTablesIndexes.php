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

class V200AddMissingTablesIndexes extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('authentication_tokens')
            ->addIndex(['user_id']);

        $this->table('comments')
            ->addIndex([
                'parent_id',
                'foreign_id',
                'foreign_model',
                'created_by',
                'modified_by',
                'user_id'
            ]);

        $this->table('favorites')
            ->addIndex([
                'user_id',
                'foreign_id',
                'foreign_model'
            ]);

        $this->table('file_storage')
            ->addIndex([
                'user_id',
                'foreign_key',
                'model'
            ]);

        $this->table('gpgkeys')
            ->addIndex(['user_id']);

        $this->table('profiles')
            ->addIndex(['user_id']);

        $this->table('resources')
            ->addIndex([
                'created_by',
                'modified_by'
            ]);

        $this->table('secrets')
            ->addIndex([
                'user_id',
                'resource_id'
            ]);

        $this->table('users')
            ->addIndex([
                'role_id',
                'username'
            ]);
    }
}
