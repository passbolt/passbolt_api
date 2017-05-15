<?php
/**
 * Auth Controller
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('UserAgent', 'Model');

class AuthController extends AppController {

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		$this->Auth->allow([
			'login',
			'logout',
			'verify',
		]);
		parent::beforeFilter();
	}

/**
 * Login
 *
 * @return void
 */
	public function login() {
		// check if the user Authentication worked
		if (!$this->Auth->login()) {

			// Check if we are using Gpg auth, and if we are at stage 1.
			$isGpgAuthStage1 = isset($this->request->data['gpg_auth']) &&
				!isset($this->request->data['gpg_auth']['user_token_result']);

			// Stage 1 will always return false, so we don't log the attempt as an error.
			// We log as an error only if we are outside of this context.
			if (!$isGpgAuthStage1) {
				// Log login error.
				ControllerLog::write(Status::ERROR, $this->request, 'login_failure', '');
			}

			$userAgent = UserAgent::parse();
			$this->set('userAgent', $userAgent);
			$this->layout = 'login';
			$this->view = '/Auth/login';
		} else {
			// Log login success.
			ControllerLog::write(Status::SUCCESS, $this->request, 'login_success', '');

			if ($this->request->is('json')) {
				// We do not redirect since the Javascript app will take care of this
				// Also it messes up with the GPGAuth headers if we do
			} else {
				return $this->redirect($this->Auth->redirectUrl());
			}
		}
	}

/**
 * Triggers GPGAuth first step, e.g. server key verification
 *
 * @return void
 */
	public function verify() {
		if ($this->request->is('post')) {
			$this->Auth->login();
		} else {
			$key['fingerprint'] = Configure::read('GPG.serverKey.fingerprint');
			$file = new File(Configure::read('GPG.serverKey.public'));
			if ($file->exists()) {
				$key['keydata'] = $file->read();
				$this->set('data', $key);
				if (!$this->request->is('json')) {
					$this->layout = 'empty';
				}
				$this->Message->success();
			} else {
                throw new InternalErrorException(__('The public key for this passbolt instance was not found.'));
			}
		}
	}

/**
 * Logout
 *
 * @return void
 */
	public function logout() {
		// Log action.
		ControllerLog::write(Status::SUCCESS, $this->request, 'error', '');

		// Redirect.
		$this->redirect($this->Auth->logout());
	}

/**
 * Is the user still logged in ?
 * If the user is not logged in, he will not be able to access this controller action, and he will get a 403 response.
 * See GpgAuthenticate::unauthenticated()
 *
 * @return void
 */
	public function checkSession() {
		return $this->Message->success();
	}

}
