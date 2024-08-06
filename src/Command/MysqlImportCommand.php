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

class MysqlImportCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Utility to import mysql database backups.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $this
            ->addDatasourceOption($parser, false)
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
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Get the file and directory name and read from it
        $dir = $this->getDir($args, $io);
        if (empty($dir)) {
            return $this->errorCode();
        }
        $file = $this->getFile($dir, $args, $io);
        if (empty($file) || $file === 'empty') {
            return $this->errorCode();
        }
        $io->out('Loading backup file: ' . $dir . $file);
        $sql = file_get_contents($dir . $file, true);

        // try to run the sql backup
        try {
            $datasource = $args->getOption('datasource');
            $connection = ConnectionManager::get($datasource);
            $connection->execute($sql);
        } catch (\Exception $e) {
            $this->error('Error: Something went wrong when importing the SQL file', $io);
            $this->error($e->getMessage(), $io);

            return $this->errorCode();
        }

        $this->success('Success: SQL file imported', $io);

        return $this->successCode();
    }

    /**
     * Get the directory where to import the backup from
     *
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return string|null
     */
    protected function getDir(Arguments $args, ConsoleIo $io): ?string
    {
        $dir = $args->getOption('dir');
        if (isset($dir) && is_string($dir)) {
            if (!file_exists($dir)) {
                $this->error('Error: the directory does not exist' . $dir, $io);

                return null;
            }

            if (substr($dir, -1) !== DS) {
                $dir .= DS;
            }
        } else {
            $dir = CACHE . 'database' . DS;
            if (!file_exists($dir)) {
                mkdir($dir);
            }
        }

        return $dir;
    }

    /**
     * Get the file where to import the backup from
     *
     * @param string $dir directory
     * @param \Cake\Console\Arguments $args Arguments
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return string|null
     */
    protected function getFile(string $dir, Arguments $args, ConsoleIo $io): ?string
    {
        $file = $args->getOption('file');
        if (isset($file) && is_string($file)) {
            if (!file_exists($dir . $file)) {
                $this->error('Error: could not find the SQL file ' . $file, $io);

                return null;
            }

            return $file;
        } else {
            $files = array_diff(scandir($dir, SCANDIR_SORT_DESCENDING), ['..', '.', 'empty']);
            if (count($files) === 0) {
                $this->error('Error: no existing backup found in ' . $dir, $io);

                return null;
            }

            return array_values($files)[0];
        }
    }
}
