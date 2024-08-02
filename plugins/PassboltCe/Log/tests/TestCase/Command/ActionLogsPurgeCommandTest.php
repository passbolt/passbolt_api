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
 * @since         4.8.0
 */
namespace Passbolt\Log\Test\TestCase\Command;

use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\I18n\FrozenDate;
use Passbolt\Log\LogPlugin;
use Passbolt\Log\Test\Factory\ActionLogFactory;

class ActionLogsPurgeCommandTest extends AppIntegrationTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->mockProcessUserService('www-data');
        $this->enableFeaturePlugin(LogPlugin::class);
    }

    public function testActionLogsPurgeCommandHelp()
    {
        $this->exec('passbolt action_logs_purge -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Purge action logs.');
        $this->assertOutputContains('<warning>The performance of your instance might be degraded');
        $this->assertOutputContains('--dry-run, -d');
        $this->assertOutputContains('--retention-in-days');
    }

    public function testActionLogsPurgeCommand_Purge()
    {
        $action = 'AuthLogin.loginGet';
        $retentionPeriodInDays = 10;
        [$actionToRetain] = ActionLogFactory::make([
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays)],
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays + 1)],
        ])
        ->setActionId($action)
        ->persist();
        $this->exec('passbolt action_logs_purge -r ' . $retentionPeriodInDays);
        $this->assertExitSuccess();
        $this->assertOutputContains('<success>1 action logs entries were deleted.</success>');
        $this->assertSame(1, ActionLogFactory::count());
        $this->assertSame($actionToRetain->get('id'), ActionLogFactory::firstOrFail()->get('id'));
    }

    public function testActionLogsPurgeCommand_Dry_Run()
    {
        $action1 = 'AuthLogin.loginGet';
        $action2 = 'ResourceTypesIndex.index';
        $retentionPeriodInDays = 10;
        ActionLogFactory::make([
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays)],
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays + 1)],
        ])
        ->setActionId($action1)
        ->persist();

        ActionLogFactory::make([
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays)],
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays + 1)],
            ['created' => FrozenDate::now()->subDays($retentionPeriodInDays + 2)],
        ])
        ->setActionId($action2)
        ->persist();

        $this->exec('passbolt action_logs_purge -d -r ' . $retentionPeriodInDays);
        $this->assertOutputContains('Action');
        $this->assertOutputContains('Count');
        $this->assertOutputContains('Total');
        $this->assertOutputContains($action1);
        $this->assertOutputContains($action2);
        $this->assertOutputContains('1'); // count for action1
        $this->assertOutputContains('2'); // count for action2
        $this->assertOutputContains('3'); // count for total
        $this->assertExitSuccess();
    }

    public function testActionLogsPurgeCommand_NegativeValueInRetentionDaysOption(): void
    {
        $action = 'AuthLogin.loginGet';
        ActionLogFactory::make([
            ['created' => FrozenDate::now()->subDays(10)],
            ['created' => FrozenDate::now()],
        ])->setActionId($action)->persist();

        $this->exec('passbolt action_logs_purge -r -10');

        $this->assertExitError();
        $this->assertOutputContains('Retention in days option must be greater than zero');
    }

    public function testActionLogsPurgeCommand_LimitOption_Success(): void
    {
        $action = 'AuthLogin.loginGet';
        ActionLogFactory::make(['created' => FrozenDate::now()->subDays(4)], 5)->setActionId($action)->persist();
        ActionLogFactory::make(['created' => FrozenDate::now()], 2)->setActionId($action)->persist();

        $this->exec('passbolt action_logs_purge -r 2 -l 3');

        $this->assertExitSuccess();
        $this->assertOutputContains('<success>3 action logs entries were deleted.</success>');
        $this->assertSame(4, ActionLogFactory::count());
    }

    public function testActionLogsPurgeCommand_LimitOption_Error(): void
    {
        $action = 'AuthLogin.loginGet';
        ActionLogFactory::make(['created' => FrozenDate::now()->subDays(4)])->setActionId($action)->persist();
        ActionLogFactory::make(['created' => FrozenDate::now()])->setActionId($action)->persist();

        $this->exec('passbolt action_logs_purge -r 2 -l -5');

        $this->assertExitError();
        $this->assertOutputContains('<error>Limit option must be greater than zero.</error>');
    }
}
