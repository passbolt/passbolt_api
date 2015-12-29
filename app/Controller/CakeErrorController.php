<?php
/**
 * Error Handling Controller
 *
 * Controller used by ErrorHandler to render error views.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Controller
 * @since         CakePHP(tm) v 2.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('AppController', 'Controller');

/**
 * Error Handling Controller
 *
 * Controller used by ErrorHandler to render error views.
 *
 * @package       Cake.Controller
 */
class CakeErrorController extends AppController {

    /**
     * Uses Property
     *
     * @var array
     */
    public $uses = array();

    /**
     * Constructor
     *
     * @param CakeRequest $request Request instance.
     * @param CakeResponse $response Response instance.
     */
    public function __construct($request = null, $response = null) {
        parent::__construct($request, $response);
        $this->constructClasses();
        if (count(Router::extensions()) &&
            !$this->Components->attached('RequestHandler')
        ) {
            $this->RequestHandler = $this->Components->load('RequestHandler');
        }
        if ($this->Components->enabled('Auth')) {
            $this->Components->disable('Auth');
        }
        if ($this->Components->enabled('Security')) {
            $this->Components->disable('Security');
        }
        $this->_set(array('cacheAction' => false, 'viewPath' => 'Errors'));
    }

    /**
     * Render an Exception
     * @param null $view
     * @param null $layout
     * @return CakeResponse
     */
    public function render($view = null, $layout = null) {
        // Default treatment is request is not JSON
        if(!$this->request->is('json')) {
            $this->layout = 'error';
            return parent::render($view, $layout);
        }

        // By default, the title is the controller name, action with an error type.
        $title = strtolower('app_' . $this->request->controller . '_' . $this->request->action . '_' . Message::ERROR);
        $response['header']['id'] = Common::uuid($title);
        $response['header']['status'] = 'error';
        $response['header']['title'] = $title;
        $response['header']['servertime'] = time();
        $response['header']['controller'] = $this->request->controller;
        $response['header']['action'] = $this->request->action;
        $response['header']['message'] = $this->viewVars['message'];

        if (isset($this->request->viewVars)) {
            $response['body'] = $this->request->viewVars;
        }
        $response['body'] = $this->response;
        $this->response->body(json_encode($response));
    }

}
