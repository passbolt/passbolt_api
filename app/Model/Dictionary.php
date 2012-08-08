<?php 
/**
 * Dictionary Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Model.Dictionary
 * @since				 version 2.12.7
 */
App::uses('I18n', 'I18n');
class Dictionary extends AppModel {

/**
 * Get Dictionary
 *
 * @param $l locale such as en_EN, fr_FR
 * @param $d dictionnary file name
 * @return mixed array or false
 */
	public static function get($l = null, $d = null) {
		$locale = (isset($l)) ? $l : Configure::read('i18n.locale');
		$dicoName = (isset($d)) ? $d : Configure::read('i18n.dictionary');
		$dicoFile = APP . 'Locale' . DS . $locale . DS . 'LC_MESSAGES' . DS . $dicoName . '.po';
		$dico = false;
		if (file_exists($dicoFile)) {
			$dico = I18n::loadPo($dicoFile);
		}
		return $dico;
	}
}
