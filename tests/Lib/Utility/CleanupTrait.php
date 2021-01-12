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
namespace App\Test\Lib\Utility;

use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

trait CleanupTrait
{
    /**
     * Perform all cleanup checks
     *
     * @param string $modelName model to call
     * @param string $checkName function to call to cleanup
     * @param int $expectedCount expected number of records after the cleanup
     * @param array|null $options
     * [
     *   bool $isDeleteCleanup Does the cleanup delete record. Default true. Some cleanup add records, false for them.
     *   int $cleanupCount The number of records the cleanup will treat. Default 1.
     * ]
     */
    protected function runCleanupChecks(string $modelName, string $checkName, int $expectedCount, ?array $options = [])
    {
        $isDeleteCleanup = Hash::get($options, 'isDeleteCleanup', true);
        $cleanupCount = Hash::get($options, 'cleanupCount', 1);
        $table = TableRegistry::getTableLocator()->get($modelName);

        // Check that the broken record was inserted
        $beforeCleanupCount = $table->find()->count();
        if ($isDeleteCleanup) {
            $expectedCountBeforeCleanup = $expectedCount + $cleanupCount;
        } else {
            $expectedCountBeforeCleanup = $expectedCount - $cleanupCount;
        }
        $this->assertEquals($expectedCountBeforeCleanup, $beforeCleanupCount, 'The number of records before cleanup is not the one expected');

        // Check that a dry run does not delete anything
        $deletedCount = $table->{$checkName}(true); // dry-run
        $this->assertEquals($cleanupCount, $deletedCount, 'Could not find anything to fix (Dry run)');

        // Check that running the cleanup delete the broken record
        $deletedCount = $table->{$checkName}();
        $this->assertEquals($cleanupCount, $deletedCount, 'Could not find anything to fix');

        // Check that subsequent cleanup do not delete anything
        $deletedCount = $table->{$checkName}();
        $this->assertEquals(0, $deletedCount, 'Running a second cleanup should not find more stuffs to fix');
        $afterCount = $table->find()->count();
        $this->assertEquals($expectedCount, $afterCount, 'Cleanup should not fix more than necessary');
    }
}
