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
 * @since         2.7.0
 */

use Migrations\AbstractMigration;

class V270AddMissingIndexes extends AbstractMigration
{
    /**
     * Up
     *
     * @return void
     */
    public function up()
    {
        $this->table('favorites')
            ->addIndex(['foreign_key', 'user_id'])
            ->save();
        $this->table('secrets')
            ->addIndex(['resource_id'])
            ->addIndex(['user_id', 'resource_id'])
            ->save();
        $this->table('resources')
            ->addIndex(['deleted'])
            ->save();
        $this->table('users')
            ->addIndex(['deleted'])
            ->save();
        $this->table('groups')
            ->addIndex(['deleted'])
            ->save();
    }
}
