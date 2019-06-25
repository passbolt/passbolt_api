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
        $this->Users = TableRegistry::getTableLocator()->get('Users');
        $this->Groups = TableRegistry::getTableLocator()->get('Groups');
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
     * @throws \Exception
     */
    public function main()
    {
        try {
            $directoryOrgSettings = DirectoryOrgSettings::get();
            $ldapDirectory = new LdapDirectory($directoryOrgSettings);
            $directoryResults = $ldapDirectory->fetchDirectoryData();
            $data = [
                'users' => array_values($directoryResults->getUsers()),
                'groups' => array_values($directoryResults->getGroups()),
            ];
        } catch (\Exception $e) {
             $this->err($e->getMessage());

            return false;
        }

        $this->displayEntries($data);

        $tree = $ldapDirectory->getFilteredDirectoryResults()->getFlattenedTree();
        $this->displayFlattenedTree($tree);

        $data['users'] = $directoryResults->getInvalidUsers();
        $data['groups'] = $directoryResults->getInvalidGroups();
        $this->displayInvalidEntries($data);

        return true;
    }

    /**
     * Display flattened tree.
     * @param array $flattenedTree flattened tree content.
     * @return void
     */
    protected function displayFlattenedTree($flattenedTree)
    {
        $output = [];
        $output[] = __('<info>Root</info>');
        foreach ($flattenedTree as $row) {
            if ($row->isUser()) {
                $output[] = ' ' . '|- ' . $this->_userToString($row);
            } else {
                $level = $row->level;
                $output[] = ' ' . str_repeat('|  ', $row->level) . '|- ' . "<info>{$this->_groupToString($row)}</info>";
                foreach ($row->group['users'] as $user) {
                    $output[] = ' ' . str_repeat('|  ', $level + 1) . '|- ' . $this->_userToString($user);
                }
            }
        }

        $io = $this->getIo();
        $io->out($io->nl(1));
        $this->info(__('The entities are organized with the following structure'));
        $io->out($io->nl(1));
        foreach ($output as $o) {
            $io->out($o);
        }
        $io->out($io->nl(1));
    }

    /**
     * Convert a group to a string.
     * @param mixed $group group
     *
     * @return string|null
     */
    protected function _groupToString($group)
    {
        if (!$group->hasErrors()) {
            $groupStr = __(
                '{0} ({1} members)',
                $group['group']['name'],
                count($group['group']['users'])
            );
        } else {
            $groupStr = __('<error>{0}</error>', $group->dn);
        }

        return $groupStr;
    }

    /**
     * Convert a user to a string.
     * @param mixed $user user
     *
     * @return string|null
     */
    protected function _userToString($user)
    {
        if (!$user->hasErrors()) {
            $userStr = __(
                '{0} {1} ({2})',
                $user['user']['profile']['first_name'],
                $user['user']['profile']['last_name'],
                $user['user']['username']
            );
        } else {
            $userStr = __('<error>{0}</error>', $user->dn);
        }

        return $userStr;
    }

    /**
     * Display valid objects.
     * @param array $data data
     * @return void
     */
    protected function displayEntries($data)
    {
        $output = [];
        $output[] = [__('groups'), __('users')];

        $maxEntries = max(count($data['users']), count($data['groups']));
        for ($i = 0; $i < $maxEntries; $i++) {
            // Handle user.
            $groupStr = '';
            if (isset($data['groups'][$i])) {
                $groupStr = $this->_groupToString($data['groups'][$i]);
            }

            $userStr = '';
            if (isset($data['users'][$i])) {
                $userStr = $this->_userToString($data['users'][$i]);
            }

            $output[] = [$groupStr, $userStr];
        }

        $this->info(__('The following groups and users have been found'));
        $io = $this->getIo();
        $io->out($io->nl(1));
        $io->helper('Table')->output($output);
    }

    /**
     * Display invalid objects.
     *
     * @param array $data data
     * @return void
     */
    protected function displayInvalidEntries(array $data)
    {
        if (count($data['users'])) {
            $this->hr();
            $this->err(__('{0} users returned by your directory are invalid and will be ignored during synchronization', count($data['users'])));
            $this->err(__('bin/cake directory_sync test --verbose for more details'));
            $this->hr();
            foreach ($data['users'] as $user) {
                $this->verbose(__('Error: ') . $user->getErrorsAsString());
                $this->verbose(json_encode($user->toArray(), JSON_PRETTY_PRINT));
            }
        }

        if (count($data['groups'])) {
            $this->hr();
            $this->err(__('{0} group(s) returned by your directory are invalid and will be ignored during synchronization', count($data['groups'])));
            $this->hr();
            foreach ($data['groups'] as $group) {
                $this->verbose(__('Error: ') . $group->getErrorsAsString());
                $this->verbose(json_encode($group->toArray(), JSON_PRETTY_PRINT));
            }
        }
    }
}
