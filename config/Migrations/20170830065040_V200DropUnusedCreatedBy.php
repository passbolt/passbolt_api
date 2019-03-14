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

class V200DropUnusedCreatedBy extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Keep: comments, groups, resources
        // Drop: auth_tokens, users, secret, group_users, gpgkeys, permissions

        $this->table('authentication_tokens')
            ->removeColumn('created_by')
            ->removeColumn('modified_by')
            ->update();

        $this->table('users')
            ->removeColumn('created_by')
            ->removeColumn('modified_by')
            ->update();

        $this->table('secrets')
            ->removeColumn('created_by')
            ->removeColumn('modified_by')
            ->update();

        $this->table('groups_users')
            ->removeColumn('created_by')
            ->update();

        $this->table('gpgkeys')
            ->removeColumn('created_by')
            ->removeColumn('modified_by')
            ->update();

        $this->table('permissions')
            ->removeColumn('created_by')
            ->removeColumn('modified_by')
            ->update();
    }
}
