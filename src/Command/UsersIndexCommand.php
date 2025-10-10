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
use App\Utility\UuidFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\ORM\TableRegistry;
use Passbolt\MultiFactorAuthentication\Service\Query\IsMfaEnabledQueryService;

/**
 * UsersIndexCommand class
 */
class UsersIndexCommand extends PassboltCommand
{
    use FeaturePluginAwareTrait;

    /**
     * @var \App\Model\Table\UsersTable
     */
    protected UsersTable $Users;

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

        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

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

        /** @var \App\Model\Table\RolesTable $rolesTable */
        $rolesTable = $this->fetchTable('Roles');

        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');

        $query = $usersTable->findIndex(Role::ADMIN)
            // overwrite the 'where' clause to avoid findIndex to exclude the deleted users
            ->where([
                'Users.role_id <>' => $rolesTable->getIdByName(Role::GUEST),
                ], [], true)
            ->orderBy([ 'Roles.name' => 'ASC', 'Profiles.last_name' => 'ASC', 'Profiles.first_name' => 'ASC']);

        if ($this->isFeaturePluginEnabled('MultiFactorAuthentication')) {
            $mfaQ = (new IsMfaEnabledQueryService());
            // need the uuid to be set because IsMfaEnabledQueryService->decorateForView will check for it existance
            $simulatedUuid = UuidFactory::uuid();
            $mfaQ->decorateForView(
                $query,
                new UserAccessControl(ROLE::ADMIN, $simulatedUuid),
                $simulatedUuid
            );
        }

        $users = $query->toArray();

        if (count($users) === 0) {
            $io->out(__('This organization has no registered users.'));

            return $this->successCode();
        }

        // Prepares the columns headers labels for a nice tabular view with
        // the column adapted to the size of the fields content
        $headers = ['Role', 'Username', 'Last name', 'First name',
                    'Created At', 'Active', 'Disabled', 'Deleted', 'MFA enabled'];
        $col_widths = array_combine(
            $headers,
            array_map('strlen', $headers)
        );

        // In order to resize the columns to fit the maximum size we have to navigate the resultset
        // only the string values are affected by varying size changes
        foreach ($users as $user) {
            $col_widths[$headers[0]] = max($col_widths[$headers[0]], strlen((string)($user->role->name ?? '')));
            $col_widths[$headers[1]] = max($col_widths[$headers[1]], strlen((string)$user->username));
            $col_widths[$headers[2]] = max($col_widths[$headers[2]], strlen((string)($user->profile->last_name ?? '')));
            $col_widths[$headers[3]] = max(
                $col_widths[$headers[3]],
                strlen((string)($user->profile->first_name ?? ''))
            );
        }

        // Generate dynmically the sprintf sized statment
        $format = sprintf(
            '| %%-%ds | %%-%ds | %%-%ds | %%-%ds | %%-%ds | %%-%ds | %%-%ds | %%-%ds | %%-%ds |',
            $col_widths[$headers[0]],
            $col_widths[$headers[1]],
            $col_widths[$headers[2]],
            $col_widths[$headers[3]],
            $col_widths[$headers[4]],
            $col_widths[$headers[5]],
            $col_widths[$headers[6]],
            $col_widths[$headers[7]],
            $col_widths[$headers[8]]
        );

        // Generate a nice ASCII line separator
        $parts = [];
        foreach ($headers as $header) {
            $parts[] = str_repeat('-', $col_widths[$header] + 2); // +2 pour les espaces autour du texte
        }
        $hr = '+' . implode('+', $parts) . '+';

        // And now display the table
        $io->out($hr);
        $io->out(vsprintf($format, $headers));
        $io->out($hr);
        foreach ($users as $user) {
            $io->out(vsprintf($format, [
                $user->role ? $user->role->name : '',
                $user->username,
                $user->profile->last_name ?? '',
                $user->profile->first_name ?? '',
                $user->created?->format('Y-m-d') ?? '',
                $user->active ? 'yes' : 'no',
                $user->disabled ? 'yes' : 'no',
                $user->deleted ? 'yes' : 'no',
                $user->is_mfa_enabled ? 'yes' : 'no',
            ]));
        }

        $io->out($hr);

        return $this->successCode();
    }
}
