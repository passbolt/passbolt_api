<?php
/**
 * Text Dictionary Controller
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.Dictionary
 * @since         version 2.12.7
 */
class DictionariesController extends AppController {

  /**
   * Get the text dictionnary
   * @todo cache the json dictionnary
   * @param $dicoName
   */
  function get($l = 'default') {
    $l = ($l != 'default') ? Configure::read('i18n.locale') : User::get('i18n.locale');
    $data = $this->Dictionary->get($l);
    if($data) {
      $this->set('data', $data);
      $this->Message->success();
    } else {
      $this->Message->error(__('Sorry the dictory could not be found'));
    }
  }
}
