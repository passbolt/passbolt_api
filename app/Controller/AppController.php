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
    'Session', 'Paginator', 'Cookie', //'Auth',  // default
    'Message', 'Mailer'                          // custom
  );

  function beforeFilter() {
    // Paranoia - Hidding PHP version number
    $this->response->header('X-Powered-By', 'PHP'); 

    // Set default json layout for the ajax request
    // @todo add is_json callback
    //if ($this->request->is('json')) {
      $this->layout = 'json';
      $this->view = '/Json/default';
    //}

    // @todo this will be remove via the initial auth check 
    // User::set() will load default config
    if ($this->Session->read('Config.language') != null) {
      Configure::write('Config.language', $this->Session->read('Config.language'));
    } else {
      $this->Session->write('Config.language', Configure::read('Config.language'));
    }
  }

}
