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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Command;

use App\Command\PassboltCommand;
use App\Model\Entity\User;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtTokenCreateService;

/**
 * Class JwtCreateTokenCommand
 */
class CreateAccessTokenCommand extends PassboltCommand
{
    /**
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        parent::initialize();

        $this->Users = $this->fetchTable('Users');
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Create a JSON Web Token.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser
            ->addOption('username', [
                'help' => 'The username to create a user for.',
                'short' => 'u',
            ])
            ->addOption('user-id', [
                'help' => 'The user ID to create a user for.',
                'short' => 'i',
            ])
            ->addOption('expiry', [
                'help' => 'The token\'s validity time in minutes, or any time expressed in words.',
                'default' => Configure::read(JwtTokenCreateService::JWT_EXPIRY_CONFIG_KEY),
                'short' => 'e',
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        parent::execute($args, $io);

        $this->abortIfNotInDebugMode($io);

        $expiry = $this->getExpiry($args);
        $user = null;
        try {
            $user = $this->getUserId($args, $io);
        } catch (\Throwable $e) {
            $io->abort($e->getMessage());
        }
        $token = (new JwtTokenCreateService())->createToken($user->id, $expiry);
        $io->out("Access token for {$user->username} valid {$expiry}:");
        $io->hr();
        $io->success($token);
        $io->hr();

        return $this->successCode();
    }

    /**
     * Fetch the user.
     *
     * @param \Cake\Console\Arguments $args Arguments passed.
     * @param \Cake\Console\ConsoleIo $io Console IO
     * @return \App\Model\Entity\User
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when there is no record.
     * * @throws \Cake\Console\Exception\StopException when options are missing.
     */
    protected function getUserId(Arguments $args, ConsoleIo $io): User
    {
        $query = $this->Users->findActive();

        if ($args->hasOption('user-id')) {
            $query->where([$this->Users->aliasField('id') => $args->getOption('user-id')]);
        } elseif ($args->hasOption('username')) {
            $query->where([$this->Users->aliasField('username') => $args->getOption('username')]);
        } else {
            $io->abort('Please specify a valid id or a username.');
        }

        /** @var \App\Model\Entity\User $user */
        $user = $query->firstOrFail();

        return $user;
    }

    /**
     * Fetch the expiry time in string format.
     *
     * @param \Cake\Console\Arguments $args Arguments passed.
     * @return string
     */
    protected function getExpiry(Arguments $args): string
    {
        /** @var string $expiry */
        $expiry = $args->getOption('expiry');
        if (is_numeric($expiry)) {
            $expiry .= ' minutes';
        }

        return $expiry;
    }
}
