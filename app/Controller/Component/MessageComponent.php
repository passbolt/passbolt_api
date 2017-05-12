<?php
/**
 * Message Component
 *
 * This class replace cake flash method. It offers options to qualify the end-user messages
 * But more importantly this component is also used to format the JSON API responses
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
app::uses('ControllerLog', 'Model');

/**
 * Class MessageComponent
 */
class MessageComponent extends Component {

/**
 * @var string $controllerVar key used to store messages in controller/view data
 */
	public $controllerVar = 'json';

/**
 * @var string key used to store message in sessions
 */
	public $sessionKey = 'Messages';

/**
 * @var Controller $controller convenience reference to the parent controller
 */
	public $controller;

/**
 * @var Controller $controller convenience reference to the parent controller
 */
	public $response;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @throws CakeException if session component is not present
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		return true;
	}

/**
 * Add a success message to the queue
 *
 * @param string $message title (optional)
 * @return void
 */
	public function success($message = null) {
		// The response message
		$response = [
			'header' => [],
			'body' => []
		];
		$level = strtolower(Status::SUCCESS);

		// By default, the title is the controller name.
		$title = strtolower('app_' . $this->controller->name . '_' . $this->controller->action . '_' . $level);

		// Set the header of the message
		$response['header'] = [
			// UUID is predictable
			'id' => Common::uuid($title),
			'status' => strtolower($level),
			'title' => $title,
			'servertime' => time(),
			'message' => $message,
			'controller' => $this->controller->name,
			'action' => $this->controller->action
		];

		// Set the body of the message
		if (isset($this->controller->viewVars['data'])) {
			$response['body'] = $this->controller->viewVars['data'];
		} else {
			$response['body'] = [];
		}

		// Log if needed
		try {
		    ControllerLog::write($level, $this->controller->request, $message, '');
        } catch(Exception $e) {
            // This may happen if no table are present
            // We still want to go forward
        }
        $this->response = $response;
		$this->controller->set($this->controllerVar, $response);
	}
}
