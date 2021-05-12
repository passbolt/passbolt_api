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
use Passbolt\EmailDigest\Service\PreviewEmailBatchService;

class PreviewCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Preview a batch of queued emails as emails digests.'))
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
        $emailSenderService = new PreviewEmailBatchService();

        $limit = (int)$args->getOption('limit');
        $previews = $emailSenderService->previewNextEmailsBatch($limit);
        foreach ($previews as $preview) {
            $io->out($preview->getHeaders());
            if ($args->getOption('body') === true) {
                $io->out($preview->getContent());
            }
            $io->out('------------------------');
        }

        return self::CODE_SUCCESS;
    }
}
