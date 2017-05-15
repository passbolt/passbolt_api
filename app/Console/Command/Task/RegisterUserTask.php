<?php

/**
 * Register user task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Controller', 'Controller');
App::uses('ComponentCollection', 'Controller');
App::uses('EmailNotificatorComponent', 'Controller/Component');

class RegisterUserTask extends AppShell {

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->description(__('Register a new user'))
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
				'help' => __('User\'s username (email)')
			])
			->addOption('first-name', [
				'short' => 'f',
				'help' => __('User\'s first name')
			])
			->addOption('last-name', [
				'short' => 'l',
				'help' => __('User\'s last name')
			])
			->addOption('role', [
				'short' => 'r',
				'help' => __('User\'s role')
			]);

		return $parser;
	}

/**
 * Register a new user.
 *
 * @return void
 */
	public function execute() {
		static $count = 0;
		$count++;

		$this->User = Common::getModel('User');
		$this->Role = Common::getModel('Role');

		// Is the command interactive.
		$this->interactive = $this->param('interactive');
		$interactiveLoop = 0;
		if ($this->interactive) {
			$this->out('Enter the user\'s information :');
			$interactiveLoop = $this->param('interactive-loop');
		}

		// If the user's username hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$username = $this->param('username');
		if (is_null($username)) {
			$username = $this->in(__('Username (email)'));
		}

		// If the user's first name hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$firstName = $this->param('first-name');
		if (is_null($firstName)) {
			$firstName = $this->in(__('First name'));
		}

		// If the user's last name hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$lastName = $this->param('last-name');
		if (is_null($lastName)) {
			$lastName = $this->in(__('Last name'));
		}

		// If the user's role hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$role = $this->param('role');
		if (is_null($role)) {
			$role = $this->in(__('Role'), ['admin', 'user'], 'user');
			if (is_null($role)) {
				$role = 'user';
			}
		}
		// Retrieve the role id
		$findRoleParams = [
			'conditions' => [
				'name' => $role
			],
		];
		$roleUser = $this->Role->find('first', $findRoleParams);
		if (!empty($roleUser)) {
			$roleId = $roleUser['Role']['id'];
		}

		// Try to register the user.
		$data = [
			'User' => [
				'username' => $username,
				'role_id' => $roleId,
			],
			'Profile' => [
				'first_name' => $firstName,
				'last_name' => $lastName,
			]
		];
		try {
			$user = $this->User->registerUser($data);
		}
		// If something goes wrong, print the validation errors on the console.
		catch (ValidationException $e) {
			$message = $e->getMessage();
			$this->out(__('<error>Error</error> : %s', $message));

			$validation = $e->invalidFields;
			foreach ($validation as $modelName => $fieldsErrors) {
				foreach ($fieldsErrors as $fieldName => $fieldErrors) {
					$this->out($fieldName . ' : ');
					foreach ($fieldErrors as $fieldError) {
						$this->out('  - ' . $fieldError);
					}
				}
			}

			// If interactive, and retry count is not over.
			if ($interactiveLoop && $count < $interactiveLoop) {
				$this->out();
				$this->execute();
			} else {
				$this->_stop();
			}
			return;
		}

		// Write down the link to follow to complete the registration process.
		$link = Router::url('/setup/install/' . $user['AuthenticationToken']['user_id'] . '/' . $user['AuthenticationToken']['token'], true);
		$this->out(__('The user has been registered with success, to complete the registration process follow the link : %s', $link));

		// Notify the user by email
		$this->_accountCreationNotification($user);
		$this->out(__('The user has been notified by email to complete his registration process.'));
	}

/**
 * Send a notification email regarding a new account created for a user.
 *
 * @param array $user The created user
 * @return void
 */
	protected function _accountCreationNotification($user) {
		$Collection = new ComponentCollection();
		$EmailNotificator = new EmailNotificatorComponent($Collection);
		$EmailNotificator->initialize(new Controller(), array());
		$EmailNotificator->accountCreationNotification(
			$user['Profile']['user_id'], [
			'token' => $user['AuthenticationToken']['token'],
			'creator_id' => Common::uuid('user.id.anonymous'),
			'self' => false,
		]);
	}

}
