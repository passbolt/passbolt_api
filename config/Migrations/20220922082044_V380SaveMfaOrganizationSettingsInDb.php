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
 * @since         3.11.0
 */

use Cake\Log\Log;
use Migrations\AbstractMigration;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationToDbService;

class V380SaveMfaOrganizationSettingsInDb extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        try {
            (new MfaOrgSettingsMigrationToDbService())->migrate();
        } catch (\Throwable $e) {
            Log::error('There was a migration error in V380SaveMfaOrganizationSettingsInDb.');
            Log::error($e->getMessage());
        }
    }
}
