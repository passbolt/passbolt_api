<?php
/**
 * Text Dictionary Controller
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Controller.Dictionary
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 * @package       app.Controller
 */
class DictionariesController extends AppController {

  /**
   * Get the text dictionnary
   * @todo cache the json dictionnary
   * @param $dicoName
   */
  function get($l = 'default') {
    $l = ($l != 'default') ? $l : User::get('i18n.locale');
    $data = $this->Dictionary->get($l);
    if($data) {
      $this->set(compact($data));
    } else {
      //@todo 404 dictionary not found?
    }
  }
}
