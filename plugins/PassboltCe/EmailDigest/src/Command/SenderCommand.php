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
 * @since         2.13.0
 */

namespace Passbolt\EmailDigest\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Passbolt\EmailDigest\Service\SendEmailBatchService;

class SenderCommand extends PassboltCommand
{
    private ?SendEmailBatchService $sendEmailBatchService;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->sendEmailBatchService = new SendEmailBatchService();
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Sends a batch of queued emails as emails digests.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser
            ->addOption('limit', [
                'short' => 'l',
                'help' => __('How many emails should be sent in this batch?'),
                'default' => Configure::read('passbolt.plugins.emailDigest.batchSizeLimit'),
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $limit = (int)$args->getOption('limit');
        /** @var \EmailQueue\Model\Table\EmailQueueTable $emailQueueTable */
        $emailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $emails = $emailQueueTable->getBatch($limit);
        $result = $this->sendEmailBatchService->sendNextEmailsBatch($emails);
        $emailQueueCounts = $this->sendEmailBatchService->getEmailQueueCounts();

        Log::info(json_encode([
            'message' => 'Email digest sender command',
            'sent' => $result->sent(), // no of emails sent
            'failed' => $result->failed(), // no of emails failed
            'pending' => $emailQueueCounts['not_sent'],
            'locked' => $emailQueueCounts['locked'],
        ]));

        return $this->successCode();
    }
}
