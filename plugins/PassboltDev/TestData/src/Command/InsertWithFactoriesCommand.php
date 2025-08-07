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
 * @since         2.0.0
 */

namespace Passbolt\TestData\Command;

use App\Command\PassboltCommand;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use DateTime;
use Exception;

class InsertWithFactoriesCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Populate the database with dummy data using fixture factories.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->addOption('truncate', [
                'help' => 'Truncates all tables apart from roles and resource_types.',
                'default' => false,
                'boolean' => true,
            ])
            ->addArgument('scenario', [
                'help' => 'The scenario to play.',
                'required' => true,
                'choices' => [
                    'default' => 'default',
                    'demo' => 'demo',
                    'large' => 'large',
                    'security' => 'security',
                ],
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $startTime = time();

        if ($args->getOption('truncate')) {
            $this->truncateTables();
        }

        try {
            $this->getScenarioClass($args)->load();
        } catch (Exception $e) {
            $this->error(__('There is already dummy data in the DB, try with the --truncate option.'), $io);
            $io->error($e->getMessage());
            $this->abort();
        }

        $endTime = time();
        $dtF = new DateTime("@$startTime");
        $dtT = new DateTime("@$endTime");
        $diff = $dtF->diff($dtT)->format('%im %ss');
        $io->success('<success>' . __('Data inserted successfully in ' . $diff) . '</success>');

        return $this->successCode();
    }

    /**
     * @param \Cake\Console\Arguments $arguments
     * @return \CakephpFixtureFactories\Scenario\FixtureScenarioInterface
     */
    private function getScenarioClass(Arguments $arguments): FixtureScenarioInterface
    {
        $scenarioName = $arguments->getArgument('scenario');
        $scenarioClassName = 'Passbolt\TestData\Scenario\TestData' . ucfirst($scenarioName) . 'Scenario';

        return new $scenarioClassName($scenarioClassName);
    }

    /**
     * @return void
     */
    private function truncateTables(): void
    {
        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        $tablesList = $connection->getSchemaCollection()->listTables();
        foreach ($tablesList as $table) {
            //skips roles, resource_types, ui_actions, rbacs, phinxlog tables
            if ($table === 'roles' || $table === 'resource_types' || $table === 'ui_actions' || $table === 'rbacs' || $table === 'phinxlog') { //phpcs:ignore
                continue;
            }
            $table = $this->fetchTable($table);
            $table->deleteAll([]);
        }
    }
}
