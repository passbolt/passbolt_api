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
namespace App\Model\Traits\Cleanup;

trait PermissionsCleanupTrait
{

    /**
     * Delete all records where associated permissions are soft deleted
     *
     * @param bool $dryRun false
     * @return number of affected records
     */
    public function cleanupHardDeletedPermissions($dryRun = false)
    {
        $secretsToDelete = [];
        $secrets = $this->find('all');

        foreach ($secrets as $secret) {
            if (!$this->Resources->hasAccess($secret->user_id, $secret->resource_id)) {
                $secretsToDelete[] = $secret->id;
            }
        }

        if (!$dryRun && !empty($secretsToDelete)) {
            $this->deleteAll(['id IN' => $secretsToDelete]);
        }

        return count($secretsToDelete);
    }
}
