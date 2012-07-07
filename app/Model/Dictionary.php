<?php 
/**
 * Dictionary Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Model.Dictionary
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('I18n', 'I18n');
class Dictionary extends AppModel {
  /**
   * Get Dictionary
   * @param $l locale such as en_EN, fr_FR
   * @param $d dictionnary file name
   * @return mixed array or false
   */
  static function get($l = null, $d = null) {
    $locale = (isset($l)) ? $l : Configure::read('i18n.locale');
    $dicoName = (isset($d)) ? $d : Configure::read('i18n.dictionary');
    $dicoFile = APP .'Locale'.DS.$locale.DS.'LC_MESSAGES'.DS.$dicoName.'.po';
    $dico = false;
    if(file_exists($dicoFile)){
      $dico = I18n::loadPo($dicoFile);
    }
    return $dico;
  }
}

