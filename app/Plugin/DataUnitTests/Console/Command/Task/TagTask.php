<?php
/**
 * Insert Tag Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.TagTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Tag', 'Model');

class TagTask extends ModelTask {

	public $model = 'Tag';

	protected function getData() {
		$ts[] = array('Tag' => array(
			'id' => Common::uuid('tag.id.social'),
			'name' => 'social',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => Common::uuid('tag.id.facebook'),
			'name' => 'facebook',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => Common::uuid('tag.id.twitter'),
			'name' => 'twitter',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => Common::uuid('tag.id.banking'),
			'name' => 'banking',
			'created' => '2012-11-25 13:39:25',
			'modified' => '2012-11-25 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$ts[] = array('Tag' => array(
			'id' => Common::uuid('tag.id.drupal'),
			'name' => 'drupal',
			'created' => '2014-09-07 19:33:00',
			'modified' => '2014-09-07 19:33:00',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $ts;
	}
}
