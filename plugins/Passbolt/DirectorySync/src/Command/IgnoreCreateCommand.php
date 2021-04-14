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

use App\Error\Exception\ValidationException;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class IgnoreCreateCommand extends DirectorySyncCommand
{
    use SyncCommandTrait;

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Add a record as to be ignored.'))
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

        $this->pad = 0;
        $foreignModel = $args->getOption('model');
        $foreignKey = $args->getOption('id');

        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            $io->error(__('The record model is not valid.'));

            return $this->errorCode();
        }
        try {
            /** @var \Passbolt\DirectorySync\Model\Table\DirectoryIgnoreTable $DirectoryIgnore */
            $DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
            $DirectoryIgnore->createOrFail($foreignModel, $foreignKey);
            $this->success(__('The record will be ignored in the next directory synchronization.'), $io);

            return $this->successCode();
        } catch (ValidationException $exception) {
            $io->err($exception->getMessage());
            $this->displayValidationError($exception->getEntity()->getErrors(), $io);

            return $this->errorCode();
        } catch (\Exception $exception) {
            $io->err($exception->getMessage());

            return $this->errorCode();
        }
    }
}
