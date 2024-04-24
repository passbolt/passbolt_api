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

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\AccountRecovery\AccountRecoveryPlugin;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryResponseFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;

/**
 * @covers  \Passbolt\AccountRecovery\Command\TruncateAccountRecoveryTablesCommand
 */
class TruncateAccountRecoveryTablesCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use FeaturePluginAwareTrait;
    use PassboltCommandTestTrait;
    use TruncateDirtyTables;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->enableFeaturePlugin(AccountRecoveryPlugin::class);
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testTruncateAccountRecoveryTablesCommand_Help()
    {
        $this->exec('passbolt truncate_account_recovery_tables -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Truncate all the account recovery tables.');
        $this->assertOutputContains('This will delete all account recovery requests and settings.');
    }

    public function testTruncateAccountRecoveryTablesCommand_Success_With_Parameters()
    {
        $factories = $this->getFactories();
        foreach ($factories as $factory) {
            $factory->persist();
        }

        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->persist();
        $orgPubKey = AccountRecoveryOrganizationPublicKeyFactory::firstOrFail();

        $this->exec(
            "passbolt truncate_account_recovery_tables -u {$admin->username} -f {$orgPubKey->fingerprint}",
            ['y',]
        );

        $this->assertExitSuccess();
        $this->assertOutputContains('The fingerprint was successfully found.');
        $this->assertOutputContains('The admin was successfully found.');
        $this->assertOutputContains('The following tables will be truncated:');
        $this->assertOutputContains('Continue anyway?');

        foreach ($factories as $factory) {
            $this->assertOutputContains("All entries in {$factory->getTable()->getTable()} table were deleted.");
            $this->assertSame(0, $factory::count());
        }
    }

    public function testTruncateAccountRecoveryTablesCommand_Success_Without_Parameters()
    {
        $factories = $this->getFactories();
        foreach ($factories as $factory) {
            $factory->persist();
        }

        $this->exec(
            'passbolt truncate_account_recovery_tables',
            ['y', 'y', 'y']
        );

        $this->assertExitSuccess();
        $this->assertOutputContains('No admin username was provided. Continue anyway?');
        $this->assertOutputContains('No fingerprint was provided. Continue anyway?');
        $this->assertOutputContains('The following tables will be truncated:');
        $this->assertOutputContains('Continue anyway?');

        foreach ($factories as $factory) {
            $this->assertOutputContains("All entries in {$factory->getTable()->getTable()} table were deleted.");
            $this->assertSame(0, $factory::count());
        }
    }

    public function testTruncateAccountRecoveryTablesCommand_Success_No_Interaction()
    {
        $factories = $this->getFactories();
        foreach ($factories as $factory) {
            $factory->persist();
        }

        $this->exec('passbolt truncate_account_recovery_tables -n');
        $this->assertExitSuccess();

        foreach ($factories as $factory) {
            $this->assertOutputContains("All entries in {$factory->getTable()->getTable()} table were deleted.");
            $this->assertSame(0, $factory::count());
        }
    }

    public function testTruncateAccountRecoveryTablesCommand_Success_With_Valid_Parameters_But_Not_In_DB()
    {
        $factories = $this->getFactories();
        foreach ($factories as $factory) {
            $factory->persist();
        }

        $this->exec(
            'passbolt truncate_account_recovery_tables -u foo@bar.com -f 8FF56AE5DFCEE142949B7826FD986838F4F9AB31',
            ['y', 'y', 'y']
        );

        $this->assertExitSuccess();
        $this->assertOutputContains('The admin could not be found. Continue anyway?');
        $this->assertOutputContains('The fingerprint could not be found in account_recovery_organization_public_keys table. Continue anyway?');
        $this->assertOutputContains('The following tables will be truncated:');
        $this->assertOutputContains('Continue anyway?');

        foreach ($factories as $factory) {
            $this->assertOutputContains("All entries in {$factory->getTable()->getTable()} table were deleted.");
            $this->assertSame(0, $factory::count());
        }
    }

    public function testTruncateAccountRecoveryTablesCommand_User_Validation_Error()
    {
        $this->exec('passbolt truncate_account_recovery_tables -u foo');
        $this->assertExitError('The username should be a valid email.');
    }

    public function testTruncateAccountRecoveryTablesCommand_Fingerprint_Validation_Error()
    {
        // Continue the dialog although the user is not in DB, and fail on fingerprint validation
        $this->exec('passbolt truncate_account_recovery_tables -u foo@test.com -f not-a-fingerprint', ['y']);
        $this->assertExitError('The fingerprint should be a string of 40 hexadecimal characters.');
    }

    /**
     * @return \CakephpFixtureFactories\Factory\BaseFactory[]
     */
    private function getFactories(): array
    {
        return [
            AccountRecoveryOrganizationPolicyFactory::make(),
            AccountRecoveryOrganizationPublicKeyFactory::make(),
            AccountRecoveryPrivateKeyPasswordFactory::make(),
            AccountRecoveryPrivateKeyFactory::make(),
            AccountRecoveryRequestFactory::make(),
            AccountRecoveryResponseFactory::make(),
            AccountRecoveryUserSettingFactory::make(),
        ];
    }
}
