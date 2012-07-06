<?php
/**
 * Application Controller
 *
 * Application-wide methods, all controllers inherit them.
 *
 * @package     app.Controller.AppController
 * @link        http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
App::uses('Controller', 'Controller');
class AppController extends Controller {

  function beforeFilter() {
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
