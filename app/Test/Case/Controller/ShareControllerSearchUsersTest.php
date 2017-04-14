<?php
/**
 * Share Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package      app.Test.Case.Controller.ShareController
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @since        version 2.12.12
 */
App::uses('AppController', 'Controller');
App::uses('PermissionsController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('Resource', 'Model');
App::uses('UserResourcePermission', 'Model');;
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');

class ShareControllerSearchUsersTest extends ControllerTestCase {

	public $fixtures = array(
		'app.resource',
		'app.secret',
		'app.favorite',
		'app.user',
		'app.group',
		'app.groups_user',
		'app.role',
		'app.profile',
		'app.file_storage',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'app.gpgkey',
		'app.emailQueue',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	public $user;

	public $session;

	public function setUp() {
		parent::setUp();

		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->Resource = Common::getModel('Resource');
		$this->Permission = Common::getModel('Permission');
		$this->UserResourcePermission = Common::getModel('UserResourcePermission');

		$this->session = new CakeSession();
		$this->session->init();

		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	public function tearDown() {
		// Make sure there is no session active after each test
		parent::tearDown();
		$this->User->setInactive();
	}

	public function testSearchUsersMissingId() {
		$this->setExpectedException('HttpException', "The resource id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/share/searchUsers/resource/", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersInvalidId() {
		$id = 'badId';
		$this->setExpectedException('HttpException', "The resource id is invalid");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('HttpException', "The resource does not exist");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	public function testSearchUsersNotAuthorized() {
		$id = Common::uuid('resource.id.debian');
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		$this->setExpectedException('HttpException', "You are not authorized to share this resource");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

	// Test exclude already granted users
	public function testSearchUsersExcludeAlreadyGrantedUsers() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');
		$this->assertFalse(in_array(Common::uuid('group.id.ada'), $usersIds));
		$groupsIds = Hash::extract($srvResult['body'], '{n}.Group.id');
		$this->assertFalse(in_array(Common::uuid('group.id.board'), $groupsIds));
	}

	// Test filter users by keywords
	public function testSearchUsersFilterUsersByKeywords() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'filter' => array(
					'keywords' => 'carol'
				)
			)
		);

		// Filter on firstname.
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');
		$this->assertTrue(in_array(Common::uuid('user.id.carol'), $usersIds));

		// Filter on lastname.
		$getOptions['data']['filter']['keywords'] = 'shaw';
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');
		$this->assertTrue(in_array(Common::uuid('user.id.carol'), $usersIds));

		// Filter on username.
		$getOptions['data']['filter']['keywords'] = 'carol@passbolt.com';
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');
		$this->assertTrue(in_array(Common::uuid('user.id.carol'), $usersIds));
	}

	// Test filter groups by keywords
	public function testSearchUsersFilterGroupsByKeywords() {
		$id = Common::uuid('resource.id.fsfe');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'filter' => array(
					'keywords' => 'marketing'
				)
			)
		);

		// Filter on group name.
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$groupsIds = Hash::extract($srvResult['body'], '{n}.Group.id');
		$this->assertTrue(in_array(Common::uuid('group.id.marketing'), $groupsIds));
	}

	// Test shouldn't return inactive users.
	public function testSearchUsersExcludeNonActive() {
		$this->User->id = Common::uuid('user.id.carol');
		$fields = $this->User->getFindFields('User::edit', User::get('Role.name'));
		$this->User->save(['active' => 0], false, $fields);

		$id = Common::uuid('resource.id.centos');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);

		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// Betty shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.carol'), $usersIds));
	}

	// Test shouldn't return deleted groups.
	public function testSearchUsersExcludeDeletedGroups() {
		$this->Group->id = Common::uuid('group.id.marketing');
		$this->Group->save(['deleted' => 1], false);

		$id = Common::uuid('resource.id.fsfe');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);

		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$groupsIds = Hash::extract($srvResult['body'], '{n}.Group.id');

		// The group shouldn't be in the list of returned groups.
		$this->assertFalse(in_array(Common::uuid('group.id.marketing'), $groupsIds));
	}

	// Test, as admin, it shouldn't return deleted groups.
	public function testAdminSearchUsersExcludeDeletedGroups() {
		$userD = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->id = $userD['User']['id'];
		$this->User->save(['role_id' => Common::uuid('role.id.admin')], false, ['role_id']);

		$this->Group->id = Common::uuid('group.id.marketing');
		$this->Group->save(['deleted' => 1], false);

		$id = Common::uuid('resource.id.fsfe');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);

		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$groupsIds = Hash::extract($srvResult['body'], '{n}.Group.id');

		// The group shouldn't be in the list of returned groups.
		$this->assertFalse(in_array(Common::uuid('group.id.marketing'), $groupsIds));
	}

	// Test filter by keywords shouldn't return inactive users.
	public function testSearchUsersByKeywordsExcludeNonActiveUsers() {
		$this->User->id = Common::uuid('user.id.edith');
		$this->User->save(['active' => 0], false, ['active']);

		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
			'data' => array(
				'filter' => [
					'keywords' => 'edith'
				]
			)
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// Betty shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.edith'), $usersIds));
	}

	// Test, as admin, it shouldn't return inactive users.
	public function testAdminSearchUsersExcludeNonActiveUsers() {
		$userD = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->id = $userD['User']['id'];
		$this->User->save(['role_id' => Common::uuid('role.id.admin')], false, ['role_id']);
		$userD['User']['role_id'] = Common::uuid('role.id.admin');
		$this->User->setActive($userD);

		$this->User->id = Common::uuid('user.id.carol');
		$this->User->save(['active' => 0], false, ['active']);

		$id = Common::uuid('resource.id.debian');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents',
		);
		$srvResult = json_decode($this->testAction("/share/search-users/resource/$id.json", $getOptions), true);
		$usersIds = Hash::extract($srvResult['body'], '{n}.User.id');

		// The user shouldn't be in the list of returned users.
		$this->assertFalse(in_array(Common::uuid('user.id.carol'), $usersIds));
	}

}
