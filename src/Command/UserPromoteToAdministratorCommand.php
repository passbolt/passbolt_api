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
 * @since         5.7.0
 */
namespace App\Command;

use App\Model\Entity\Role;
use App\Model\Table\UsersTable;
use App\Service\Command\ProcessUserService;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UserAccessControl;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

/**
 * UserPromoteToAdministratorCommand class
 */
class UserPromoteToAdministratorCommand extends PassboltCommand
{
    use FeaturePluginAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $UsersTable;

    /**
     * @var \App\Service\Command\ProcessUserService
     */
    protected ProcessUserService $processUserService;

    /**
     * @param \App\Service\Command\ProcessUserService $processUserService Process user service.
     */
    public function __construct(ProcessUserService $processUserService)
    {
        parent::__construct();

        $this->processUserService = $processUserService;
    }

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

        $this->UsersTable = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Promote a user to administrator.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('user-username', [
                'short' => 'u',
                'help' => __('The user to promote'),
                'required' => true,
            ])
            ->addOption('admin-username', [
                'short' => 'a',
                'help' => __('The administrator to impersonate'),
                'required' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        // Root user is not allowed to execute this command.
        $this->assertCurrentProcessUser($io, $this->processUserService);

        // Ensure the admin we are going to impersonate exists in the DB and is really an admin
        $providedAdminUsername = $args->getOption('admin-username');

        // $adminUser = $usersTable->find()->where(['username' => $providedAdminUsername])->first();
        $adminUser = $this->UsersTable->findByUsername($providedAdminUsername)->first();

        if (!$adminUser) {
            $io->out(__('No administrator matching the username "{0}" was found.', $providedAdminUsername));

            return $this->errorCode();
        }

        if ($adminUser->role->name !== ROLE::ADMIN) {
            $io->out(__(
                'The user with username {0} doesn\'t have administrator priviledges',
                $adminUser->username
            ));

            return $this->errorCode();
        }

        // Ensure the user we are going to promote exists in the DB
        $providedUserUsername = $args->getOption('user-username');
        $userToPromote = $this->UsersTable->findByUsername($providedUserUsername)->first();

        if (!$userToPromote) {
            $io->out(__('No user matching the username "{0}" was found.', $providedUserUsername));

            return $this->errorCode();
        }

        // We can reuse the role_id we got from the admin user entity as reference value
        $adminRoleId = $adminUser->role->id;
        $uac = new UserAccessControl(Role::ADMIN, $adminUser->id);
        $data = [ 'role_id' => $adminRoleId];
        $userToPromote = $this->UsersTable->editEntity($userToPromote, $data, $uac);
        $this->UsersTable->save($userToPromote);

        // Now ensure the user has been promoted
        $userToPromote = $this->UsersTable->findByUsername($providedUserUsername)->first();
        if ($userToPromote->role->name !== ROLE::ADMIN) {
            $io->out(__('Warning: the user has NOT been promoted!'));

            return $this->errorCode();
        }

        $io->out(__(
            'The user identified by "{0}" has been promoted to the administrator user role',
            $providedUserUsername
        ));

        return $this->successCode();
    }
}
