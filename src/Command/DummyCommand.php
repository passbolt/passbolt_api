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
 * @since         2.8.0
 */
namespace PassboltTestData\Command;

use App\Command\PassboltCommand;
use Cake\Console\ConsoleOptionParser;

/**
 * Dummy shell command.
 */
class DummyCommand extends PassboltCommand
{
    /**
     * @var array of linked tasks
     */
    public $tasks = [
        'PassboltTestData.Insert'
    ];

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'));

        $parser->addArgument('Insert', [
            'help' => __d('cake_console', 'Insert dummies'),
        ]);

        return $parser;
    }
}
