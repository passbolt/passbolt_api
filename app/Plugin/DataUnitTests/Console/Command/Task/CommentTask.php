<?php
/**
 * Insert Comment Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.CommentTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Comment', 'Model');
App::uses('Resource', 'Model');

class CommentTask extends ModelTask {
	
	public $model = 'Comment';
	
	protected function getData() {
		$this->Resource = ClassRegistry::init('Resource');

		$c[] = array('Comment'=>array(
			'id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
			'parent_id' => null,
			'foreign_id' => Common::uuid('resource.id.salesforce-account'),
			'foreign_model' => 'Resource',
			'content' => 'this is a short comment',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.irene'),
			'modified_by' => Common::uuid('user.id.irene'),
		));
		$c[] = array('Comment'=>array(
			'id' => 'aaa00001-cccc-11d1-a0c5-080027796c4c',
			'parent_id' => 'aaa00000-cccc-11d1-a0c5-080027796c4c',
			'foreign_id' => Common::uuid('resource.id.salesforce-account'),
			'foreign_model' => 'Resource',
			'content' => 'this is a reply to the short comment',
			'created' => '2012-11-25 13:39:26',
			'modified' => '2012-11-25 13:39:26',
			'created_by' => Common::uuid('user.id.irene'),
			'modified_by' => Common::uuid('user.id.irene'),
		));
		return $c;
	}
}
