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

use App\Error\Exception\ValidationException;
use App\Shell\AppShell;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class IgnoreDeleteTask extends AppShell
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
     * Main shell entry point
     *
     * @return bool true if successful
     */
    public function main()
    {
        $foreignModel = $this->param('model');
        $foreignKey = $this->param('id');

        if (!Validation::inList($foreignModel, ['Groups', 'Users', 'DirectoryEntries'])) {
            $this->err(__('The record model is not valid.'));
        }
        try {
            $DirectoryIgnore = TableRegistry::getTableLocator()->get('Passbolt/DirectorySync.DirectoryIgnore');
            $ignored = $DirectoryIgnore->get($foreignKey);
            if ($ignored->foreign_model !== $foreignModel) {
                throw new RecordNotFoundException(__('The record could not be found.'));
            }
            $DirectoryIgnore->delete($ignored);
            $this->success(__('The record will stop being ignored in the next directory synchronization.'));

            return true;
        } catch (RecordNotFoundException $exception) {
            $this->err($exception->getMessage());

            return false;
        }
    }
}
