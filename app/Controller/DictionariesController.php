<?php
/**
 * Gettext Dictionary Controller
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.DictionaryController
 * @since			version 2.12.7
 */
class DictionariesController extends AppController {

/**
 * Get the text dictionnary
 * @param $dicoName
 */
	public function view($l = 'default') {
		// get user locale or application default one
		// TODO #PASSBOLT-158 User::get('i18n.locale');
		$l = ($l != 'default') ? $l : Configure::read('i18n.locale');

		// find it in cache or read from model
		$cache	= Cache::read('dictionary_' . $l, '_cake_model_');
		if ($cache === false) {
			$data = $this->Dictionary->get($l);
			if ($data) {
				Cache::write('dictionary_' . $l, $data, '_cake_model_');
			}
		} else {
			$data = $cache;
		}

		// are you happy now?
		if ($data) {
			$this->Message->success();
			$this->set('data', $data);
		} else {
			$this->Message->error(__('Sorry the dictory could not be found'));
		}
	}

}
