<?php
/**
 * Role Schema
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Config.Schema.roles
 * @since        version 2.12.11
 */
App::uses('Role', 'Model');

class RoleSchema {

	public function init() {
		$role = ClassRegistry::init('Role');
		$rs = $this->_getDefaultRoles();
		foreach ($rs as $r) {
			$role->create();
			$role->save($r);
		}
	}

	protected function _getDefaultRoles() {
		$rs[] = array('Role' => array(
			'id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'guest',
			'description' => 'Non logged-in user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'user',
			'description' => 'Logged in default user',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'admin',
			'description' => 'Organization administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		$rs[] = array('Role' => array(
			'id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'name' => 'root',
			'description' => 'Super Administrator',
			'created' => '2012-07-04 13:39:25',
			'modified' => '2012-07-04 13:39:25',
			'created_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'modified_by' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c'
		));
		return $rs;
	}
}
