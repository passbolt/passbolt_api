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

use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Migrations\AbstractMigration;

class V5170AddScimEntriesScimNameIndex extends AbstractMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        // Postgres already has a unique index on the same two columns, added by V5120AddPartialUniqueIndexOnScimEntries
        if (ConnectionManager::get('default')->getDriver() instanceof Postgres) {
            return;
        }

        // if index already present, skip.
        $table = $this->table('scim_entries');
        if ($table->hasIndex(['scim_name', 'foreign_model'])) {
            Log::info(__('The index for `scim_name` column in scim_entries table already exists.'));

            return;
        }

        $table
            ->addIndex(['scim_name', 'foreign_model'])
            ->update();
    }
}
