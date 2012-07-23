<?php
/**
 * Application Controller
 * Application-wide methods, all controllers inherit them.
 *
 * @copyright     copyright 2012 Passbolt.com
 * @package       app.Controller.AppController
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Controller', 'Controller');
class AppController extends Controller {
  
  /**
   * @var $component application wide components 
   */
  public $components = array(
    'Session', 'Paginator', 'Cookie', 'Auth',  // default
    'Message', 'Mailer'                        // custom
  );

  /**
   * Called before the controller action.  You can use this method to configure and customize components
   * or perform logic that needs to happen before each controller action.
   * @link http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
   * @return void
   */
  function beforeFilter() {
    // Paranoia - Hidding PHP version number
    $this->response->header('X-Powered-By', 'PHP'); 

    // Set default json layout for the ajax request
    // @todo add is_json callback
    //if ($this->request->is('json')) {
      $this->layout = 'json';
      $this->view = '/Json/default';
    //}

    // Auth component initilization
    $this->Auth->loginAction = Configure::read('App.auth.loginAction');
    $this->Auth->loginRedirect = Configure::read('App.auth.loginRedirect');
    $this->Auth->logoutRedirect = Configure::read('App.auth.logoutRedirect');
    $this->Auth->authenticate = array('Form');
    $this->Auth->authorize = array('Controller'); //@see AppController::isAuthorized

    // @todo this will be remove via the initial auth check 
    // User::set() will load default config
    if ($this->Session->read('Config.language') != null) {
      Configure::write('Config.language', $this->Session->read('Config.language'));
    } else {
      $this->Session->write('Config.language', Configure::read('Config.language'));
    }
  }

  /**
   * Authorization check main callback
   * @link http://api20.cakephp.org/class/auth-component#method-AuthComponentisAuthorized
   * @param mixed $user The user to check the authorization of. If empty the user in the session will be used.
   * @return boolean True if $user is authorized, otherwise false
   * @access public
   */
  function isAuthorized($user) {
    if($this->isWhitelisted()) {
      return true;
    } else {
      // @todo authorization
      return true;
    }
  }

  /**
   * Is the controller:action pair whitelisted in config? (see. App.auth.whitelist) 
   * @param string $controller, current is used if null
   * @param string $action, current is used if null
   * @return bool true if the controller action pair is whitelisted
   */
  function isWhitelisted($controller=null, $action=null) {
    if ($controller == null) {
      $controller = strtolower($this->name);
    }
    if ($action == null) {
      $action = $this->action;
    }
    $whitelist = Configure::read('App.auth.whitelist');
    return (isset($whitelist[$controller][$action]));
  }
}