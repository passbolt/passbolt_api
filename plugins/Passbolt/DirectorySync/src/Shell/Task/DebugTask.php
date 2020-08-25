<?php
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
namespace Passbolt\DirectorySync\Shell\Task;

use App\Shell\AppShell;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\LdapDirectory;

class DebugTask extends AppShell
{

    protected $DirectoryEntries;
    protected $Groups;
    protected $Users;

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @throws \Exception
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(__('Debug configuration helper'));

        return $parser;
    }

    /**
     * Main shell entry point
     *
     * @return bool true if successful
     * @throws \Exception
     */
    public function main()
    {
        try {
            $directoryOrgSettings = DirectoryOrgSettings::get();
            $ldapDirectory = new LdapDirectory($directoryOrgSettings);
            $userFilter = $ldapDirectory->getUserFiltersAsString();
            $groupFilter = $ldapDirectory->getGroupFiltersAsString();

            $this->out(__('<info>Configuration source:</info> {0}', $directoryOrgSettings->getSource()));
            echo $this->nl(1);
            $this->info(__('The following filters are in use'));
            $this->out(__("users:\n{0}", [$userFilter]));
            echo $this->nl(1);
            $this->out(__("groups:\n{0}", [$groupFilter]));
            echo $this->nl(1);
        } catch (\Exception $e) {
            $this->err($e->getMessage());

            return false;
        }

        return true;
    }
}
