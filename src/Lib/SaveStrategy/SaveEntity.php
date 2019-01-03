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
 * @since         2.7.0
 */
namespace PassboltTestData\Lib\SaveStrategy;

use Cake\Console\Shell;

class SaveEntity
{
    /**
     * The shell the strategy is executing on.
     *
     * @var Shell
     */
    private $shell;

    /**
     * Constructor
     *
     * @param Shell $shell The console the strategy is executing by.
     */
    public function __construct(Shell $shell)
    {
        $this->shell = $shell;
    }

    /**
     * Save data
     *
     * @param array $data The data to save
     * @return bool
     */
    public function save(array $data = [])
    {
        foreach ($data as $row) {
            try {
                $this->saveEntity($row);
            } catch (Exception $e) {
                $this->shell->err(sprintf('Data "%s" from "%s" could not be inserted', $row[array_keys($row)[0]]['id'], $this->shell->entityName));
                $this->shell->err(print_r($row, true));
                $this->shell->warn($e->getMessage());

                return false;
            }
        }

        return true;
    }

    /**
     * Insert an entity.
     *
     * @param array $data The entity data
     * @throws Exception if the entity can not be validated or saved
     * @return void
     */
    public function saveEntity(array $data = [])
    {
        $entity = $this->shell->_Entity->newEntity();
        $entity->accessible('*', true);
        $entity->set($data);

        $errors = $entity->getErrors();
        if ($errors) {
            $this->shell->out('Unable to validate the entity data');
            $this->shell->out(json_encode($errors));
            $this->shell->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }

        if (!$this->shell->_Entity->save($entity, ['checkRules' => false, 'atomic' => false])) {
            $errors = $entity->getErrors();
            $this->shell->out('Unable to save the entity');
            $this->shell->out(json_encode($errors));
            $this->shell->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }
    }
}
