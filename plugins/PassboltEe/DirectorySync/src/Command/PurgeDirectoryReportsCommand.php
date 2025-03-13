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
namespace Passbolt\DirectorySync\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Database\Expression\QueryExpression;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\DirectorySync\Model\Entity\DirectoryReport;
use Passbolt\DirectorySync\Model\Table\DirectoryReportsItemsTable;
use Passbolt\DirectorySync\Model\Table\DirectoryReportsTable;

class PurgeDirectoryReportsCommand extends DirectorySyncCommand
{
    private ?DirectoryReportsTable $DirectoryReports = null;
    private ?DirectoryReportsItemsTable $DirectoryReportsItems = null;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->DirectoryReports = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryReports');
        $this->DirectoryReportsItems = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryReportsItems'); // phpcs:ignore
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Purge directory sync report entries.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Purge directory sync report entries'));

        $parser
            ->addOption('before', [
                'short' => 'b',
                'required' => true,
                'help' => __('The date (format: dd-mm-yyyy) before the data will be purged.'),
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        try {
            $beforeDate = \Cake\I18n\Date::createFromFormat('d-m-Y', $args->getOption('before'));
        } catch (\InvalidArgumentException $e) {
            $msg = __('Invalid before date provided.');
            $msg .= ' ' . $e->getMessage();
            $this->error($msg, $io);

            return $this->errorCode();
        }

        $reports = $this->getReportsToPurge($beforeDate);
        if (count($reports) === 0) {
            $this->success(__('No reports to purge.'), $io);

            return $this->successCode();
        }

        [$directoryReportPurgeCount, $directoryReportItemsPurgeCount] = $this->purgeDirectoryReports($reports);

        $this->success(__('{0} directory report entries purged.', $directoryReportPurgeCount), $io);
        $this->success(__('{0} directory report item entries purged.', $directoryReportItemsPurgeCount), $io);

        return $this->successCode();
    }

    /**
     * @param \Cake\I18n\Date $date The before date.
     * @return array
     */
    private function getReportsToPurge(\Cake\I18n\Date $date): array
    {
        return $this->DirectoryReports
            ->find()
            ->select(['id'])
            ->where(function (QueryExpression $queryExpr) use ($date) {
                return $queryExpr->and([
                    'status' => DirectoryReport::STATUS_DONE, // To make sure to not delete in process reports accidentally
                    $queryExpr->lt('created', $date),
                ]);
            })
            ->toArray();
    }

    /**
     * @param array $directoryReports Directory reports.
     * @return array
     */
    private function purgeDirectoryReports(array $directoryReports): array
    {
        $reportIds = Hash::extract($directoryReports, '{n}.id');

        $directoryReportPurgeCount = $this->DirectoryReports->deleteAll(['id IN' => $reportIds]);
        $directoryReportItemsPurgeCount = $this->DirectoryReportsItems->deleteAll(['report_id IN' => $reportIds]);

        return [$directoryReportPurgeCount, $directoryReportItemsPurgeCount];
    }
}
