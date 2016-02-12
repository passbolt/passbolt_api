<?php 
/**
 * Dictionary Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('I18n', 'I18n');

class Dictionary extends AppModel {

/**
 * Details of use table
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/model-attributes.html
 */
	public $useTable = false;

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
