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

class GroupSchema {

	public function init() {
		$this->Group = ClassRegistry::init('Group');
		$this->User = ClassRegistry::init('User');
		$this->GroupUser = ClassRegistry::init('GroupUser');
		$this->insertGroups($this->_getDefaultGroups());
	}

	public function insertGroups ($groups) {
		foreach ($groups as $groupName => $users) {
			// Insert group
			if ($groupName != 'Users') {
				$this->Group->create();
				$group = $this->Group->save(array(
					'Group' => array(
						'name' => $groupName
					)
				));
			}

			foreach ($users['Users'] as $value) {
				if (!($user = $this->User->findByUsername($value['User']['username']))) {
					$this->User->create();
					$user = $this->User->save($value);
				}
				if ($groupName != 'Users') {
					$this->GroupUser->create();
					$this->GroupUser->save(array(
						'GroupUser' => array( 'group_id' => $group['Group']['id'], 'user_id' => $user['User']['id'] )
					));
				}
			}
		}
	}

	protected function _getDefaultGroups() {
		$userRoleId = '0208f57a-c5cd-11e1-a0c5-080027796c4c';
		$defaultPassword = 'test123';
		$categories = array (
			'management' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'dark.vador@test.com', 'password' => $defaultPassword, 'active' => '1')),
				)
			),
			'accounting dpt' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'aurelie.gerhards@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
			'human resources' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'ismail.guennouni@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'myriam.djerouni@test.com', 'password' => $defaultPassword, 'active' => '1'))
				),
			),
			'developers' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'remy.bertot@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'cedric.alfonsi@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'kevin.muller@test.com', 'password' => $defaultPassword, 'active' => '1'))
				),
			),
			'developers team leads' => array(
				'Users' => array(
					array('User' => array( 'username' => 'remy.bertot@test.com'))
				),
			),
			'developers drupal' => array(
				'Users' => array(
					array('User' => array( 'username' => 'cedric.alfonsi@test.com')),
					array('User' => array( 'username' => 'kevin.muller@test.com'))
				),
			),
			'developers cakephp' => array(
				'Users' => array(
					array('User' => array( 'username' => 'remy.bertot@test.com'))
				),
			),
			'freelancers' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'a-user1@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'a-user2@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'b-user1@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'b-user2@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
			'company a' => array(
				'Users' => array(
					array('User' => array('username' => 'a-user1@test.com')),
					array('User' => array('username' => 'a-user2@test.com')),
				),
			),
			'company b' => array(
				'Users' => array(
					array('User' => array('username' => 'b-user1@test.com')),
					array('User' => array('username' => 'b-user2@test.com')),
				),
			),
			'Users' => array(
				'Users' => array(
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'jean-rene@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'adminsys@test.com', 'password' => $defaultPassword, 'active' => '1')),
					array('User' => array( 'role_id' => $userRoleId, 'username' => 'ramesh.kumar@test.com', 'password' => $defaultPassword, 'active' => '1')),
				),
			),
		);
		return $categories;
	}
}
