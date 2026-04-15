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
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class IgnoreDeleteCommand extends DirectorySyncCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Stop ignoring a record.'))
            ->addOption('id', [
                'help' => 'The record UUID.',
                'default' => 'true',
            ])
            ->addOption('model', [
                'help' => 'The model name (Users, Groups, DirectoryEntries).',
                'default' => 'true',
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $foreignModel = $args->getOption('model');
        $foreignKey = $args->getOption('id');

        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            $io->err(__('The record model is not valid.'));

            return $this->errorCode();
        }
        try {
            /** @var \Passbolt\DirectorySync\Model\Table\DirectoryIgnoreTable $DirectoryIgnore */
            $DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
            $ignored = $DirectoryIgnore->get($foreignKey);
            if ($ignored->foreign_model !== $foreignModel) {
                throw new RecordNotFoundException(__('The record could not be found.'));
            }
            $DirectoryIgnore->delete($ignored);
            $this->success(__('The record will stop being ignored in the next directory synchronization.'), $io);

            return $this->successCode();
        } catch (RecordNotFoundException $exception) {
            $io->err($exception->getMessage());

            return $this->successCode();
        }
    }
}
