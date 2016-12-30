<?php
/**
 * Health Check Controller
 * Help administrators with application install status
 *
 * @copyright (c) 2016-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('HttpSocket', 'Network/Http');
App::uses('Migration', 'Lib/Migration');
App::uses('Validation', 'Utility');

class HealthCheckController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = [];

/**
 * Results of healthchecks
 * @var array
 */
	protected $_checks = [];

/**
 * MigrationVersion class
 *
 * @var MigrationVersion
 */
	protected $_MigrationVersion = null;

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		$this->Auth->allow(['index']);
		parent::beforeFilter();
	}

/**
 * Index
 * Display information about the passbolt instance
 * It is only available in debug mode or for logged in administrators
 *
 * @return void
 */
	public function index() {
		$this->layout = 'login';

		$this->__databaseChecks();
		$this->__accessChecks();
		$this->__adminCountCheck();
		$this->__coreChecks();
		$this->__appChecks();
		$this->__devChecks();
		$this->set('checks', $this->_checks);
	}

/**
 * Check that there is at least one admin in the DB
 *
 * @access private
 * @return bool
 */
	private function __adminCountCheck() {
		$this->_checks['adminCount'] = false;
		if ($this->_checks['dbConnect']) {
			$this->User = Common::getModel('User');
			try {
				$i = $this->User->find('count', [
					'conditions' => ['Role.name' => Role::ADMIN],
					'contain' => ['Role' => [
						'fields' => [
							'Role.id',
							'Role.name'
						]
					]]
				]);
				$this->_checks['adminCount'] = ($i > 0);
			} catch(Exception $e) {
			}
		}
	}

/**
 * Database healthchecks
 *
 * @return void
 * @access private
 */
	private function __databaseChecks() {
		$this->_checks['dbFile'] = (file_exists(APP . 'Config' . DS . 'database.php'));
		$this->_checks['dbConnect'] = false;
		if ($this->_checks['dbFile']) {
			App::uses('ConnectionManager', 'Model');
			try {
				ConnectionManager::getDataSource('default');
				$this->_checks['dbConnect'] = true;
			} catch (Exception $connectionError) {
			}
		}
	}

/**
 * Cakephp Core checks
 *
 * @return void
 * @access private
 */
	private function __coreChecks() {
		$settings = Cache::settings();
		$this->_checks['settings'] = (!empty($settings));
		$this->set('settings', $settings);
		$this->_checks['phpVersion'] = (version_compare(PHP_VERSION, '5.2.8', '>='));
		$this->_checks['tmp'] = is_writable(TMP);
		$this->_checks['debug'] = Configure::read('debug') > 0;
		$this->_checks['validation'] = (Validation::alphaNumeric('cakephp'));
	}

/**
 * Access checks
 *
 * @throws ForbiddenException if application is not in debug mode or user is not admin
 * @return void
 * @access private
 */
	private function __accessChecks() {
		if (Configure::read('debug') == 0) {
			if (User::get('Role.name') != Role::ADMIN) {
				throw new ForbiddenException();
			} else {
				$this->layout = 'default';
			}
		}
	}

/**
 * Application checks
 *
 * @return void
 * @access private
 */
	private function __appChecks() {
		try {
			$this->_checks['remoteVersion'] = Migration::getLatestTagName();
			$this->_checks['latestVersion'] = Migration::isLatestVersion();
		} catch (exception $e) {
			$this->_checks['remoteVersion'] = null;
			$this->_checks['latestVersion'] = null;
		}
		$this->_checks['needMigration'] = Migration::needMigration();
		$this->_checks['ssl'] = $this->request->is('ssl');
		$this->_checks['sslForce'] = configure::read('App.ssl.force');
		$this->_checks['gpg'] = (class_exists('gnupg'));
		$this->_checks['gpgKeyDefault'] = (Configure::read('GPG.serverKey.fingerprint') != '2FC8945833C51946E937F9FED47B0811573EE67E');
		$this->_checks['gpgKey'] = (Configure::read('GPG.serverKey.fingerprint') != null);
		$this->_checks['selenium'] = Configure::read('App.selenium.active');
		$this->_checks['registration'] = !Configure::read('App.registration.public');
		$this->_checks['jsProd'] = (Configure::read('App.js.build') == 'production');
		$this->_checks['imgPublicWritable'] = is_writable(IMAGES . DS . 'public');
	}

/**
 * Development checks
 *
 * @return void
 * @access private
 */
	private function __devChecks() {
		$this->_checks['debugKit'] = CakePlugin::loaded('DebugKit');
		$this->_checks['phpunit'] = (class_exists('PHPUnit_Runner_Version'));
		$this->_checks['phpunitVersion'] = ($this->_checks['phpunit'] && PHPUnit_Runner_Version::id() === '3.7.38');
	}
}