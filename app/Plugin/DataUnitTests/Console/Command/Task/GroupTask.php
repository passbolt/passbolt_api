<?php
/**
 * Insert Group Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.GroupTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Group', 'Model');

class GroupTask extends ModelTask {

	public $model = "Group";

	public static function getAlias() {
		$Group = ClassRegistry::init('Group');
		$aliases = array (
			'man' => $Group->findByName('management'),
			'acc' => $Group->findByName('accounting dpt'),
			'hr'  => $Group->findByName('human resources'),
			'dev' => $Group->findByName('developers'),
			'dtl' => $Group->findByName('developers team leads'),
			'dru' => $Group->findByName('developers drupal'),
			'cak' => $Group->findByName('developers cakephp'),
			'fre' => $Group->findByName('freelancers'),
			'cpa' => $Group->findByName("company a"),
			'cpb' => $Group->findByName("company b")
		);
		foreach ($aliases as $name=>$obj){
			$aliases[$name] = $obj['Group']['id'];
		}
		return $aliases;
	}

	protected function getData() {
		$g[] = array('Group' => array(
			'id' => '10ce2d3a-0468-433b-b59f-3053d7a10fce',
			'name' => 'management',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '20ce3d3a-0468-433b-b59f-3053d7a10fce',
			'name' => 'accounting dpt',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '30ce2d3a-0468-4334-b59f-3053d7a10fce',
			'name' => 'human resources',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '40ce2d3a-0468-423b-b59f-3053d7a10fce',
			'name' => 'developers',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '5ad56042-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'developers team leads',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		// $g[] = array('Group' => array(
			// 'id' => '5ad56042-c5cd-11e1-a0c5-080038896c4c',
			// 'name' => 'developers team leads junior',
			// 'created' => '2013-01-24 13:39:25', 'modified' => '2013-01-24 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		// ));
		$g[] = array('Group' => array(
			'id' => '6bd58742-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'developers drupal',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '6bd58742-c5cd-11e1-a0c5-080027796ce7',
			'name' => 'developers cakephp',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '6bd58742-c5cd-11e1-a0c6-080127896ce7',
			'name' => 'freelancers',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '8bd56042-d9cd-11e1-a0c5-080027796c4c',
			'name' => 'company a',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '9bd56042-c09d-11e1-a0c5-080027796c4c',
			'name' => 'company b',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$g[] = array('Group' => array(
			'id' => '10d56042-c5cd-11e1-a0c5-080877796c4c',
			'name' => 'Users',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		return $g;
	}
}
