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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Model\Behavior;

use Cake\ORM\Behavior;

/**
 * Decorate the PermissionsTable class to add cleanup functions
 *
 * @method \App\Model\Table\PermissionsTable table()
 */
class PermissionsCleanupBehavior extends Behavior
{
    /**
     * Delete all records where associated folders are deleted
     *
     * @param bool $dryRun false
     * @return int number of affected records
     */
    public function cleanupHardDeletedFolders(?bool $dryRun = false): int
    {
        return $this->table()->cleanupHardDeletedAco('Folders', $dryRun);
    }
}
