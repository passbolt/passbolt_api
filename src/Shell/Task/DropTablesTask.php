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
namespace App\Shell\Task;

use App\Shell\AppShell;
use Cake\Datasource\ConnectionManager;

class DropTablesTask extends AppShell
{
    /**
     * @inheritDoc
     */
    public function getOptionParser(): \Cake\Console\ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__('Drop all the tables. Dangerous but useful for a full reinstall.'))
            ->addOption('datasource', [
                'short' => 'd',
                'default' => 'default',
                'help' => __('Datasource name'),
            ]);

        return $parser;
    }

    /**
     * Main drop tables task
     *
     * @return bool
     */
    public function main()
    {
        $datasource = $this->param('datasource');
        $connection = ConnectionManager::get($datasource);
        $tables = $connection->execute('show tables');
        $tables = $tables->fetchAll();
        foreach ($tables as $table) {
            $this->out(__('Dropping table ' . $table[0]));
            $connection->query('drop table ' . $table[0]);
        }
        $this->_success(__('{0} tables dropped', count($tables)));

        return true;
    }
}
