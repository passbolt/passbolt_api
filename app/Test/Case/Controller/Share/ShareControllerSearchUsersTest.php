<?php
/**
 * Share Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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

/**
 * Test fails if aco id is missing
 */
	public function testSearchUsersMissingId() {
		$this->setExpectedException('BadRequestException', "The aco id is missing");
		// go through the addAcoPermissions because of routes
		$this->testAction("/share/searchUsers/resource/", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test fails if aco id is not valid uuid
 */
	public function testSearchUsersInvalidId() {
		$id = 'badId';
		$this->setExpectedException('BadRequestException', "The aco id is not valid");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test fails if aco id does not exist
 */
	public function testSearchUsersDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', "The aco id does not exist for model resource.");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test fails if user does not have right to share this item
 */
	public function testSearchUsersNotAuthorized() {
		$id = Common::uuid('resource.id.debian');
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('ForbiddenException', "You are not authorized to share this.");
		$this->testAction("/share/search-users/resource/$id.json", array('method' => 'get', 'return' => 'contents'));
	}

/**
 * Test exclude already granted users
 */
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

/**
 * Test filter users by keywords (first name, last name and emails)
 */
	public function testSearchUsersFilterUsersByKeywords() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array('method' => 'get', 'return' => 'contents');

		// Filter on firstname.
		$url = "/share/search-users/resource/$id.json?filter[keywords]=carol";
		$srvResult = json_decode($this->testAction($url, $getOptions), true);
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

/**
 * Test filter users by keywords (legacy)
 */
	public function testSearchUsersFilterUsersByLegacyKeywords() {
		$id = Common::uuid('resource.id.debian');
		$getOptions = array('method' => 'get', 'return' => 'contents');

		// Filter on firstname.
		$url = "/share/search-users/resource/$id.json?keywords=carol";
		$srvResult = json_decode($this->testAction($url, $getOptions), true);
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

/**
 * Test filter groups by keywords
 */
	public function testSearchUsersFilterGroupsByKeywords() {
		$id = Common::uuid('resource.id.fsfe');
		$getOptions = array(
			'method' => 'get',
			'return' => 'contents'
		);

		// Filter on group name.
		$url = "/share/search-users/resource/$id.json?filter[keywords]=marketing";
		$srvResult = json_decode($this->testAction($url, $getOptions), true);
		$groupsIds = Hash::extract($srvResult['body'], '{n}.Group.id');
		$this->assertTrue(in_array(Common::uuid('group.id.marketing'), $groupsIds));
	}

/**
 * Test search shouldn't return inactive users.
 */
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

/**
 * Test search should not return deleted groups.
 */
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

/**
 * Test search as admin I also should not see deleted groups.
 */
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

/**
 * Test filter by keywords should not return inactive users.
 */
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

/**
 * Test search as admin, it shouldn't return inactive users.
 */
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