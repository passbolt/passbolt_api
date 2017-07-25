<?php
/**
 * Api Controller
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
use Swagger\Swagger;

class ApiController extends AppController {

/**
 * Called before the controller action. Used to manage access right
 *
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeFilter() {
		$allow = [
			'swagger'
		];
		$this->Auth->allow($allow);
		parent::beforeFilter();
	}

/**
 * Generates Swagger-PHP JSON documentation file
 *
 * @throws ForbiddenException if application is not in debug mode
 * @return void
 */
	public function swagger() {
		if (Configure::read('debug') < 1) {
			throw new ForbiddenException();
		}

		$this->layout = 'json';

		// API doc info block
		// is built from config and saved tmp/swagger/info.php
		$view = new View($this, false);
		$html = $view->render('info');
		$file = new File(TMP . DS . 'swagger' . DS . 'info.php');
		$file->write($html);
		$file->close();

		// Scan
		$this->viewPath = 'Json';
		$this->view = 'default';

		$pathList = [
			APP . 'tmp' . DS . 'swagger',
			APP . 'Controller',
			APP . 'Model'
		];
		$swagger = \Swagger\scan($pathList);
		$this->set('json', $swagger);
	}
}