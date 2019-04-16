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

class MysqlExportTask extends AppShell
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
            ->setDescription(__('Utility to export mysql database backups.'))
            ->addOption('datasource', [
                'short' => 'd',
                'default' => 'default',
                'help' => __('Datasource name')
            ])
            ->addOption('clear-previous', [
                'boolean' => true,
                'default' => false,
                'help' => __('Clear previous backups.')
            ])
            ->addOption('force', [
                'boolean' => true,
                'default' => false,
                'help' => __('Override if file already exist.')
            ])
            ->addOption('file', [
                'help' => 'The file to export the schema and data to.',
                'default' => null,
            ])
            ->addOption('dir', [
                'help' => 'The directory to export the schema and data to.',
                'default' => null,
            ]);

        return $parser;
    }

    /**
     * Export
     *
     * @return bool
     */
    public function main()
    {
        // Check if the data source is in the config
        $datasource = $this->param('datasource');
        $connection = ConnectionManager::get($datasource);

        // Check the database support
        $config = $connection->config();
        if ($config['driver'] !== 'Cake\Database\Driver\Mysql') {
            $this->_error('Sorry only mySQL is supported at the moment');

            return false;
        }

        // Define location and dump the table and data
        $dir = $this->_getDir();
        if (empty($dir)) {
            return false;
        }
        $file = $this->_getFile($dir);
        if (empty($file)) {
            return false;
        }
        $status = $this->_mysqlDump($config, $dir, $file);
        if (!$status) {
            $this->_error('Something went wrong!');

            return false;
        }

        if ($this->param('clear-previous')) {
            $this->_clearPrevious($dir, $file);
        }

        $this->_success(__('Success: the database was saved on file!'));

        return true;
    }

    /**
     * Perform a mysqldump command
     *
     * @param array $config connection manager config
     * @param string $dir directory path
     * @param string $file file name
     * @return bool
     */
    protected function _mysqlDump($config, $dir, $file)
    {
        // Build the dump command.
        $cmd = 'mysqldump -h' . escapeshellarg($config['host']) . ' -u' . escapeshellarg($config['username']);
        if (!empty($config['password'])) {
            $cmd .= ' -p' . escapeshellarg($config['password']);
        }
        $cmd .= ' ' . escapeshellarg($config['database']) . ' > ' . $dir . $file;
        $this->out('Saving backup file: ' . $dir . $file);
        exec($cmd, $output, $status);

        return ($status === self::CODE_SUCCESS);
    }

    /**
     * Clear any previously saved backup file
     *
     * @param string $dir directory
     * @param string $newFile name, to avoid deleting it
     * @return void
     */
    protected function _clearPrevious($dir, $newFile)
    {
        $files = glob($dir . '*');
        foreach ($files as $file) {
            if (is_file($file) && $file !== ($dir . $newFile)) {
                if (strpos($file, 'empty') !== false) {
                    continue;
                }
                unlink($file);
                $this->out('Deleting previous backup: ' . $file);
            }
        }
    }

    /**
     * Get the file where to import the backup from
     *
     * @param string $dir directory
     * @return string
     */
    protected function _getFile($dir)
    {
        $file = $this->param('file');
        if (empty($file)) {
            $file = 'backup_' . time() . '.sql';
        }
        if (file_exists($dir . $file)) {
            $force = $this->param('force');
            if (!$force) {
                $this->_error(__('The backup file already exist: ' . $dir . $file));

                return null;
            }
        }

        return $file;
    }

    /**
     * Get the directory where to import the backup from
     *
     * @return string or null if not exist
     */
    protected function _getDir()
    {
        $dir = $this->param('dir');
        if (empty($dir)) {
            $dir = CACHE . 'database' . DS;
        }
        if (!file_exists($dir)) {
            $this->_error(__('Could not access the backup directory: ' . $dir));

            return null;
        };

        return $dir;
    }
}
