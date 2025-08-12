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
 * @since         4.10.0
 */
namespace Passbolt\Metadata\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Passbolt\TestData\Command\Base\FoldersDataCommand;
use Passbolt\TestData\Command\Base\FoldersPermissionsDataCommand;
use Passbolt\TestData\Command\Base\FoldersRelationsDataCommand;
use Passbolt\TestData\Command\Base\GpgkeysDataCommand;
use Passbolt\TestData\Command\Base\GroupsDataCommand;
use Passbolt\TestData\Command\Base\GroupsUsersDataCommand;
use Passbolt\TestData\Command\Base\PermissionsDataCommand;
use Passbolt\TestData\Command\Base\ProfilesDataCommand;
use Passbolt\TestData\Command\Base\ResourcesDataCommand;
use Passbolt\TestData\Command\Base\SecretsDataCommand;
use Passbolt\TestData\Command\Base\UsersDataCommand;
use Passbolt\TestData\Command\InsertCommand;

class InsertDummyDataCommand extends InsertCommand
{
    /**
     * Get the tasks to execute.
     *
     * @param string $scenario Scenario.
     * @return array
     */
    protected function getShellTasks(string $scenario): array
    {
        if ($scenario === 'default') {
            return [
                UsersDataCommand::class,
                ProfilesDataCommand::class,
                GpgkeysDataCommand::class,
                GroupsDataCommand::class,
                GroupsUsersDataCommand::class,
                ResourcesDataCommand::class,
                PermissionsDataCommand::class,
                SecretsDataCommand::class,
                FoldersDataCommand::class,
                FoldersRelationsDataCommand::class,
                FoldersPermissionsDataCommand::class,
            ];
        }

        return parent::getShellTasks($scenario);
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        if (!Configure::read('debug') || !Configure::read('passbolt.selenium.active')) {
            $io->out('This command is to be used for testing and development purpose only.');
            $io->out('Please enable DEBUG and PASSBOLT_SELENIUM_ACTIVE flags.');

            return $this->errorCode();
        }

        return parent::execute($args, $io);
    }
}
