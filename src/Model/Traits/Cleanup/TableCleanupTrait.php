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

use Cake\ORM\Query;
use Cake\Utility\Hash;

trait TableCleanupTrait
{

    /**
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @param query $query custom query to replace the default find if any
     * @return number of affected records
     */
    public function cleanupSoftDeleted(string $modelName, $dryRun = false, Query $query = null)
    {
        if (!isset($query)) {
            $query = $this->query()
                ->select(['id'])
                ->leftJoinWith($modelName)
                ->where([$modelName . '.deleted' => true]);
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Delete all association records where associated model entities are deleted
     *
     * @param string $modelName model
     * @param bool $dryRun false
     * @param query $query custom query to replace the default find if any
     * @return number of affected records
     */
    public function cleanupHardDeleted(string $modelName, $dryRun = false, Query $query = null)
    {
        if (!isset($query)) {
            $query = $this->query()
                ->select(['id'])
                ->leftJoinWith($modelName)
                ->where(function ($exp, $q) use ($modelName) {
                    return $exp->isNull($modelName . '.id');
                });
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }
}
