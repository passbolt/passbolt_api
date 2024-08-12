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
 * @since         4.2.0
 */
namespace App\Command;

use App\Service\EmailQueue\PurgeEmailQueueService;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class PurgeEmailQueueCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Purge email queue content. Remove sent emails and unsent emails with 3 retries.');
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $deleted = (new PurgeEmailQueueService())->purge();

        if (!$deleted) {
            $io->out(__('Nothing to delete.'));
        } else {
            if ($deleted === 1) {
                $io->out(__('One email was deleted from the queue.'));
            } else {
                $io->out(__('{0} emails were deleted from the queue.', $deleted));
            }
        }

        return $this->successCode();
    }
}
