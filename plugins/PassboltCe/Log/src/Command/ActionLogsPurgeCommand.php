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
namespace Passbolt\Log\Command;

use App\Command\PassboltCommand;
use App\Service\Command\ProcessUserService;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\Log\Service\ActionLogs\ActionLogsPurgeService;

class ActionLogsPurgeCommand extends PassboltCommand
{
    /**
     * @var \App\Service\Command\ProcessUserService
     */
    protected ProcessUserService $processUserService;
    protected ActionLogsPurgeService $purgeService;

    public const DEFAULT_LIMIT = 100_000; // 100k

    /**
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service.
     */
    public function __construct(ProcessUserService $processUserService)
    {
        parent::__construct();
        $this->processUserService = $processUserService;
        $this->purgeService = new ActionLogsPurgeService();
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription([
                __('Purge action logs.'),
                '<warning>' .
                __('The performance of your instance might be degraded while the command is running.')
                . '</warning>',
            ])
            ->addOption('retention-in-days', [
                'short' => 'r',
                'required' => true,
                'help' => __('Retention period in days.'),
            ])
            ->addOption('dry-run', [
                'short' => 'd',
                'boolean' => true,
                'default' => false,
                'help' => __('Dry run mode.'),
            ])
            ->addOption('limit', [
                'short' => 'l',
                'required' => true,
                'default' => self::DEFAULT_LIMIT,
                'help' => __('Maximum number of rows to purge.'),
            ])
            ->addOption('verbose', [
                'short' => 'v',
                'boolean' => true,
                'default' => false,
                'help' => __('Display the count of logs grouped by actions before and after the purge.'),
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Root user is not allowed to execute this command.
        $this->assertCurrentProcessUser($io, $this->processUserService);

        $this->validateOptions($args, $io);

        $retentionInDays = (int)$args->getOption('retention-in-days');
        $limit = (int)$args->getOption('limit');
        $dryRun = $args->getOption('dry-run');
        if ($dryRun) {
            $this->getDryRun($retentionInDays, $io);
        } else {
            $this->purge($retentionInDays, $limit, $io);
        }

        return $this->successCode();
    }

    /**
     * Validates user provided arguments/options data.
     *
     * @param \Cake\Console\Arguments $args Argument object.
     * @param \Cake\Console\ConsoleIo $io I/O object.
     * @return void
     */
    private function validateOptions(Arguments $args, ConsoleIo $io): void
    {
        $errors = [];

        $retentionInDays = (int)$args->getOption('retention-in-days');
        if ($retentionInDays < 1) {
            $errors[] = __('Retention in days option must be greater than zero.');
        }

        $limit = (int)$args->getOption('limit');
        if ($limit < 1) {
            $errors[] = __('Limit option must be greater than zero.');
        }

        if (empty($errors)) {
            // No errors, data is good to use
            return;
        }

        foreach ($errors as $error) {
            $this->error($error, $io);
        }
        $this->abort();
    }

    /**
     * @param int $retentionInDays retention in days
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    private function getDryRun(int $retentionInDays, ConsoleIo $io): void
    {
        $logs = $this->purgeService->dryRun($retentionInDays);
        $data[] = ['Action', 'Count'];
        /** @var \Passbolt\Log\Model\Entity\ActionLog $log */
        foreach ($logs as $log) {
            $data[] = [$log['action'], $log['count']];
        }
        $io->helper('Table')->output($data);
    }

    /**
     * @param int $retentionInDays retention in days
     * @param int $limit Maximum number of rows to purge.
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    private function purge(int $retentionInDays, int $limit, ConsoleIo $io): void
    {
        $nEntriesDeleted = $this->purgeService->purge($retentionInDays, $limit);
        $io->success(__('{0} action logs entries were deleted.', $nEntriesDeleted));
    }
}
