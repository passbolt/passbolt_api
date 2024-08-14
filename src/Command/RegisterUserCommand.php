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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Service\Command\ProcessUserService;
use App\Utility\UserAccessControl;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettings;

/**
 * RegisterUserCommand class
 */
class RegisterUserCommand extends PassboltCommand
{
    /**
     * Number of interaction with the console.
     */
    public const DEFAULT_INTERACTIVE_LOOP = 3;

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
        $this->Roles = $this->fetchTable('Roles');
        $this->AuthenticationTokens = $this->fetchTable('AuthenticationTokens');
    }

    /**
     * @inheritDoc
     */
    public static function getCommandDescription(): string
    {
        return __('Register a new user.');
    }

    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);

        $parser
            ->addOption('interactive', [
                'short' => 'i',
                'boolean' => true,
                'help' => __('Enable interactive mode'),
            ])
            ->addOption('interactive-loop', [
                'default' => self::DEFAULT_INTERACTIVE_LOOP,
                'help' => __('Enable interactive mode'),
            ])
            ->addOption('username', [
                'short' => 'u',
                'help' => __('The user email aka username'),
            ])
            ->addOption('first-name', [
                'short' => 'f',
                'help' => __('The user first name'),
            ])
            ->addOption('last-name', [
                'short' => 'l',
                'help' => __('The user last name'),
            ])
            ->addOption('role', [
                'short' => 'r',
                'help' => __('The User role, such as "admin" or "user"'),
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

        // Who is creating the user?
        // use the oldest admin or temporary non existing one
        $admin = $this->Users->findFirstAdmin();
        if (!is_null($admin)) {
            $accessControl = new UserAccessControl(Role::ADMIN, $admin->id);
        } else {
            // Act as an admin but without a user set
            $accessControl = new UserAccessControl(Role::ADMIN);
        }

        $attempt = 0;
        $user = null;

        if ($args->getOption('interactive')) {
            $maxAttempt = $args->getOption('interactive-loop');
        } else {
            $maxAttempt = 1;
        }
        while (($attempt < $maxAttempt)) {
            $attempt++;
            $data = $this->askUserData($args, $io);
            try {
                $user = $this->Users->register($data, $accessControl);
                break;
            } catch (ValidationException $exception) {
                $io->out(__('Validation failed for the following user data:'));
                $this->displayValidationError($exception->getErrors(), $io);
            } catch (InternalErrorException $exception) {
                $io->out(__('Something went wrong when trying to save the user, please try again.'));
            }
        }

        if (!isset($user)) {
            $this->error(__('User registration failed.'), $io);

            return $this->errorCode();
        }

        $this->success(__('User saved successfully.'), $io);
        $this->notifyUser($user, $io);

        return $this->successCode();
    }

    /**
     * Display the entity validation errors
     *
     * @param array $errors validation errors
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function displayValidationError($errors, ConsoleIo $io): void
    {
        foreach ($errors as $fieldname => $error) {
            foreach ($error as $rule => $message) {
                if (is_array($message)) {
                    $this->displayValidationError($error, $io);
                    break;
                } else {
                    $message = '- ' . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $io->out($message);
                }
            }
        }
    }

    /**
     * Get user data from command line or prompt if interactive mode is on
     *
     * @param \Cake\Console\Arguments $args Arguments.
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return array
     */
    protected function askUserData(Arguments $args, ConsoleIo $io): array
    {
        $roleName = $args->getOption('role');
        $username = $args->getOption('username');
        $firstname = $args->getOption('first-name');
        $lastname = $args->getOption('last-name');
        $interactive = $args->getOption('interactive');

        // Interactively capture missing data if needed
        if (empty($username) && $interactive) {
            $username = $io->ask(__('User email (also called username)'));
        }
        if (empty($firstname) && $interactive) {
            $firstname = $io->ask(__('First name'));
        }
        if (empty($lastname) && $interactive) {
            $lastname = $io->ask(__('Last name'));
        }
        if (empty($roleName) && $interactive) {
            $roleName = $io->ask(__('Role name: user (default) or admin'));
        }
        if (empty($roleName)) {
            $roleName = Role::USER;
            $io->out('<warning>' . __('Role not found, using default user role.') . '</warning>');
        }
        $roleId = $this->Roles->getIdByName($roleName);
        $userData = [
            'username' => $username,
            'role_id' => $roleId, // if null it will be defaulted to user in beforeMarshal
            'profile' => [
                'first_name' => $firstname,
                'last_name' => $lastname,
            ],
        ];

        return $userData;
    }

    /**
     * Notify the user by trigerring a registerPost event
     *
     * @param \App\Model\Entity\User $user Entity User
     * @param \Cake\Console\ConsoleIo $io Console IO.
     * @return void
     */
    protected function notifyUser(User $user, ConsoleIo $io)
    {
        // Display the token in console for convenience
        $token = $this->AuthenticationTokens->getByUserId($user->id);

        if (EmailNotificationSettings::get('send.user.create')) {
            $message = __(
                "To start registration follow the link provided in your mailbox or here: \n{0}",
                Router::url('/setup/start/' . $user->id . '/' . $token->get('token'), true)
            );
        } else {
            $message = __(
                "To start registration follow the link provided here: \n{0}",
                Router::url('/setup/start/' . $user->id . '/' . $token->get('token'), true)
            );
        }

        $this->success($message, $io);
    }
}
