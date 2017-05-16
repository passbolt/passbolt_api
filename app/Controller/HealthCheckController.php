<?php
/**
 * Health Check Controller
 * Help administrators with application install status
 *
 * @copyright (c) 2016-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Healthchecks', 'Lib');

class HealthCheckController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = [];

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
        $this->Auth->allow(['status','index']);
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
        // Allow access only in debug mode or if logged in as admin
        if (Configure::read('debug') == 0) {
            if (User::get('Role.name') != Role::ADMIN) {
                throw new ForbiddenException();
            }
        }
		$this->layout = 'login';
		$checks = Healthchecks::all();
		$checks = array_merge($this->__webChecks(), $checks);
		$this->set('checks', $checks);
	}

/**
 * A simple ok page allowing to see if the site is up
 */
    public function status() {
        if(!$this->request->is('json')) {
            $this->layout = 'empty';
        };
		$this->set('data', 'OK');
        $this->Message->success(__("OK"));
    }

/**
 * Check that needs to be performs in the request context
 *
 * @access private
 * @return bool
 */
	private function __webChecks() {
        $checks['ssl'] = $this->request->is('ssl');
        return $checks;
	}
}