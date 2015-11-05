<?php
/**
 * Our Custom Exception Renderer
 *
 * Provides Exception rendering features.  Which allow exceptions to be rendered
 * as HTML pages.
 *
 * @copyright    copyright 2012 Passbolt.com
 * @package      app.Controller.ExceptionRenderer
 * @since        version 2.13.9
 * @license      http://www.passbolt.com/license
 */

App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

/**
 * Convenience method to display a 400 series page.
 *
 * @param Exception $error
 * @return void
 */
	public function error400($error) {
		// If the request is not Ajax, use the default exception handler behavior
		if(!$this->controller->request->isAjax() && !$this->controller->request->is('json')) {
			return parent::error400($error);
		}
		// Set the http response status code
		$code = ($error->getCode() >= 400 && $error->getCode() < 506) ? $error->getCode() : 500;
		$this->controller->response->statusCode($code);

		// Render the Json template
		$this->controller->render('/Json/default');
		$this->controller->afterFilter();
		$this->controller->response->send();
	}
}
