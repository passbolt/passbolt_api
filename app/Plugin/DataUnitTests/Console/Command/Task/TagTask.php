<?php
/**
 * Insert Tag Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.TagTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Tag', 'Model');

class TagTask extends ModelTask {

	public $model = 'Tag';

	protected function getData() {
		$ts[] = array('Tag' => array(
			'id' => 'aaa00000-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'social',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00001-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'facebook',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00002-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'twitter',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa00003-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'banking',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => 'aaa01103-c5cd-11d1-a1c5-080027796c4c',
			'name' => 'drupal',
			'created' => '2014-09-07 19:33:00',
			'modified' => '2014-09-07 19:33:00',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $ts;
	}
}
