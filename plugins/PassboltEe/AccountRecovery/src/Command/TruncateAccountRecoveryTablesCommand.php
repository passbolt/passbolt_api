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
 * @since         4.3.0
 */
namespace Passbolt\AccountRecovery\Command;

use App\Command\PassboltCommand;
use App\Model\Validation\EmailValidationRule;
use App\Model\Validation\Fingerprint\IsValidFingerprintValidationRule;
use App\Service\Command\ProcessUserService;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Datasource\ConnectionManager;

/**
 * TruncateAccountRecoveryTablesCommand class
 */
class TruncateAccountRecoveryTablesCommand extends PassboltCommand
{
    public const ACCOUNT_RECOVERY_TABLES_TO_TRUNCATE = [
        'account_recovery_organization_policies',
        'account_recovery_organization_public_keys',
        'account_recovery_private_key_passwords',
        'account_recovery_private_keys',
        'account_recovery_requests',
        'account_recovery_responses',
        'account_recovery_user_settings',
    ];

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
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription([
                'Truncate all the account recovery tables.',
                'This will delete all account recovery requests and settings.',
            ])
            ->addOption('username', [
                'short' => 'u',
                'required' => false,
                'help' => 'The user name (email)',
            ])
            ->addOption('fingerprint', [
                'short' => 'f',
                'required' => false,
                'help' => 'Organization public key\'s fingerprint',
            ])
            ->addOption('no-verify', [
                'short' => 'n',
                'required' => false,
                'boolean' => true,
                'help' => 'Skip email and fingerprint checks',
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

        $isInteractiveModeOn = !$args->getOption('no-verify');
        if ($isInteractiveModeOn) {
            $this->interactWithOperator($args, $io);
        }

        $this->truncateTables($io);

        return $this->successCode();
    }

    /**
     * @param string $choice 'y' or 'n'
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    protected function abortIfNoContinue(string $choice, ConsoleIo $io): void
    {
        if ($choice !== 'y') {
            $io->info('Aborting...');
            $this->abort();
        }
    }

    /**
     * @param \Cake\Console\Arguments $args Args
     * @param \Cake\Console\ConsoleIo $io $io
     * @return void
     */
    protected function interactWithOperator(Arguments $args, ConsoleIo $io): void
    {
        $this->processUsername($args, $io);
        $this->processFingerprint($args, $io);

        $io->info('The following tables will be truncated:');
        $io->info(self::ACCOUNT_RECOVERY_TABLES_TO_TRUNCATE);

        $continue = $io->askChoice('Continue anyway?', ['y', 'n'], 'n');
        $this->abortIfNoContinue($continue, $io);
    }

    /**
     * @param \Cake\Console\Arguments $args Args
     * @param \Cake\Console\ConsoleIo $io $io
     * @return void
     */
    protected function processUsername(Arguments $args, ConsoleIo $io): void
    {
        $username = $args->getOption('username');

        if (is_null($username)) {
            $continue = $io->askChoice('No admin username was provided. Continue anyway?', ['y', 'n'], 'n');
            $this->abortIfNoContinue($continue, $io);

            return;
        }

        if (!EmailValidationRule::check($username)) {
            $io->error('The username should be a valid email.');
            $this->abort();
        }

        /** @var \App\Model\Entity\User|null $admin */
        $admin = $this->fetchTable('Users')
            ->find('admins')
            ->where(compact('username'))
            ->first();

        if (is_null($admin)) {
            $continue = $io->askChoice('The admin could not be found. Continue anyway?', ['y', 'n'], 'n');
            $this->abortIfNoContinue($continue, $io);
        }

        $io->success('The admin was successfully found.');
    }

    /**
     * @param \Cake\Console\Arguments $args Args
     * @param \Cake\Console\ConsoleIo $io $io
     * @return void
     */
    protected function processFingerprint(Arguments $args, ConsoleIo $io): void
    {
        $fingerprint = $args->getOption('fingerprint');

        if (is_null($fingerprint)) {
            $continue = $io->askChoice('No fingerprint was provided. Continue anyway?', ['y', 'n'], 'n');
            $this->abortIfNoContinue($continue, $io);

            return;
        }

        $fingerprintValidationRule = (new IsValidFingerprintValidationRule());
        if (!$fingerprintValidationRule->rule($fingerprint, null)) {
            $io->error($fingerprintValidationRule->defaultErrorMessage($fingerprint, null));
            $this->abort();
        }

        /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|null $fingerprint */
        $fingerprint = $this->fetchTable('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys')
            ->find()
            ->where(compact('fingerprint'))
            ->first();

        if (is_null($fingerprint)) {
            $continue = $io->askChoice(
                'The fingerprint could not be found in account_recovery_organization_public_keys table. '
                . 'Continue anyway?',
                ['y', 'n'],
                'n'
            );
            $this->abortIfNoContinue($continue, $io);
        }

        $io->success('The fingerprint was successfully found.');
    }

    /**
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return void
     */
    protected function truncateTables(ConsoleIo $io): void
    {
        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        foreach (self::ACCOUNT_RECOVERY_TABLES_TO_TRUNCATE as $table) {
            try {
                $connection->delete($table);
            } catch (\Throwable $e) {
                $io->error($e->getMessage());
                $this->abort();
            }
            $io->success("All entries in $table table were deleted.");
        }
    }
}
