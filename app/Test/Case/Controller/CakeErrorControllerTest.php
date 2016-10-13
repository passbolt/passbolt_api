<?php
/**
 * Favorites Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.FavoritesControllerTest
 * @since        version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('CakeErrorController', 'Controller');
App::uses('UserController', 'Controller');
App::uses('ControllerLog', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
    require CAKE . 'Model/Datasource/CakeSession.php';
}

class CakeErrorControllerTest extends ControllerTestCase {

    public $fixtures = array(
        'app.resource',
        'app.category',
        'app.categories_resource',
        'app.secret',
        'app.favorite',
        'app.user',
        'app.profile',
        'app.group',
        'app.groupsUser',
        'app.role',
        'app.permission',
        'app.permissions_type',
        'app.permission_view',
        'app.authenticationBlacklist',
        'app.gpgkey',
        'app.file_storage',
        'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
    );

    public function setUp() {
        parent::setUp();
    }

    /**
     * Test render
     */
    function testRenderMockedSuccess() {
        $this->CakeErrorController = new CakeErrorController();
        $this->CakeErrorController->request = new CakeRequest();
        $this->CakeErrorController->request->addDetector('json', [
            'callback' => function ($request) {
                return true;
            }
        ]);
        $this->CakeErrorController->response = new CakeResponse();
        $this->CakeErrorController->request->action = 'TestAction';
        $this->CakeErrorController->request->controller = 'TestController';
        $this->CakeErrorController->viewVars['message'] = 'TestMessage';
        $this->CakeErrorController->viewVars['error'] = new CakeObject();
        $this->CakeErrorController->viewVars['error']->invalidFields = ['TestField' => 'TestFieldError'];
        $json = $this->CakeErrorController->render();
        $result = json_decode($json);
        $this->assertEquals($result->header->status, 'error');
        $this->assertEquals($result->header->action, 'TestAction');
        $this->assertEquals($result->header->controller, 'TestController');
        $this->assertEquals($result->header->message, 'TestMessage');

        $controllerLog = ClassRegistry::init('ControllerLog');
        $log = $controllerLog->find('first', array(
            'order' => array('ControllerLog.created' => 'asc')
        ));
        $this->assertEquals($log['ControllerLog']['controller'], 'TestController');
        $this->assertEquals($log['ControllerLog']['message'], 'TestMessage');
        $this->assertEquals($log['ControllerLog']['action'], 'TestAction');
        unset($this->CakeErrorController);
    }

    /**
     * Test render
     */
    function testRenderMockedMessageSuccess() {
        $this->CakeErrorController = new CakeErrorController();
        $this->CakeErrorController->request = new CakeRequest();
        $this->CakeErrorController->request->addDetector('json', [
            'callback' => function ($request) {
                return true;
            }
        ]);
        $this->CakeErrorController->Message = new CakeObject();
        $this->CakeErrorController->Message->messages = [[
            'header' => [
                'action' => 'TestAction',
                'controller' => 'TestController',
                'message' => 'TestMessage',
                'status' => 'error'
            ]
        ]];
        $this->CakeErrorController->request->action = 'TestAction';
        $this->CakeErrorController->request->controller = 'TestController';
        $this->CakeErrorController->response = new CakeResponse();
        $json = $this->CakeErrorController->render();
        $result = json_decode($json);
        $this->assertEquals($result->header->status, 'error');
        $this->assertEquals($result->header->action, 'TestAction');
        $this->assertEquals($result->header->controller, 'TestController');
        $this->assertEquals($result->header->message, 'TestMessage');
        unset($this->CakeErrorController);
    }

	/**
	 * Check config is ok to run the tests
	 */
	public function testSeleniumConfig() {
		$configok = Configure::read('App.selenium.active');
		$this->assertTrue($configok, 'Selenium.active should be unabled in config to run these tests');
	}

    /**
     * Check 404 on public JSON endpoint
     */
    public function test404Json() {
        $this->setExpectedException('HttpException', '404 test not found');
        $this->testAction('/seleniumTests/error404.json', array('return' => 'contents', 'method' => 'GET'), true);
    }
    public function test404Json2() {
        $this->setExpectedException('HttpException', 'Not Found');
        $this->testAction('/seleniumTests/error404/exception.json', array('return' => 'contents', 'method' => 'GET'), true);
    }

    /**
     * Check 404 on public page
     */
    public function test404Page() {
        $this->setExpectedException('HttpException', '404 test not found');
        $this->testAction('/seleniumTests/error404', array('return' => 'contents', 'method' => 'GET'), true);
    }
    public function test404Page2() {
        $this->setExpectedException('HttpException', 'Not Found');
        $this->testAction('/seleniumTests/error404/exception', array('return' => 'contents', 'method' => 'GET'), true);
    }

    /**
     * Check if a bad request exception is thrown on a public endpoint with invalid method
     */
    public function testBadRequest() {
        $this->setExpectedException('HttpException', 'Invalid request method, should be PUT');
        $this->testAction('/users/validateAccount/xxx.json', array('return' => 'contents', 'method' => 'GET'), true);
    }

    /**
     * Check if a ForbiddenException is thrown when accessing a non public json endpoint
     */
    public function testNotAuthorizedJSON() {
        $this->setExpectedException('HttpException', 'You need to login to access this location');
        $this->testAction('/users/view/xxx.json', array('return' => 'contents', 'method' => 'GET'), true);
    }

    public function testErrorControllerRender() {

    }
}
