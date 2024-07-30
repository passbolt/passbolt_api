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
 * @since         3.1.0
 */
namespace App\Command;

use App\Service\Avatars\AvatarsTransferService;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * @deprecated to be removed in v5.0
 */
class AvatarTransferCommand extends PassboltCommand
{
    use DatabaseAwareCommandTrait;

    public const SOURCE_TABLE = 'file_storage';

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Diagnose errors in the transfer of avatars.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $this->addDatasourceOption($parser);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $datasource = $args->getOption('datasource');

        $listTables = ConnectionManager::get($datasource)->getSchemaCollection()->listTables();
        if (!in_array(self::SOURCE_TABLE, $listTables)) {
            $io->error('The table ' . self::SOURCE_TABLE . ' could not be found.');
            $this->abort();
        }

        /** @var \App\Model\Table\AvatarsTable $AvatarsTable */
        $AvatarsTable = TableRegistry::getTableLocator()->get('Avatars');
        $FileStorageTable = TableRegistry::getTableLocator()
            ->get('FileStorage')
            ->setTable('file_storage');

        /** @phpstan-ignore-next-line */
        $AvatarsTable->setConnection(ConnectionManager::get($datasource));
        /** @phpstan-ignore-next-line */
        $FileStorageTable->setConnection(ConnectionManager::get($datasource));

        $results = (new AvatarsTransferService($AvatarsTable, $FileStorageTable, true))->transfer();

        foreach ($results['error'] as $msg) {
            $io->error($msg);
        }

        foreach ($results['success'] as $msg) {
            $io->success($msg);
        }

        $nErrors = count($results['error']);
        $nSuccess = count($results['success']);
        if ($nErrors > 0) {
            $io->error("{$nErrors} error(s) found.");
        } else {
            $io->success('No error found.');
        }
        $io->info("{$nSuccess} files successfully transferred.");

        return $this->successCode();
    }
}
