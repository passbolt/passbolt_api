<?php
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
namespace App\Shell\Task;

use App\Controller\Events\EmailNotificationsListener;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Shell\AppShell;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\Exception\InternalErrorException;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Ramsey\Uuid\Uuid;

class RegisterUserTask extends AppShell
{
    /**
     * Initializes the Shell
     * acts as constructor for subclasses
     * allows configuration of tasks prior to shell execution
     *
     * @return void
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#Cake\Console\ConsoleOptionParser::initialize
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Roles');
        $this->loadModel('AuthenticationTokens');
    }

    /**
     * Gets the option parser instance and configures it.
     *
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser
            ->setDescription(__('Register a new user.'))
            ->addOption('interactive', [
                'short' => 'i',
                'boolean' => true,
                'help' => __('Enable interactive mode')
            ])
            ->addOption('interactive-loop', [
                'default' => 3,
                'help' => __('Enable interactive mode')
            ])
            ->addOption('username', [
                'short' => 'u',
                'help' => __('The user email aka username')
            ])
            ->addOption('first-name', [
                'short' => 'f',
                'help' => __('The user first name')
            ])
            ->addOption('last-name', [
                'short' => 'l',
                'help' => __('The user last name')
            ])
            ->addOption('role', [
                'short' => 'r',
                'help' => __('The User role, such as "admin" or "user"')
            ]);

        return $parser;
    }

    /**
     * Main registration task
     *
     * @return bool
     */
    public function main()
    {
        // Root user is not allowed to execute this command.
        if (!$this->assertNotRoot()) {
            return false;
        }

        // Who is creating the user?
        // use the oldest admin or temporary non existing one
        $admin = $this->Users->findFirstAdmin();
        if (isset($admin)) {
            $accessControl = new UserAccessControl(Role::ADMIN, $admin->id);
        } else {
            // Act as an admin but without a user set
            $accessControl = new UserAccessControl(Role::ADMIN);
        }

        $result = false;
        $attempt = 0;
        if ($this->param('interactive')) {
            $maxAttempt = $this->param('interactive-loop');
        } else {
            $maxAttempt = 1;
        }
        while (($attempt < $maxAttempt)) {
            $result = false;
            $attempt++;
            $data = $this->_getUserData();
            try {
                $user = $this->Users->register($data, $accessControl);
                $result = true;
                break;
            } catch (ValidationException $exception) {
                $this->out(__('Validation failed for the following user data:'));
                $this->_displayValidationError($exception->getErrors());
            } catch (InternalErrorException $exception) {
                $this->out(__('Something went wrong when trying to save the user, please try again.'));
            };
        }

        if (!$result) {
            $this->_error(__('User registration failed.'));

            return false;
        }

        $this->_success(__('User saved successfully.'));
        $this->_notifyUser($user);

        return true;
    }

    /**
     * Display the entity validation errors
     *
     * @param array $errors validation errors
     * @return void
     */
    protected function _displayValidationError($errors)
    {
        foreach ($errors as $fieldname => $error) {
            foreach ($error as $rule => $message) {
                if (is_array($message)) {
                    $this->_displayValidationError($error);
                    break;
                } else {
                    $message = '- ' . ucfirst(str_replace('_', ' ', $fieldname)) . ': ' . $message;
                    $this->out($message);
                }
            }
        }
    }

    /**
     * Get user data from command line or prompt if interactive mode is on
     *
     * @return array
     */
    protected function _getUserData()
    {
        $roleName = $this->param('role');
        $username = $this->param('username');
        $firstname = $this->param('first-name');
        $lastname = $this->param('last-name');
        $interactive = $this->param('interactive');

        // Interactively capture missing data if needed
        if (empty($username) && $interactive) {
            $username = $this->in(__('User email (also called username)'));
        }
        if (empty($firstname) && $interactive) {
            $firstname = $this->in(__('First name'));
        }
        if (empty($lastname) && $interactive) {
            $lastname = $this->in(__('Last name'));
        }
        if (empty($roleName) && $interactive) {
            $roleName = $this->in(__('Role name: user (default) or admin'));
        }
        if (empty($roleName)) {
            $roleName = Role::USER;
            $this->out('<warning>' . __('Role not found, using default user role.') . '</warning>');
        }
        $roleId = $this->Roles->getIdByName($roleName);
        $userData = [
            'username' => $username,
            'role_id' => $roleId, // if null it will be defaulted to user in beforeMarshal
            'profile' => [
                'first_name' => $firstname,
                'last_name' => $lastname
            ]
        ];

        return $userData;
    }

    /**
     * Notify the user by trigerring a registerPost event
     *
     * @param object $user Entity User
     * @return void
     */
    protected function _notifyUser($user)
    {
        // Display the token in console for convenience
        $AuthenticationTokens = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $token = $AuthenticationTokens->getByUserId($user->id);
        $this->_success(
            __(
                "To start registration follow the link in provided in your mailbox or here: \n{0}",
                Router::url('/setup/install/' . $user->id . '/' . $token->token, true)
            )
        );
    }
}
