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

class V200AddCommentsUserIdField extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        // Add column user_id.
        $this->table('comments')
             ->addColumn('user_id', 'char', [
                 'default' => null,
                 'limit' => 36,
                 'null' => false,
             ])
             ->save();

        // Populate user_id with the content of created_by.
        $this->query('UPDATE comments SET user_id=created_by');
    }
}
