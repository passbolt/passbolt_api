<?
/**
 * Languages Controller
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Controller.Langages
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 * @package			 app.Controller
 */
App::uses('Controller', 'Controller');
class LanguagesController extends AppController {

	/**
	 * Change the application language
	 * @access public
	 * @return void
	 */
	function set($locale) {
		// @todo move this to test
		$locale = 'fr-FR';
		// @todo ? User::get('settings.language') instead of:
		Configure::write('Config.language', $locale); 
		$this->Session->write('Config.language', $locale);
	}
}
