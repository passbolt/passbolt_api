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
 * @since         3.1.0
 */
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Migrations\Command\MigrationsMigrateCommand;

trait DatabaseAwareCommandTrait
{
    /**
     * Adds the option to specify the datasource.
     * Per default, the datasource is limited to default or test.
     *
     * @param \Cake\Console\ConsoleOptionParser $parser Parser
     * @param bool|null $isDefaultOrTest Restrict the choice to default or test, or not.
     * @return \Cake\Console\ConsoleOptionParser
     */
    protected function addDatasourceOption(
        ConsoleOptionParser $parser,
        ?bool $isDefaultOrTest = true
    ): ConsoleOptionParser {
        $options = [
            'short' => 'd',
            'default' => 'default',
            'help' => __('Datasource name.'),
        ];
        if ($isDefaultOrTest) {
            $options['choices'] = ['default', 'test'];
        }

        return $parser->addOption('datasource', $options);
    }

    /**
     * Run the migration migrate command, mapping the -c option to datasource.
     * No lock is created.
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return int|null
     */
    protected function runMigrationsMigrateCommand(Arguments $args, ConsoleIo $io): ?int
    {
        $options = $this->formatOptions($args, ['--no-lock', '-c', $args->getOption('datasource')]);

        return $this->executeCommand(MigrationsMigrateCommand::class, $options, $io);
    }
}
