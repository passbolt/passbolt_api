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
namespace App\Test\TestCase\Command;

use App\Test\Lib\AppTestCase;
use Cake\Chronos\Chronos;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

/**
 * @covers \App\Command\ShowQueuedEmailsCommand
 */
class ShowQueuedEmailsCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->useCommandRunner();
    }

    public function testShowQueuedEmailsCommand_Help()
    {
        $this->exec('passbolt show_queued_emails -h');

        $this->assertExitSuccess();
        $this->assertOutputContains('Shows records from email_queue table.');
        $this->assertOutputContains('cake passbolt show_queued_emails');
        // Assert options
        $this->assertOutputContains('--limit');
        $this->assertOutputContains('Number of records to show');
    }

    /**
     * Data provider for testShowQueuedEmailsCommand_ValidationLimitOption()
     *
     * @return array
     */
    public function invalidLimitOptionProvider(): array
    {
        return [
            [-100],
            [0],
            [101],
            [2000],
            ['abcd'],
        ];
    }

    /**
     * @dataProvider invalidLimitOptionProvider
     */
    public function testShowQueuedEmailsCommand_ValidationLimitOption($limit): void
    {
        $this->exec('passbolt show_queued_emails --limit=' . $limit);

        $this->assertExitError('Limit option value should be between 1 and 100');
    }

    public function testShowQueuedEmailsCommand_Success(): void
    {
        EmailQueueFactory::make(15)->persist();
        $oldEmail = EmailQueueFactory::make(['created' => Chronos::now()->subYears(30)])->persist();

        $this->exec('passbolt show_queued_emails');

        $this->assertSuccessOutput();
        // Assert old email is not in the output
        $this->assertOutputNotContains($oldEmail['email']);
    }

    public function testShowQueuedEmailsCommand_Success_LimitOption(): void
    {
        $emails = EmailQueueFactory::make(2)->persist();
        $oldEmails = EmailQueueFactory::make(['created' => Chronos::now()->subYears(27)], 2)->persist();

        $this->exec('passbolt show_queued_emails --limit=2');

        $this->assertSuccessOutput();
        foreach ($emails as $email) {
            $this->assertOutputContains($email['email']);
            $this->assertOutputContains($email['subject']);
        }
        // Assert old email is not in the output
        $this->assertOutputNotContains($oldEmails[1]['email']);
    }

    public function testShowQueuedEmailsCommand_Success_FailedOption(): void
    {
        $email = EmailQueueFactory::make()->persist();
        $errorEmails = EmailQueueFactory::make(['error' => 'something went wrong'], 2)->persist();

        $this->exec('passbolt show_queued_emails --failed');

        $this->assertSuccessOutput();
        $this->assertOutputContains($errorEmails[0]['email']);
        $this->assertOutputContains($errorEmails[0]['subject']);
        $this->assertOutputContains($errorEmails[1]['email']);
        $this->assertOutputContains($errorEmails[1]['subject']);
        // Assert success email is not in the output
        $this->assertOutputNotContains($email['email']);
        $this->assertOutputNotContains($email['subject']);
    }

    public function testShowQueuedEmailsCommand_Success_OldestOption(): void
    {
        $latestEmails = EmailQueueFactory::make(['created' => Chronos::now()], 2)->persist();
        $oldEmails = EmailQueueFactory::make(['created' => Chronos::now()->subMonths(5)], 2)->persist();

        $this->exec('passbolt show_queued_emails --limit=2 --oldest');

        $this->assertSuccessOutput();
        $this->assertOutputContains($oldEmails[0]['email']);
        $this->assertOutputContains($oldEmails[0]['subject']);
        $this->assertOutputContains($oldEmails[1]['email']);
        $this->assertOutputContains($oldEmails[1]['subject']);
        // Assert success email is not in the output
        $this->assertOutputNotContains($latestEmails[0]['email']);
        $this->assertOutputNotContains($latestEmails[0]['subject']);
    }

    // ---------------------------
    // Helper methods
    // ---------------------------

    public function assertSuccessOutput(): void
    {
        $this->assertExitSuccess();

        $this->assertOutputContains('List of queued emails');

        $fields = [
            __('Email'),
            __('Subject'),
            __('Error'),
            __('Created'),
            __('Sent'),
        ];
        foreach ($fields as $field) {
            $this->assertOutputContains($field);
        }
    }
}
