<?php
/**
 * Insert Comment Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.CommentTask
 * @since        version 2.12.11
 */

require_once ('plugins' . DS . 'DataExtras' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Comment', 'Model');

class CommentTask extends ModelTask {
	
	public $model = 'Comment';
	
	protected function getData() {
		$c[] = array('Comment'=>array(
			'id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
			'parent_id' => null,
			'foreign_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'foreign_model' => 'Resource',
			'content' => 'this is a short comment',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$c[] = array('Comment'=>array(
			'id' => 'aaa00001-cccc-11d1-a0c5-080027796c4c',
			'parent_id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
			'foreign_id' => '50d77ff9-c358-4dfb-be34-1b63d7a10fce',
			'foreign_model' => 'Resource',
			'content' => 'this is a reply to the short comment',
			'created' => '2012-11-25 13:39:26',
			'modified' => '2012-11-25 13:39:26',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $c;
	}
}
