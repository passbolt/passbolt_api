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
 * @since         5.12.0
 */

use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Migrations\AbstractMigration;

class V5120AddPartialUniqueIndexOnScimEntries extends AbstractMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        if (ConnectionManager::get('default')->getDriver() instanceof Postgres) {
            $this->execute(
                'CREATE UNIQUE INDEX scim_entries_scim_name_active_uniq ' .
                'ON scim_entries (scim_name, foreign_model) WHERE deleted IS NULL'
            );
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        if (ConnectionManager::get('default')->getDriver() instanceof Postgres) {
            $this->execute('DROP INDEX IF EXISTS scim_entries_scim_name_active_uniq');
        }
    }
}
