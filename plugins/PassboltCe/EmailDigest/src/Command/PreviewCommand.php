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
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\EmailDigest\Service\PreviewEmailBatchService;

class PreviewCommand extends PassboltCommand
{
    /**
     * @var string
     */
    public const EMAIL_SEPARATOR = '------------------------';

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Preview a batch of queued emails as emails digests.');
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
                'help' => __('How many emails should be in this batch?'),
                'default' => Configure::read('passbolt.plugins.emailDigest.batchSizeLimit'),
            ])
            ->addOption('body', [
                'boolean' => true,
                'help' => __('Display the email content?'),
                'default' => false,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $limit = (int)$args->getOption('limit');
        /** @var \EmailQueue\Model\Table\EmailQueueTable $EmailQueueTable */
        $EmailQueueTable = TableRegistry::getTableLocator()->get('EmailQueue.EmailQueue');
        $emailQueues = $EmailQueueTable->getBatch($limit);

        Configure::write('App.baseUrl', '/');

        if (!empty($emailQueues)) {
            // we release the locks as soon as we get the emails
            // we don't want to block the next batch ran by a cron job because of lock.
            // technically, to do better, we should write the same query ran in getBatch method without locking the emails
            $EmailQueueTable->releaseLocks(Hash::extract($emailQueues, '{n}.id'));
        }

        $previews = (new PreviewEmailBatchService())->previewNextEmailsBatch($emailQueues);
        foreach ($previews as $preview) {
            $io->out($preview->getHeaders());
            if ($args->getOption('body') === true) {
                $io->out($preview->getContent());
            }
            $io->out(self::EMAIL_SEPARATOR);
        }

        return self::CODE_SUCCESS;
    }
}
