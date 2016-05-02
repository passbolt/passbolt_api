<?php
/**
 * Auth Controller
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
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
			$userAgent = UserAgent::parse();
			$this->set('userAgent', $userAgent);
			$this->layout = 'login';
			$this->view = '/Auth/login';
		} else {
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
				} else {
					$this->response->type('json');
				}

				return $this->Message->success();
			} else {
				return $this->Message->error(
					__('The public key for this passbolt instance was not found.'),
					['code' => '500']
				);
			}
		}
	}

/**
 * Logout
 *
 * @return void
 */
	public function logout() {
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
