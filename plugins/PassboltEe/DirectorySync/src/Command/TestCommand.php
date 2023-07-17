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

class TestCommand extends DirectorySyncCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Test Sync'));

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
            $directoryResults = $ldapDirectory->fetchDirectoryData();
            $data = [
                'users' => array_values($directoryResults->getUsers()),
                'groups' => array_values($directoryResults->getGroups()),
            ];
        } catch (\Exception $e) {
             $io->err($e->getMessage());

            return $this->errorCode();
        }

        $this->displayEntries($data, $io);

        $tree = $ldapDirectory->getFilteredDirectoryResults()->getFlattenedTree();
        $this->displayFlattenedTree($tree, $io);

        $data['users'] = $directoryResults->getInvalidUsers();
        $data['groups'] = $directoryResults->getInvalidGroups();
        $this->displayInvalidEntries($data, $io);

        return $this->successCode();
    }

    /**
     * Display flattened tree.
     *
     * @param array $flattenedTree flattened tree content.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayFlattenedTree($flattenedTree, ConsoleIo $io)
    {
        $output = [];
        $output[] = __('<info>Root</info>');
        foreach ($flattenedTree as $row) {
            if ($row->isUser()) {
                $output[] = ' ' . '|- ' . $this->userToString($row);
            } else {
                $level = $row->level;
                $output[] = ' ' . str_repeat('|  ', $row->level) . '|- ' . "<info>{$this->groupToString($row)}</info>";
                foreach ($row->group['users'] as $user) {
                    $output[] = ' ' . str_repeat('|  ', $level + 1) . '|- ' . $this->userToString($user);
                }
            }
        }

        $io->nl();
        $io->info(__('The entities are organized with the following structure'));
        $io->nl();
        foreach ($output as $o) {
            $io->out($o);
        }
        $io->nl();
    }

    /**
     * Convert a group to a string.
     *
     * @param mixed $group group
     * @return string|null
     */
    protected function groupToString($group)
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
     *
     * @param mixed $user user
     * @return string|null
     */
    protected function userToString($user)
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
     *
     * @param array $data data
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayEntries($data, $io)
    {
        $output = [];
        $output[] = [__('groups'), __('users')];

        $maxEntries = max(count($data['users']), count($data['groups']));
        for ($i = 0; $i < $maxEntries; $i++) {
            // Handle user.
            $groupStr = '';
            if (isset($data['groups'][$i])) {
                $groupStr = $this->groupToString($data['groups'][$i]);
            }

            $userStr = '';
            if (isset($data['users'][$i])) {
                $userStr = $this->userToString($data['users'][$i]);
            }

            $output[] = [$groupStr, $userStr];
        }

        $io->info(__('The following groups and users have been found'));
        $io->out($io->nl(1));
        $io->helper('Table')->output($output);
    }

    /**
     * Display invalid objects.
     *
     * @param array $data data
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayInvalidEntries(array $data, ConsoleIo $io)
    {
        if (count($data['users'])) {
            $io->hr();
            $io->err(
                __(
                    '{0} users returned by your directory are invalid and will be ignored during synchronization',
                    count($data['users'])
                )
            );
            $io->err(__('bin/cake directory_sync test --verbose for more details'));
            $io->hr();
            foreach ($data['users'] as $user) {
                $io->verbose(__('Error: ') . $user->getErrorsAsString());
                $io->verbose(json_encode($user->toArray(), JSON_PRETTY_PRINT));
            }
        }

        if (count($data['groups'])) {
            $io->hr();
            $io->err(
                __(
                    '{0} group(s) returned by your directory are invalid and will be ignored during synchronization',
                    count($data['groups'])
                )
            );
            $io->hr();
            foreach ($data['groups'] as $group) {
                $io->verbose(__('Error: ') . $group->getErrorsAsString());
                $io->verbose(json_encode($group->toArray(), JSON_PRETTY_PRINT));
            }
        }
    }
}
