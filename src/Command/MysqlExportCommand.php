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
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

class MysqlExportCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Utility to export mysql database backups.'))
            ->addOption('datasource', [
                'short' => 'd',
                'default' => 'default',
                'help' => __('Datasource name'),
            ])
            ->addOption('clear-previous', [
                'boolean' => true,
                'default' => false,
                'help' => __('Clear previous backups.'),
            ])
            ->addOption('force', [
                'boolean' => true,
                'default' => false,
                'help' => __('Override if file already exist.'),
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
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Check if the data source is in the config
        $datasource = $args->getOption('datasource');
        $connection = ConnectionManager::get($datasource);

        // Check the database support
        $config = $connection->config();
        if ($config['driver'] !== 'Cake\Database\Driver\Mysql') {
            $this->error('Sorry only mySQL is supported at the moment', $io);

            return $this->errorCode();
        }

        // Define location and dump the table and data
        $dir = $this->getDir($args, $io);
        if (empty($dir)) {
            return $this->errorCode();
        }
        $file = $this->getFile($dir, $args, $io);

        if (empty($file)) {
            return $this->errorCode();
        }
        $status = $this->mysqlDump($config, $dir, $file, $io);
        if (!$status) {
            $this->error('Something went wrong!', $io);

            return $this->errorCode();
        }

        if ($args->getOption('clear-previous')) {
            $this->clearPrevious($dir, $file, $io);
        }

        $this->success(__('Success: the database was saved on file!'), $io);

        return $this->successCode();
    }

    /**
     * Perform a mysqldump command
     *
     * @param array $config connection manager config
     * @param string $dir directory path
     * @param string $file file name
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return bool
     */
    protected function mysqlDump($config, $dir, $file, ConsoleIo $io): bool
    {
        // Build the dump command.
        $cmd = 'mysqldump -h' . escapeshellarg($config['host']) . ' -u' . escapeshellarg($config['username']);
        if (!empty($config['password'])) {
            $cmd .= ' -p' . escapeshellarg($config['password']);
        }
        $cmd .= ' ' . escapeshellarg($config['database']) . ' > ' . $dir . $file;
        $io->out('Saving backup file: ' . $dir . $file);
        exec($cmd, $output, $status);
        if ($status !== $this->successCode()) {
            $io->quiet(__('There was an error running mysqldump.'));
            $userName = $config['username'];
            $dbName = $config['database'];
            $io->error(__('Please ensure that the user has PROCESS privileges.'));
            $io->error(__('Database ') . $dbName);
            $io->error(__('User ') . $userName);

            return false;
        }

        return $status === $this->successCode();
    }

    /**
     * Clear any previously saved backup file
     *
     * @param string $dir directory
     * @param string $newFile name, to avoid deleting it
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function clearPrevious($dir, $newFile, ConsoleIo $io): void
    {
        $files = glob($dir . '*');
        foreach ($files as $file) {
            if (is_file($file) && $file !== $dir . $newFile) {
                if (strpos($file, 'empty') !== false) {
                    continue;
                }
                unlink($file);
                $io->out('Deleting previous backup: ' . $file);
            }
        }
    }

    /**
     * Get the file where to import the backup from
     *
     * @param string $dir directory
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return ?string
     */
    protected function getFile($dir, Arguments $args, ConsoleIo $io): ?string
    {
        $file = $args->getOption('file');
        if (empty($file)) {
            $file = 'backup_' . time() . '.sql';
        }
        if (file_exists($dir . $file)) {
            $force = $args->getOption('force');
            if (!$force) {
                $this->error(__('The backup file already exist: ' . $dir . $file), $io);

                return null;
            }
        }

        return $file;
    }

    /**
     * Get the directory where to import the backup from
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return ?string or null if not exist
     */
    protected function getDir(Arguments $args, ConsoleIo $io): ?string
    {
        $dir = $args->getOption('dir');
        if (empty($dir)) {
            $dir = CACHE . 'database' . DS;
            if (!file_exists($dir)) {
                mkdir($dir);
            }
        }
        if (!file_exists($dir)) {
            $this->error(__('Could not access the backup directory: ' . $dir), $io);

            return null;
        }
        if (substr($dir, -1) !== DS) {
            $dir .= DS;
        }

        return $dir;
    }
}
