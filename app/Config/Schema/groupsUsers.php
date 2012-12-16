<?php
/**
 * Group Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.groups
 * @since        version 2.12.11
 */
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('GroupUser', 'Model');

class GroupUserSchema {

	public function init() {
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');
		$this->GroupUser = ClassRegistry::init('GroupUser');
		$this->insertGroupsUsers($this->_getDefaultGroupsUsers());
	}

	public function insertGroupsUsers ($gus) {
		foreach($gus as $gu) {
			$this->GroupUser->create();
			$this->GroupUser->save($gu);
		}
	}
	
	protected function _getDefaultGroupsUsers() {
//		$userRoleId = '0208f57a-c5cd-11e1-a0c5-080027796c4c';
		$groups = GroupSchema::getAlias();
		$users = UserSchema::getAlias();
		
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
//		$gu[] = array('GroupUser' => array(
//			'id' => 'bbd5a042-c5cd-11e1-a345-080027046c4c',
//			'group_id' => $groups[''],
//			'user_id' => $users[''],
//			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c', 'modified_by' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c'
//		));
		return $gu;
	}
}
