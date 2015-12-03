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
			'id' => Common::uuid('category_type.id.default'),
			'name' => 'default',
			'description' => 'default category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ct[] = array('CategoryType' => array(
			'id' => Common::uuid('category_type.id.database'),
			'name' => 'database',
			'description' => 'database category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ct[] = array('CategoryType' => array(
			'id' => Common::uuid('category_type.id.ssh'),
			'name' => 'ssh',
			'description' => 'ssh category type description',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $ct;
	}
}
