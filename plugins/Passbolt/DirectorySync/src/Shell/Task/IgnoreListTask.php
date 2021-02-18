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
namespace Passbolt\DirectorySync\Shell\Task;

use App\Shell\AppShell;
use Cake\ORM\TableRegistry;

class IgnoreListTask extends AppShell
{
    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize()
    {
        parent::initialize();
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
        $parser->setDescription(__('List records ignored during directory synchronization process.'));

        return $parser;
    }

    /**
     * Main shell entry point
     *
     * @return bool true if successful
     */
    public function main()
    {
        $io = $this->getIo();
        // Output some data as a table.

        $this->DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
        $di = $this->DirectoryIgnore->find()
            ->select()
            ->contain(['Users', 'Groups', 'DirectoryEntries'])
            ->toArray();
        $records[] = ['Model', 'Name', 'Created', 'ID'];
        foreach ($di as $i => $record) {
            switch ($record->foreign_model) {
                case 'Users':
                    $name = $record->user->username;
                    break;
                case 'Groups':
                    $name = $record->group->name;
                    break;
                case 'DirectoryEntries':
                    if (isset($record->directory_entry->directory_name)) {
                        $name = $record->directory_entry->directory_name;
                    } else {
                        $name = $record->id;
                    }
                    break;
            }
            $records[] = [$record->foreign_model, $name, $record->created->timeAgoInWords(), $record->id];
        }
        if (count($records) === 1) {
            $this->success(__('No record is being ignored. The next job will try to synchronize all records.'));

            return true;
        }

        $io->helper('Table')->output($records);
        $this->out();
        $this->out('[help] you can stop ignoring records with the following command.');
        $this->out('       ./bin/cake directory_sync ignore-create [ID]');
        $this->out();

        return true;
    }
}
