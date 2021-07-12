<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\DirectorySync\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\LdapDirectory;

class DebugCommand extends DirectorySyncCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Debug configuration helper'));

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        try {
            $directoryOrgSettings = DirectoryOrgSettings::get();
            $ldapDirectory = new LdapDirectory($directoryOrgSettings);
            $userFilter = $ldapDirectory->getUserFiltersAsString();
            $groupFilter = $ldapDirectory->getGroupFiltersAsString();

            $io->out(__('<info>Configuration source:</info> {0}', $directoryOrgSettings->getSource()));
            $io->nl();
            $io->info(__('The following filters are in use'));
            $io->out(__("users:\n{0}", [$userFilter]));
            $io->nl();
            $io->out(__("groups:\n{0}", [$groupFilter]));
            $io->nl();
        } catch (\Exception $e) {
            $this->error($e->getMessage(), $io);

            return $this->errorCode();
        }

        return $this->successCode();
    }
}
