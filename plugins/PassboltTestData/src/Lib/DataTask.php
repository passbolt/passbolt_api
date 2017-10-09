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

namespace PassboltTestData\Lib;

use Cake\Console\Shell;
use Exception;

/**
 * Data shell task.
 */
abstract class DataTask extends Shell
{
    /**
     * The entity name the data task target.
     * @var null
     */
    public $entityName = null;

    /**
     * The entity model
     * @var null
     */
    protected $_Entity = null;

    /**
     * execute() method.
     *
     * @return bool|int|null Success or error code.
     * @throws Exception
     */
    public function execute()
    {
        if (is_null($this->entityName)) {
            throw new Exception('Entity name not defined');
        }

        $this->loadModel($this->entityName);
        $this->_Entity = $this->{$this->entityName};

        // Flush all the previously stored data.
        $this->_Entity->deleteAll([]);

        $conn = \Cake\Datasource\ConnectionManager::get('default');
        $conn->logQueries(true);

        // Insert the data in the db.
        $data = $this->_getData();
        foreach ($data as $row) {
            $this->saveEntity($row);
        }
        $this->out('Data for entity "' . $this->entityName . '" inserted (' . count($data) . ')');
    }

    /**
     * Insert an entity.
     *
     * @param $data
     * @throws Exception
     */
    public function saveEntity($data)
    {
        $entity = $this->_Entity->newEntity();
        $entity->accessible('*', true);
        $entity->set($data);

        $errors = $entity->getErrors();
        if ($errors) {
            $this->out('Unable to validate the entity data');
            $this->out(json_encode($errors));
            $this->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }

        if (!$this->_Entity->save($entity)) {
            $errors = $entity->getErrors();
            $this->out('Unable to save the entity');
            $this->out(json_encode($errors));
            $this->out(json_encode($data));
            throw new Exception('Unable to save the entity data');
        }
    }
}
