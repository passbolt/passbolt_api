<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace PassboltTestData\Lib;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Exception;
use PassboltTestData\Lib\SaveStrategy\SaveEntity;
use PassboltTestData\Lib\SaveStrategy\SaveMany;
use PassboltTestData\Lib\SaveStrategy\SaveSqlInfile;

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
     * Truncate data.
     * @var boolean
     */
    protected $_truncate = true;

    /**
     * execute() method.
     *
     * @throws Exception if the entity name is not defined
     * @return bool true on success
     */
    public function execute()
    {
        $startTime = time();
        if (is_null($this->entityName)) {
            throw new Exception('Entity name not defined');
        }

        $this->loadModel($this->entityName);
        $this->_Entity = $this->{$this->entityName};

        // Truncate the table.
        if ($this->_truncate) {
            $this->_Entity->deleteAll([]);
        }

        // $conn = \Cake\Datasource\ConnectionManager::get('default');
        // $conn->logQueries(true);

        // Insert the data in the db.
        $data = $this->getData();
        try {
            $this->save($data);
            // old fashion way (can be useful for debugging)
            // return $this->saveOneByOne();
            $endTime = time();
            $dtF = new \DateTime("@$startTime");
            $dtT = new \DateTime("@$endTime");
            $diff = $dtF->diff($dtT)->format('%im %ss');
            $this->out('Data for entity "' . $this->entityName . '" inserted (' . count($data) . ') in ' . $diff);
        } catch (Exception $e) {
            $this->err(sprintf('Data for %s cannot be imported', $this->entityName));
            $this->warn($e->getMessage());

            return false;
        }

        return true;
    }

    /**
     * Display the progress bar
     * @param int $total Number total of tasks
     * @return Progress
     */
    public function displayProgressBar($total)
    {
        $progress = null;

        if ($total > 1) {
            $progress = $this->helper('Progress');
            $progress->init([
                'total' => $total,
                'width' => 50,
            ]);
            $progress->draw();
        }

        return $progress;
    }

    /**
     * Save data.
     *
     * @param {array} $data The data to save
     * @return void
     */
    public function save(array $data = [])
    {
        $saveStrategy = Configure::read('PassboltTestData.saveStrategy');
        switch ($saveStrategy) {
            default:
            case 'default':
                $strategy = new SaveEntity($this);
                break;
            case 'many':
                $strategy = new SaveMany($this);
                break;
            case 'sqlInfile':
                $strategy = new SaveSqlInfile($this);
                break;
        }

        $strategy->save($data);
    }
}
