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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Command;

use App\Command\PassboltCommand;
use App\Service\Command\GetUserCommandService;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;

/**
 * Base ScimSettingsCommand.
 */
abstract class ScimSettingsCommand extends PassboltCommand
{
    /**
     * @param \App\Service\Command\GetUserCommandService $getUserCommandService
     */
    public function __construct(
        protected GetUserCommandService $getUserCommandService,
    ) {
        parent::__construct();
    }

    /**
     * Hook method for defining this command's option parser.
     *
     * @see https://book.cakephp.org/4/en/console-commands/commands.html#defining-arguments-and-options
     * @param \Cake\Console\ConsoleOptionParser $parser The parser to be defined
     * @return \Cake\Console\ConsoleOptionParser The built parser.
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('username', [
                'short' => 'u',
                'required' => true,
                'help' => __('The user name (email).'),
            ])
            ->addOption('id', [
                'short' => 'i',
                'required' => false,
                'help' => __('The SCIM settings uuid.'),
            ]);

        return $parser;
    }

    /**
     * Check if the command is run in a develop environment
     *
     * @param \Cake\Console\ConsoleIo $io
     * @return bool
     */
    protected function isDevelopEnvironment(ConsoleIo $io): bool
    {
        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            $io->out('Please enable DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.');

            return false;
        }

        return true;
    }
}
