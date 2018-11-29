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
use Cake\ORM\TableRegistry;
use Passbolt\DirectorySync\Utility\Alias;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\DirectorySync\Utility\LdapDirectory;

class TestTask extends AppShell
{

    protected $DirectoryEntries;
    protected $Groups;
    protected $Users;

    /**
     * Initialize.
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        $this->DirectoryEntries = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryEntries');
        $this->Users = TableRegistry::get('Users');
        $this->Groups = TableRegistry::get('Groups');
    }

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
        $parser->setDescription(__('Test Sync'));

        return $parser;
    }

    /**
     * Main shell entry point
     *
     * @return bool true if successful
     */
    public function main()
    {
        try {
            $directoryOrgSettings = DirectoryOrgSettings::get();
            $ldapDirectory = new LdapDirectory($directoryOrgSettings);
            $data = [
                'users' => $ldapDirectory->getUsers(),
                'groups' => $ldapDirectory->getGroups(),
            ];
        } catch (\Exception $e) {
             $this->err($e->getMessage());

            return false;
        }

        $validUsers = [];
        foreach ($data['users'] as $entryUser) {
            $entryUser['foreign_model'] = Alias::MODEL_USERS;
            $entry = $this->DirectoryEntries->buildEntityFromData($entryUser);
            if (!empty($entry->getErrors())) {
                $this->err(__('There was an error with a user entry'));
                $this->err($entry->getErrors());
                continue;
            }
            $validUsers[] = $entryUser;
        }

        $validGroups = [];
        foreach ($data['groups'] as $entryGroup) {
            $entryGroup['foreign_model'] = Alias::MODEL_GROUPS;
            $entry = $this->DirectoryEntries->buildEntityFromData($entryGroup);
            if (!empty($entry->getErrors())) {
                $this->err(__('There was an error with a group entry'));
                $this->err($entry->getErrors());
                continue;
            }
            $validGroups[] = $entryGroup;
        }

        $data['users'] = $validUsers;
        $data['groups'] = $validGroups;
        $this->displayValidObjects($data);

        return true;
    }

    /**
     * Display valid objects.
     * @param array $data data
     * @return void
     */
    protected function displayValidObjects($data)
    {
        $output = [];
        $output[] = [__('groups'), __('users')];

        $maxEntries = max(count($data['users']), count($data['groups']));
        for ($i = 0; $i < $maxEntries; $i++) {
            // Handle user.
            $groupStr = '';
            if (isset($data['groups'][$i]) && isset($data['groups'][$i]['group'])) {
                $groupStr = __(
                    '{0} ({1} members)',
                    $data['groups'][$i]['group']['name'],
                    count($data['groups'][$i]['group']['users'])
                );
            }

            $userStr = '';
            if (isset($data['users'][$i]) && isset($data['users'][$i]['user'])) {
                $userStr = __(
                    '{0} {1} ({2})',
                    $data['users'][$i]['user']['profile']['first_name'],
                    $data['users'][$i]['user']['profile']['last_name'],
                    $data['users'][$i]['user']['username']
                );
            }

            $output[] = [$groupStr, $userStr];
        }

        $this->info(__('The following groups and users have been found'));
        $io = $this->getIo();
        $io->helper('Table')->output($output);
    }
}
