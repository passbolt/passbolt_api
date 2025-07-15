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
 * @since         2.0.0
 */
namespace PassboltTestData\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;

/**
 * Dummy data installer.
 */
class InsertCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Populate the database with dummy data test.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->addArgument('scenario', [
                'help' => 'The scenario to play.',
                'required' => true,
                'choices' => array_keys(Configure::read('PassboltTestData.scenarios'))
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $commands = $this->getShellTasks($args->getArgument('scenario'));
        $startTime = time();

        foreach ($commands as $command) {
            if (Configure::read('passbolt.edition') === 'ce') {
                if (strpos($command, 'PassboltTestData\Command\Pro') !== false) {
                    continue;
                }
            }
            try {
                $command = new $command();
                $options = [];
                $options['connection'] = $args->getOption('connection') ?? 'default';
                if (method_exists($command, "beforeExecute")) {
                    $command->beforeExecute();
                }
                $this->executeCommand($command, $this->formatOptions($args, $options));
                if (method_exists($command, "afterExecute")) {
                    $command->afterExecute();
                }
            } catch (\Exception $exception) {
                $message = __('Could not load command {0}, skipping.', get_class($command));
                $message .= ' ';
                $message .= __('Reason: {0}', $exception->getMessage());

                $io->error($message);
            }
        }

        $endTime = time();
        $dtF = new \DateTime("@$startTime");
        $dtT = new \DateTime("@$endTime");
        $diff = $dtF->diff($dtT)->format('%im %ss');
        $io->success('<success>' . __('Data inserted successfully in ' . $diff) . '</success>');

        return $this->successCode();
    }

    /**
     * Get the tasks to execute.
     * @param string $scenario Scenario.
     * @return array
     */
    protected function getShellTasks(string $scenario)
    {
        $scenarios = Configure::read('PassboltTestData.scenarios');
        $scenario = $scenarios[$scenario];

        return $scenario['install']['shellTasks'];
    }
}
