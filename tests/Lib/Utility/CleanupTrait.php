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
namespace App\Test\Lib\Utility;

trait CleanupTrait
{
    /**
     * Perform all cleanup checks
     *
     * @param string $modelName model to call
     * @param string $checkName function to call to cleanup
     * @param int $originalCount initial number of records before broken record insert
     */
    protected function runCleanupChecks($modelName, $checkName, $originalCount)
    {
        // Check that the broken record was inserted
        $afterCount = $this->{$modelName}->find()->count();
        $this->assertEquals($originalCount + 1, $afterCount, 'Broken record was not inserted');

        // Check that a dry run does not delete anything
        $deletedCount = $this->{$modelName}->{$checkName}(true); // dry-run
        $this->assertEquals(1, $deletedCount, 'Could not find anything to delete (Dry run)');

        // Check that running the cleanup delete the broken record
        $deletedCount = $this->{$modelName}->{$checkName}();
        $this->assertEquals(1, $deletedCount, 'Could not find anything to delete');

        // Check that subsequent cleanup do not delete anything
        $deletedCount = $this->{$modelName}->{$checkName}();
        $this->assertEquals(0, $deletedCount, 'Running a second cleanup should not find more stuffs to delete');
        $afterCount = $this->{$modelName}->find()->count();
        $this->assertEquals($originalCount, $afterCount, 'Cleanup should not delete more than necessary');
    }
}
