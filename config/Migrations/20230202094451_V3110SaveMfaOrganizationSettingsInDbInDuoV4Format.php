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

use Migrations\AbstractMigration;
use Cake\Log\Log;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsMigrationInDbToDuoV4Service;

class V3110SaveMfaOrganizationSettingsInDbInDuoV4Format extends AbstractMigration
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
            (new MfaOrgSettingsMigrationInDbToDuoV4Service())->migrate();
        } catch (\Throwable $e) {
            Log::error('There was a migration error in V3110SaveMfaOrganizationSettingsInDbInDuoV4Format.');
            Log::error($e->getMessage());
        }
    }
}
