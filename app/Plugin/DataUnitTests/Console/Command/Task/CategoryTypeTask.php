<?php
/**
 * Insert CategoryType Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.CategoryTypeTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('CategoryTypes', 'Model');

class CategoryTypeTask extends ModelTask {

	public $model = 'CategoryType';

	protected function getData() {
		$ct[] = array('CategoryType' => array(
			'id' => '0234f3a4-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'default',
			'description' => 'default category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ct[] = array('CategoryType' => array(
			'id' => '0234f3a4-c5cd-11e1-a0c5-081127796c4c',
			'name' => 'database',
			'description' => 'database category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$ct[] = array('CategoryType' => array(
			'id' => '0234f3a4-c5cd-11e1-a0c5-080027456c4c',
			'name' => 'ssh',
			'description' => 'ssh category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $ct;
	}
}
