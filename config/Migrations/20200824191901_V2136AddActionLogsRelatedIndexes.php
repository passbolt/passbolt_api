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
 * @since         2.13.0
 */

use Migrations\AbstractMigration;

class V2136AddActionLogsRelatedIndexes extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $this->table('entities_history')
            ->addIndex([
                'foreign_key',
            ])
            ->save();

        $this->table('secret_accesses')
            ->addIndex([
                'resource_id',
            ])
            ->addIndex([
                'user_id',
            ])
            ->save();

        $this->table('secrets_history')
            ->addIndex([
                'resource_id',
            ])
            ->addIndex([
                'user_id',
            ])
            ->save();

        $this->table('action_logs')
            ->addIndex([
                'action_id',
            ])
            ->addIndex([
                'status',
            ])
            ->addIndex([
                'action_id',
                'status'
            ])
            ->save();
    }
}
