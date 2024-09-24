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

use PassboltTestData\Command\Alt0\GroupsDataCommand;
use PassboltTestData\Command\Alt0\GroupsUsersDataCommand;
use PassboltTestData\Command\Alt0\PermissionsDataCommand;
use PassboltTestData\Command\Alt0\SecretsDataCommand;
use PassboltTestData\Command\Base\FoldersDataCommand;
use PassboltTestData\Command\Base\FoldersPermissionsDataCommand;
use PassboltTestData\Command\Base\FoldersRelationsDataCommand;
use PassboltTestData\Command\Base\GpgkeysDataCommand;
use PassboltTestData\Command\Base\ProfilesDataCommand;
use PassboltTestData\Command\Base\ResourcesDataCommand;
use PassboltTestData\Command\Base\UsersDataCommand;
use PassboltTestData\Command\InsertCommand;

class InsertDummyDataCommand extends InsertCommand
{
    /**
     * Get the tasks to execute.
     *
     * @param string $scenario Scenario.
     * @return array
     */
    protected function getShellTasks(string $scenario)
    {
        if ($scenario === 'default') {
            return [
                UsersDataCommand::class,
                GpgkeysDataCommand::class,
                ProfilesDataCommand::class,
                GroupsDataCommand::class,
                GroupsUsersDataCommand::class,
                ResourcesDataCommand::class,
                SecretsDataCommand::class,
                PermissionsDataCommand::class,
                FoldersDataCommand::class,
                FoldersRelationsDataCommand::class,
                FoldersPermissionsDataCommand::class,
            ];
        }

        return parent::getShellTasks($scenario);
    }
}
