<?php
App::uses('AppModel', 'Model');
/**
 * FileStorageAppModel
 *
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class FileStorageAppModel extends AppModel {

/**
 * Behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Containable');

}