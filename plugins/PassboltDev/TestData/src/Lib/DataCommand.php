<?php
declare(strict_types=1);

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

namespace Passbolt\TestData\Lib;

use App\Command\PassboltCommand;
use Cake\Command\Helper\ProgressHelper;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Core\Configure;
use Cake\ORM\Table;
use DateTime;
use Exception;
use Passbolt\TestData\Lib\SaveStrategy\SaveEntity;
use Passbolt\TestData\Lib\SaveStrategy\SaveMany;
use Passbolt\TestData\Lib\SaveStrategy\SaveSqlInfile;

/**
 * Data command.
 */
abstract class DataCommand extends PassboltCommand
{
    /**
     * The entity name the data task target.
     */
    public string $entityName;

    /**
     * The entity model
     *
     * @var \Cake\ORM\Table
     */
    public ?Table $Table = null;

    /**
     * Truncate data.
     *
     * @var bool
     */
    protected bool $_truncate = true;

    /**
     * Console IO
     *
     * @var \Cake\Console\ConsoleIo
     */
    public ConsoleIo $io;

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $startTime = time();

        $this->Table = $this->fetchTable($this->entityName);

        // Truncate the table.
        if ($this->_truncate) {
            $this->Table->deleteAll([]);
        }

        // Console IO used by the Save Strategies
        $this->io = $io;

        // $conn = \Cake\Datasource\ConnectionManager::get('default');
        // $conn->logQueries(true);

        // Insert the data in the db.
        $data = $this->getData();
        try {
            $this->save($data);
            // old fashion way (can be useful for debugging)
            // return $this->saveOneByOne();
            $endTime = time();
            $dtF = new DateTime("@$startTime");
            $dtT = new DateTime("@$endTime");
            $diff = $dtF->diff($dtT)->format('%im %ss');
            $io->out('Data for entity "' . $this->entityName . '" inserted (' . count($data) . ') in ' . $diff);
        } catch (Exception $e) {
            $io->err(sprintf('Data for %s cannot be imported', $this->entityName));
            $io->warning($e->getMessage());

            return static::CODE_ERROR;
        }

        return static::CODE_SUCCESS;
    }

    /**
     * Display the progress bar
     *
     * @param int $total Number total of tasks
     * @return \Cake\Command\Helper\ProgressHelper
     */
    public function displayProgressBar(int $total): ?ProgressHelper
    {
        $progress = null;

        if ($total > 1) {
            /** @var \Cake\Command\Helper\ProgressHelper $progress */
            $progress = $this->io->helper('Progress');
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
     * @param array $data The data to save
     * @return void
     */
    public function save(array $data = []): void
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

    /**
     * @return array
     */
    abstract public function getData(): array;
}
