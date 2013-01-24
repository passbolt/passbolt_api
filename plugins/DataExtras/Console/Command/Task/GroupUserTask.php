<?php
/**
 * Insert GroupUser Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.GroupUserTask
 * @since        version 2.12.11
 */

require_once ('plugins' . DS . 'DataExtras' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('GroupUser', 'Model');

class GroupUserTask extends ModelTask {

	public $model = 'GroupUser';
	
	protected function getData() {
		$GroupTask = $this->Tasks->load('Data.Group');
		$groups = $GroupTask::getAlias();
		$UserTask = $this->Tasks->load('Data.User');
		$users = $UserTask::getAlias();
		
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd564542-c5cd-11e1-a345-080027796c4c',
			'group_id' => $groups['man'],
			'user_id' => $users['dar'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd564542-c5cd-11e1-a345-080027796c4c',
			'group_id' => $groups['acc'],
			'user_id' => $users['aur'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd56042-c5cd-11e1-a345-080877796c4c',
			'group_id' => $groups['hr'],
			'user_id' => $users['ism'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd56902-c5cd-11e1-a345-080027796c4c',
			'group_id' => $groups['hr'],
			'user_id' => $users['myr'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd56042-c5cd-11e1-aa55-080027796c4c',
			'group_id' => $groups['dev'],
			'user_id' => $users['rem'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd56042-c5cd-11e1-a345-0800279a6c4c',
			'group_id' => $groups['dev'],
			'user_id' => $users['kev'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbda6042-c5cd-11e1-a345-080027996c4c',
			'group_id' => $groups['dev'],
			'user_id' => $users['ced'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bb956042-c5cd-11e1-a345-080a27796c4c',
			'group_id' => $groups['dtl'],
			'user_id' => $users['rem'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbde0042-c5cd-1ae1-a345-080027796c4c',
			'group_id' => $groups['dru'],
			'user_id' => $users['kev'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbd5e042-c5cd-11e1-a345-080028896c4c',
			'group_id' => $groups['dru'],
			'user_id' => $users['ced'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbe56042-c5cd-11e1-a345-080927796c4c',
			'group_id' => $groups['cak'],
			'user_id' => $users['rem'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbe56042-c5cd-11e1-a345-080927796c4c',
			'group_id' => $groups['cpa'],
			'user_id' => $users['au1'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		$gu[] = array('GroupUser' => array(
			'id' => 'bbe560fa-c5cd-11e1-a345-080927796cfa',
			'group_id' => $groups['cak'],
			'user_id' => $users['fra'],
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		));
		// $gu[] = array('GroupUser' => array(
			// 'id' => 'bbd5a042-c5cd-11e1-a345-080027046c4c',
			// 'group_id' => $groups['tlj'],
			// 'user_id' => $users['ras'],
			// 'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		// ));
		// $gu[] = array('GroupUser' => array(
			// 'id' => 'bbd5a042-c5cd-11e1-a345-080027046c4c',
			// 'group_id' => $groups['tlj'],
			// 'user_id' => $users['ala'],
			// 'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
		// ));
//		$gu[] = array('GroupUser' => array(
//			'id' => 'bbd5a042-c5cd-11e1-a345-080027046c4c',
//			'group_id' => $groups[''],
//			'user_id' => $users[''],
//			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
//		));
		return $gu;
	}
}
