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
 * @since         2.0.0
 */
namespace App\Model\Traits\Cleanup;

trait GroupsCleanupTrait
{
    /**
     * Delete all records where associated groups are soft deleted
     *
     * @param bool|null $dryRun false
     * @return int number of affected records
     */
    public function cleanupSoftDeletedGroups(?bool $dryRun = false): int
    {
        return $this->cleanupSoftDeleted('Groups', $dryRun);
    }

    /**
     * Delete all records where associated groups are deleted
     *
     * @param bool|null $dryRun false
     * @return int number of affected records
     */
    public function cleanupHardDeletedGroups(?bool $dryRun = false): int
    {
        return $this->cleanupHardDeleted('Groups', $dryRun);
    }
}
