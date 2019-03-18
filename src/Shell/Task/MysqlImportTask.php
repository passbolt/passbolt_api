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
namespace App\Shell\Task;

use App\Shell\AppShell;
use Cake\Datasource\ConnectionManager;

class MysqlImportTask extends AppShell
{

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__('Utility to import a mysql database backups.'))
            ->addOption('datasource', [
                'short' => 'd',
                'default' => 'default',
                'help' => __('Datasource name')
            ])
            ->addOption('file', [
                'help' => 'The file to import the schema and data from',
                'default' => null,
            ])
            ->addOption('dir', [
                'help' => 'The directory to import the schema and data from',
                'default' => null,
            ]);

        return $parser;
    }

    /**
     * Import
     *
     * @return bool
     */
    public function main()
    {
        // Get the file and directory name and read from it
        $dir = $this->_getDir();
        if (empty($dir)) {
            return false;
        }
        $file = $this->_getFile($dir);
        if (empty($file) || $file === 'empty') {
            return false;
        }
        $this->out('Loading backup file: ' . $dir . $file);
        $sql = file_get_contents($dir . $file, FILE_USE_INCLUDE_PATH);

        // try to run the sql backup
        try {
            $datasource = $this->param('datasource');
            $connection = ConnectionManager::get($datasource);
            $connection->query($sql);
        } catch (Exception $e) {
            $this->_error('Error: Something went wrong when importing the SQL file');

            return false;
        }

        $this->_success('Success: SQL file imported');

        return true;
    }

    /**
     * Get the directory where to import the backup from
     *
     * @return string
     */
    protected function _getDir()
    {
        $this->param('dir');
        if (isset($dir) && !file_exists($dir)) {
            $this->_error('Error: the directory does not exist' . $dir);

            return null;
        } else {
            $dir = CACHE . 'database' . DS;
        }

        return $dir;
    }

    /**
     * Get the file where to import the backup from
     *
     * @param string $dir directory
     * @return string|null
     */
    protected function _getFile($dir)
    {
        $file = $this->param('file');
        if (isset($file) && !file_exists($file)) {
            $this->_error('Error: could not find the SQL file ' . $dir . $file);

            return null;
        } else {
            $files = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), ['..', '.']);
            if (!isset($files[1])) {
                $this->_error('Error: no existing backup found in ' . $dir);

                return null;
            }
            $file = $files[1];
        }

        return $file;
    }
}
