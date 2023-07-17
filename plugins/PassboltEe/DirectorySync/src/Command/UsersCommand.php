<?php
declare(strict_types=1);

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
namespace Passbolt\DirectorySync\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Passbolt\DirectorySync\Actions\UserSyncAction;

/**
 * Class UsersCommand
 */
class UsersCommand extends DirectorySyncCommand
{
    use SyncCommandTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @var \App\Model\Table\RolesTable
     */
    protected $Roles;

    /**
     * @var \App\Model\Table\AuthenticationTokensTable
     */
    protected $AuthenticationTokens;

    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize(): void
    {
        parent::initialize();
        /** @phpstan-ignore-next-line */
        $this->Users = $this->fetchTable('Users');
        /** @phpstan-ignore-next-line */
        $this->Roles = $this->fetchTable('Roles');
        /** @phpstan-ignore-next-line */
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->setDescription(__('Sync users'))
            ->addOption('dry-run', [
                'help' => 'Don\'t save the changes',
                'default' => 'false',
                'boolean' => true,
            ])
            ->addOption('persist', [
                'help' => 'Persist data, otherwise it won\'t save the changes',
                'default' => false,
                'boolean' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        try {
            $dryRun = $args->getOption('dry-run') || !$args->getOption('persist');

            $action = new UserSyncAction();
            $action->setDryRun($dryRun);
            $reports = $action->execute();
            $this->displayReports($reports, 'Users', $io);
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(), $io);

            return $this->errorCode();
        }

        $this->warnPersist($args, $io);

        return $this->successCode();
    }
}
