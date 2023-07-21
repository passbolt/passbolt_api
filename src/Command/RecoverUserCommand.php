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
 * @since         4.0.0
 */
namespace App\Command;

use App\Error\Exception\ValidationException;
use App\Model\Entity\AuthenticationToken;
use App\Model\Entity\User;
use App\Model\Validation\EmailValidationRule;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Routing\Router;

/**
 * RecoverUserCommand class
 */
class RecoverUserCommand extends PassboltCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser
            ->setDescription(__('Get an existing account recovery token, or create a new one.'))
            ->addOption('username', [
                'short' => 'u',
                'required' => true,
                'help' => __('The user name (email).'),
            ])
            ->addOption('create', [
                'short' => 'c',
                'boolean' => true,
                'help' => __('Create a new token.'),
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
        $this->assertCurrentProcessUser($io);

        $username = $args->getOption('username');
        if (!EmailValidationRule::check($username)) {
            $io->error('The username should be a valid email.');
            $this->abort();
        }

        /** @var \App\Model\Entity\User|null $user */
        $user = $this->fetchTable('Users')
            ->find()
            ->where(compact('username'))
            ->first();
        if (is_null($user)) {
            $io->error("The user {$username} could not be found.");
            $this->abort();
        }
        if (!$user->active) {
            $io->error("The user {$username} is not active.");
            $this->abort();
        }

        if ($args->getOption('create')) {
            $token = $this->createTokenOrAbort($user, $io);
            $io->success('The recovery token has been created.');
        } else {
            $token = $this->fetchExistingActiveTokenOrAbort($user, $io);
        }

        $recoverUrl = Router::url('/setup/recover/start/' . $user['id'] . '/' . $token['token'], true);

        $io->success("The user {$username} can recover its account here:");
        $io->success($recoverUrl);

        return $this->successCode();
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \Cake\Console\ConsoleIo $io Console
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function createTokenOrAbort(User $user, ConsoleIo $io): AuthenticationToken
    {
        /** @var \App\Model\Table\AuthenticationTokensTable $AuthenticationTokens */
        $AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
        try {
            return $AuthenticationTokens->generate($user->id, AuthenticationToken::TYPE_RECOVER);
        } catch (ValidationException $exception) {
            $io->error($exception->getMessage());
            $this->abort();
        }
    }

    /**
     * @param \App\Model\Entity\User $user User
     * @param \Cake\Console\ConsoleIo $io Console
     * @return \App\Model\Entity\AuthenticationToken
     */
    protected function fetchExistingActiveTokenOrAbort(User $user, ConsoleIo $io): AuthenticationToken
    {
        /** @var \App\Model\Entity\AuthenticationToken|null $token */
        $token = $this
            ->fetchTable('AuthenticationTokens')
            ->find()
            ->where([
                'user_id' => $user->get('id'),
                'active' => true,
                'type' => AuthenticationToken::TYPE_RECOVER,
            ])->first();
        if (is_null($token)) {
            $io->error("An active recovery token could not be found for the user {$user->username}.");
            $io->info('You may create one using the option --create.');
            $this->abort();
        }

        return $token;
    }
}
