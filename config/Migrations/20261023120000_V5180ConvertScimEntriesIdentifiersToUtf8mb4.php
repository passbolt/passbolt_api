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
 * @since         5.18.0
 */

use Migrations\AbstractMigration;

class V5180ConvertScimEntriesIdentifiersToUtf8mb4 extends AbstractMigration
{
    /**
     * @return void
     */
    public function up(): void
    {
        $this->table('scim_entries')
            ->changeColumn('external_identifier', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_bin',
            ])
            ->changeColumn('scim_name', 'string', [
                'default' => null,
                'limit' => 256,
                'null' => true,
                'encoding' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
            ])
            ->update();
    }

    /**
     * Converting back to ascii would fail as soon as a non-ASCII value is stored.
     *
     * @return void
     */
    public function down(): void
    {
    }
}
