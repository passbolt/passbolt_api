<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Model.AppModel
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('Model', 'Model');
class AppModel extends Model {
  var $actsAs = array('Containable');

  /**
   * Never fetch any recursive data from associated models
   * Use containable for any assocs
   * @var integer
   */
  public $recursive = -1;

  /**
   * Check if two field values are equal (as in password confirmation)
   * @param array $field check
   * @param string $field2 as in 'User.password_confirm'
   * return boolean
   */
  function isConfirmed($field, $field2) {
    list($model,$field3) = (preg_split('/\./',$field2));
    return(isset($this->data[$model][$field3]) && $this->data[$model][$field3] == current($field));
  }

  /**
   * Indicates if a given string is a UUID
   * @param string $str
   * @return boolean
   */
  static function isUuid($str) {
    return is_string($str) && preg_match('/^[A-Fa-f0-9]{8}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{4}-[A-Fa-f0-9]{12}$/', $str);
  }

}
