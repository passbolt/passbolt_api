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
 * @since         3.5.0
 */
namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Database\Connection;
use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;

class MigratePostgresCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;

    public const POSTGRES_RELEVANT_MIGRATIONS = [
        'V340MigrateASCIIFieldsEncoding',
        'V340MigrateASCIIFieldsEncodingPro',
        'V350ConvertIdFieldsToUuidFields',
    ];

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Re-runs the migrations required by Postgres.'));

        $this->addDatasourceOption($parser);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Root user is not allowed to execute this command.
        // This command needs to be executed with the same user as the webserver.
        if (!$this->assertNotRoot($io)) {
            return $this->errorCode();
        }

        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get($args->getOption('datasource'));
        if (!($connection->getDriver() instanceof Postgres)) {
            $this->error('This command is available with a Postgres connection only.', $io);
            $this->abort();
        }

        $this->deletePostgresRelevantMigrations($connection);

        $this->runMigrationsMigrateCommand($args, $io);

        $io->info('Passbolt can now be used with Postgres.');

        return $this->successCode();
    }

    /**
     * Marks as non-migrated the Postgres relevant migrations.
     *
     * @param \Cake\Database\Connection $connection Connection
     * @return void
     */
    public function deletePostgresRelevantMigrations(Connection $connection): void
    {
        $connection->newQuery()
            ->delete('phinxlog')
            ->where(['phinxlog.migration_name IN' => self::POSTGRES_RELEVANT_MIGRATIONS,])
            ->execute();
    }
}
