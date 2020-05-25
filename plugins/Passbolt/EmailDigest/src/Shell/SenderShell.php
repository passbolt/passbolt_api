<?php
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

namespace Passbolt\EmailDigest\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Exception;
use Passbolt\EmailDigest\Service\SendEmailBatchService;

class SenderShell extends Shell
{
    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription('Sends a batch of queued emails as emails digests.')
            ->addOption(
                'limit',
                [
                    'short' => 'l',
                    'help' => 'How many emails should be sent in this batch?',
                    'default' => Configure::read('passbolt.plugins.EmailDigest.batchSizeLimit'),
                ]
            );

        return $parser;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function main()
    {
        $emailSenderService = new SendEmailBatchService();

        $emailSenderService->sendNextEmailsBatch($this->getParam('limit'));
    }

    /**
     * Return a parameter passed to the command line
     * @param string $name Name of the parameter
     * @return int|null
     */
    private function getParam(string $name)
    {
        return $this->params[$name] ?? null;
    }
}
