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
 * @since         4.3.0
 */
namespace Passbolt\AccountRecovery\Test\TestCase\Command;

use App\Command\CleanupCommand;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;

/**
 * @covers  \Passbolt\AccountRecovery\Command\TruncateAccountRecoveryTablesCommand
 */
class CleanupCommandAccountRecoveryTablesCleanupTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        CleanupCommand::resetCleanups();
    }

    /**
     * Test that the cleanups for the AccountRecovery tables are well registered in the CleanupCommand
     */
    public function testCleanupCommand_AccountRecoveryTablesSelfRegistration()
    {
        $this->enableFeaturePlugin('AccountRecovery');

        $verboseMethodCallPrints = [
            'Passbolt/AccountRecovery.AccountRecoveryPrivateKeys:cleanupHardDeletedUsers',
            'Passbolt/AccountRecovery.AccountRecoveryPrivateKeys:cleanupSoftDeletedUsers',
            'Passbolt/AccountRecovery.AccountRecoveryRequests:cleanupHardDeletedUsers',
            'Passbolt/AccountRecovery.AccountRecoveryUserSettings:cleanupHardDeletedUsers',
            'Passbolt/AccountRecovery.AccountRecoveryUserSettings:cleanupSoftDeletedUsers',
            'Passbolt/AccountRecovery.AccountRecoveryResponses:cleanupHardDeletedAccountRecoveryRequests',
            'Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords:cleanupHardDeletedAccountRecoveryPrivateKeys',
            'Passbolt/Tags.ResourcesTags:cleanupDuplicatedResourcesTags',
        ];

        UserFactory::make()->admin()->persist();
        $this->exec('passbolt cleanup --dry-run --verbose');
        $this->assertExitSuccess();
        foreach ($verboseMethodCallPrints as $methodCallPrint) {
            $this->assertOutputContains($methodCallPrint);
        }
    }
}
