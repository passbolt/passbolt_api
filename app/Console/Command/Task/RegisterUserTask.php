<?php

/**
 * Register user task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('Role', 'Model');

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
			->addOption('notify', [
				'short' => 'n',
				'boolean' => true,
				'help' => __('Should the user be notified by email')
			])
			->addOption('first_name', [
				'short' => 'f',
				'help' => __('User\'s first name')
			])
			->addOption('last_name', [
				'short' => 'l',
				'help' => __('User\'s last name')
			])
			->addOption('username', [
				'short' => 'u',
				'help' => __('User\'s email')
			])
			->addOption('role', [
				'short' => 'r',
				'help' => __('User\'s role')
			]);

		return $parser;
	}

/**
 * Register a new user.
 */
	function execute() {
		$this->out(__('register_user'));
		$this->User = Common::getModel('User');
		$this->Role = Common::getModel('Role');

		// Is the command interactive.
		$this->interactive = $this->param('interactive');

		// Should the user be notified by email.
		$notify = $this->param('notify');

		// If the user's first name hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$firstName = $this->param('first_name');
		if (is_null($firstName)) {
			$firstName = $this->in(__('User\'s first name'));
		}

		// If the user's last name hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$lastName = $this->param('last_name');
		if (is_null($lastName)) {
			$lastName = $this->in(__('User\'s last name'));
		}

		// If the user's username hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$username = $this->param('username');
		if (is_null($username)) {
			$username = $this->in(__('User\'s email'));
		}

		// If the user's role hasn't been given as option and interactive mode is enabled
		// request the user to fill it.
		$role = $this->param('role');
		if (is_null($role)) {
			$role = $this->in(__('User\'s role'), ['admin', 'user'], 'user');
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
			$role_id = $roleUser['Role']['id'];
		}

		// Try to register the user.
		$data = [
			'User' => [
				'username' => $username,
				'role_id' => $role_id,
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
			$this->_stop();
			return;
		}

		$this->out(__('The user has been registered with success, '), 0);
		if ($notify) {
			$this->out(__('the user has been notified by email to complete the registration process.'));
		} else {
			$link = Router::url('/setup/install/' . $user['AuthenticationToken']['user_id'] . '/' . $user['AuthenticationToken']['token'], true);
			$this->out(__('complete the registration process by following the link : %s', $link));
		}
	}

}
