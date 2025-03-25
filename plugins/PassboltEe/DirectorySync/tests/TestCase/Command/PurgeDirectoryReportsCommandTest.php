<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.10.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Command;

use Cake\I18n\FrozenDate;
use Passbolt\DirectorySync\Test\Factory\DirectoryReportFactory;
use Passbolt\DirectorySync\Test\Factory\DirectoryReportsItemFactory;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncConsoleIntegrationTestCase;

/**
 * @uses \Passbolt\DirectorySync\Command\PurgeDirectoryReportsCommand
 */
class PurgeDirectoryReportsCommandTest extends DirectorySyncConsoleIntegrationTestCase
{
    public function testPurgeDirectoryReportsCommand_Help(): void
    {
        $this->exec('directory_sync purge_directory_reports -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Purge directory sync report entries');
    }

    public function testPurgeDirectoryReportsCommand_Success(): void
    {
        $oldDirectoryReport = DirectoryReportFactory::make(['created' => FrozenDate::now()->subYears(3)])->persist();
        DirectoryReportsItemFactory::make(3)->setReportId($oldDirectoryReport->get('id'))->persist();
        // Latest reports (After before date)
        $directoryReport = DirectoryReportFactory::make()->persist();
        DirectoryReportsItemFactory::make(2)->setReportId($directoryReport->get('id'))->persist();

        $date = FrozenDate::now()->subYears(1)->format('d-m-Y');
        $this->exec('directory_sync purge_directory_reports --before=' . $date);

        $this->assertExitSuccess();
        $this->assertSame(1, DirectoryReportFactory::find()->all()->count());
        $this->assertSame(2, DirectoryReportsItemFactory::find()->all()->count());
    }

    public function testPurgeDirectoryReportsCommand_Success_NoData(): void
    {
        $date = FrozenDate::now()->subYears(1)->format('d-m-Y');
        $this->exec('directory_sync purge_directory_reports --before=' . $date);

        $this->assertExitSuccess('No reports to purge');
    }

    public function testPurgeDirectoryReportsCommand_Error_BeforeDateOptionRequired(): void
    {
        $this->exec('directory_sync purge_directory_reports');
        $this->assertExitError('Missing required option');
    }

    public function testPurgeDirectoryReportsCommand_Error_InvalidBeforeDate(): void
    {
        $oldDirectoryReport = DirectoryReportFactory::make(['created' => FrozenDate::now()->subYears(3)])->persist();
        DirectoryReportsItemFactory::make(3)->setReportId($oldDirectoryReport->get('id'))->persist();

        $this->exec('directory_sync purge_directory_reports --before=foo-bar');

        $this->assertExitError('Invalid before date provided');
    }
}
