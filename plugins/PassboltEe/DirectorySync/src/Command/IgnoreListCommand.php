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

/**
 * Class IgnoreListCommand
 */
class IgnoreListCommand extends DirectorySyncCommand
{
    /**
     * @var \Passbolt\DirectorySync\Model\Table\DirectoryIgnoreTable
     */
    protected $DirectoryIgnore;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->DirectoryIgnore = $this->fetchTable('Passbolt/DirectorySync.DirectoryIgnore');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('List records ignored during directory synchronization process.'));

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Output some data as a table.
        $di = $this->DirectoryIgnore->find()
            ->select()
            ->contain(['Users', 'Groups', 'DirectoryEntries'])
            ->toArray();
        $records[] = ['Model', 'Name', 'Created', 'ID'];
        foreach ($di as $i => $record) {
            $name = '';
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
            $this->success(__('No record is being ignored. The next job will try to synchronize all records.'), $io);

            return $this->successCode();
        }

        $io->helper('Table')->output($records);
        $io->out();
        $io->out('[help] you can stop ignoring records with the following command.');
        $io->out('       ./bin/cake directory_sync ignore-create [ID]');
        $io->out();

        return $this->successCode();
    }
}
