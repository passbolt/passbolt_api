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
namespace Passbolt\MultiFactorAuthentication\Command;

use App\Command\PassboltCommand;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Model\Table\AvatarsTable;
use App\Model\Table\UsersTable;
use App\Model\Validation\EmailValidationRule;
use App\Service\Command\ProcessUserService;
use App\Utility\Application\FeaturePluginAwareTrait;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\Exception\RecordNotFoundException;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;
use Passbolt\MultiFactorAuthentication\Service\MfaUserSettings\MfaUserSettingsDeleteService;
use Passbolt\MultiFactorAuthentication\Service\Query\IsMfaEnabledQueryService;

/**
 * MfaUserSettingsDisableCommand class
 */
class MfaUserSettingsDisableCommand extends PassboltCommand
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
        return __('Disable MFA for a user.');
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
                'help' => __('The user to whom disable the MFA settings'),
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

        // Root user is not allowed to execute this command
        $this->assertCurrentProcessUser($io, $this->processUserService);

        // Ensure the user we are going to promote exists in the DB
        $providedUsername = $args->getOption('user-username');

        // Validate admin email
        if (!EmailValidationRule::check($providedUsername)) {
            $this->error(__('The username must be a valid email address.', $providedUsername), $io);
            $this->abort();
        }

        try {
            $userToUpdate = $this->getUserWithLocaleAndMfaIsEnabledInfo($providedUsername);
        } catch (RecordNotFoundException) {
            $io->out(__('No user matching the username "{0}" was found.', $providedUsername));

            return $this->errorCode();
        }

        // Now we ensure that the user has its MFA enabled
        // (beware that if the organization has MFA disabled the service method will return true)
        $isMfaEnabledService = new IsMfaEnabledService();
        if (!$isMfaEnabledService->isEnabledForUser($userToUpdate)) {
            $io->out(__(
                'The user "{0}" has MFA disabled already.
                The MFA is disabled either at the user level or for the whole organization.',
                $providedUsername
            ));

            return $this->errorCode();
        }

        // Disable the MFA
        $mfaUserSettingsDisableService = new MfaUserSettingsDeleteService();
        if (
            !$mfaUserSettingsDisableService->disableUserSettings(
                $userToUpdate,
                new UserAccessControl($userToUpdate->role->name, $userToUpdate->id, $userToUpdate->username)
            )
        ) {
            $io->out(__('An error happened while disabling the MFA for user "{0}".', $providedUsername));

            return $this->errorCode();
        }

        // Now we ensure that the user has its MFA disabled
        $userToUpdate = $this->getUserWithLocaleAndMfaIsEnabledInfo($providedUsername);
        if ($isMfaEnabledService->isEnabledForUser($userToUpdate)) {
            $io->out(__('An unknown error hapened. The MFA for user "{0}" is still ACTIVE.', $providedUsername));

            return $this->errorCode();
        }

        $io->out(__('The MFA for user "{0}" has been disabled.', $providedUsername));

        return $this->successCode();
    }

    /**
     * @param string $username
     * @return \App\Model\Entity\User
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When there is no metadata key record.
     */
    private function getUserWithLocaleAndMfaIsEnabledInfo(string $username): User
    {
        $findUserQuery = $this->UsersTable->find()
            ->select(['Users.role_id','Users.username', 'Roles.name'])
            ->contain(['MfaSettings', 'Roles'])
            ->contain(['Profiles' => AvatarsTable::addContainAvatar()])
            ->where([ 'Users.username' => $username])->find('locale');

        $mfaQ = (new IsMfaEnabledQueryService());
        $simulatedUuid = UuidFactory::uuid(); // need the uuid to be set because decorateForView will verify it
        $mfaQ->decorateForView(
            $findUserQuery,
            new UserAccessControl(ROLE::ADMIN, $simulatedUuid),
            $simulatedUuid
        );

        return $findUserQuery->firstOrFail();
    }
}
