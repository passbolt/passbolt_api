<?php
declare(strict_types=1);

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
 * @since         5.17.0
 */

use Cake\Log\Log;
use Migrations\AbstractMigration;

class V5170AddUsersUsernameIndex extends AbstractMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        // Handle case where users have already created the index on their own
        $table = $this->table('users');
        if ($table->hasIndex('username')) {
            Log::info(__('The index for `username` column in users table already exists.'));

            return;
        }

        $table
            ->addIndex('username')
            ->update();
    }
}
