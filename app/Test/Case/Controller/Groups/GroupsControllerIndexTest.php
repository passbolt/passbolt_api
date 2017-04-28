<?php
/**
 * Groups Controller Index Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('UsersController', 'Controller');
App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('Role', 'Model');
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('CakeSessionFixture', 'Test/Fixture');

/**
 * Class GroupsControllerTest
 */
class GroupsControllerIndexTest extends ControllerTestCase {

	public $fixtures = array(
		'app.groups_user',
		'app.group',
		'app.user',
		'app.group',
		'app.gpgkey',
		'app.email_queue',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.authenticationToken',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
		'app.resource',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
	);

/**
 * @var options Default request action options for Index method
 */
	protected $options;

/**
 * Setup
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = Common::getModel('Group');
		$this->session = new CakeSession();
		$this->options = ['return' => 'contents', 'method' => 'GET'];
		$this->session->init();
	}

/**
 * Tear down
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

/**
 * Test a call to index without being logged in.
 *
 * @return void
 */
	public function testIndexNoAllowed() {
		$this->User->setInactive();
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		$this->testAction('/groups.json', $this->options, true);
	}

/**
 * Test a call to index.
 *
 * Assert that the call is a success.
 * Assert that the deleted groups are not returned.
 * Assert that each element contains the element Group, UserGroup and User by default.
 *
 * @return void
 */
	public function testIndexSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		$json = json_decode($this->testAction('/groups.json', $this->options, true), true);
		$this->assertEquals($json['header']['status'], Status::SUCCESS, '/groups.json should return success');

		// Extract list of groups.
		$groupIds = Hash::extract($json['body'], '{n}.Group.id');

		// Make sure that the deleted group is not part of the list.
		$deletedGroup = $this->Group->findByDeleted(1);

		// Assert that the deleted group is not visible in the list of groups that are returned.
		$this->assertFalse(in_array($deletedGroup['Group']['id'], $groupIds));

		// Assert that each element includes a UserGroup and a User entry by default.
		$this->assertNotEmpty($json['body'], 'Request should return at least one value');
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group', 'GroupUser']);
		}
	}

/**
 * Test index entry point with contain parameters.
 *
 * Assert that when contain[user] is passed, the output contains for each group a User and GroupUser object
 */
	public function testIndexWithUserContainSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain query string.
		$url = '/groups.json?contain[user]=1';
		$json = json_decode($this->testAction($url, $this->options, true), true);

		// Assert that each element includes a Group, UserGroup and a User.
		$this->assertNotEmpty($json['body'], 'Request should return at least one value');
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group', 'GroupUser']);
		}
	}

/**
 * Test index entry point with contain parameters.
 *
 * Assert that user contain can be disabled, and that when disabled
 * the output doesn't contain neither User nor GroupUser
 */
	public function testIndexWithoutUserContainSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain query string
		$url = "/groups.json?contain[user]=0";
		$json = json_decode($this->testAction($url, $this->options, true), true);

		// Assert that each element includes a Group, UserGroup and a User.
		$this->assertNotEmpty($json['body'], 'Request should return at least one value');
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group']);
		}
	}

/**
 * Test index entry point with contain parameters.
 *
 * Assert that when contain[Modifier] is passed, the output contains for each group a Modifier
 */
	public function testIndexWithModifierContain() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$url = '/groups.json?contain[modifier]=1';
		$res = $this->testAction($url, $this->options);
		$json = json_decode($res, true);

		// Assert that each element includes a Group, UserGroup and a User.
		foreach($json['body'] as $entry) {
			$keys = array_keys($entry);
			$this->assertEquals($keys, ['Group','Modifier','GroupUser']);
		}
	}

/**
 * Test index entry point with "has-users" filter parameters.
 *
 * Assert that each group returned contained the user mentioned in the filter
 */
	public function testIndexWithFilterHasUserSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$url = '/groups.json?filter[has-users]=' . Common::uuid('user.id.irene');
		$res = $this->testAction($url, $this->options);
		$json = json_decode($res, true);
		$this->assertNotEmpty($json['body']);
		$this->assertCount(3, $json['body']);

		// Check if Irene is present in each and every group returned by the query.
		foreach($json['body'] as $jsonGroup) {
			$userIds = Hash::extract($jsonGroup, 'GroupUser.{n}.user_id');
			$this->assertTrue(
				in_array(Common::uuid('user.id.irene'), $userIds),
				'Irene should be found in the list of users for the group'
			);
		}
	}

/**
 * Test index entry point with "has-users" filter parameters and multiple users provided.
 *
 * Assert that each group returned contained both the users mentioned in the filter
 */
	public function testIndexWithFilterHasUserMultipleUsersSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$usersToFind = [Common::uuid('user.id.jean'), Common::uuid('user.id.nancy')];
		$url = '/groups.json?filter[has-users]=' .  $usersToFind[0] . "," . $usersToFind[1];
		$res = $this->testAction($url, $this->options, true);
		$json = json_decode($res, true);
		$this->assertNotEmpty($json['body']);
		$this->assertCount(1, $json['body']);

		// Check if Ada is present in each and every group returned by the query.
		foreach($json['body'] as $jsonGroup) {
			$groupMemberIds = Hash::extract($jsonGroup, 'GroupUser.{n}.user_id');
			foreach($usersToFind as $userToFind) {
				$this->assertContains($userToFind, $groupMemberIds);
			}
		}
	}

/**
 * Test index entry point with "has-users" filter parameters and multiple users provided.
 *
 * Assert that no group are returned if the users are not part of the same groups
 */
	public function testIndexWithFilterHasUserMultipleUsersNotResult() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$url = '/groups.json?filter[has-users]=' . Common::uuid('user.id.frances') . ',' . Common::uuid('user.id.hedy');
		$res = $this->testAction($url, $this->options);
		$json = json_decode($res, true);
		$this->assertEmpty($json['body']);
	}

/**
 * Test index entry point with "has-managers" filter parameters and one manager provided.
 *
 * Assert that each group returned contains the manager mentioned in the filter
 * Assert that each time betty is mentioned, she is the manager of the group.
 */
	public function testIndexWithFilterHasManagerSuccess() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$managerId = Common::uuid('user.id.ping');
		$url = '/groups.json?filter[has-managers]=' . $managerId;
		$res = $this->testAction($url, $this->options, true);
		$json = json_decode($res, true);
		$this->assertNotEmpty($json['body']);
		$this->assertCount(2, $json['body']);

		// Check if the user is present in each and every group returned by the query,
		// and if she is a manager for all the groups returned.
		foreach($json['body'] as $jsonGroup) {
			$userIds = Hash::extract($jsonGroup, 'GroupUser.{n}.user_id');
			$this->assertContains($managerId, $userIds);
			foreach($jsonGroup['GroupUser'] as $groupUser) {
				if ($groupUser['user_id'] == $managerId) {
					$this->assertTrue($groupUser['is_admin'], 1);
				}
			}
		}
	}

/**
 * Test index fails with wrong "has-users" filter parameters.
 */
	public function testIndexFailsWithHasUsersNotUuidFilters() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'Invalid filter. "notuuid" is not a valid user id for filter has-users.');
		$url = '/groups.json?filter[has-users]=notuuid';
		$this->testAction($url, $this->options);
	}

/**
 * Test index fails with one good and one wrong "has-users" filter parameters.
 */
	public function testIndexFailsWithOneGoodAndWrongHasUsersFilters() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'Invalid filter. "notuuid" is not a valid user id for filter has-users.');
		$url = '/groups.json?filter[has-users]=' . Common::uuid('user.id.betty') . ',notuuid';
		$this->testAction($url, $this->options);
	}

/**
 * Test index fails with wrong "has-manager" filter parameters.
 */
	public function testIndexFailsWithWrongHasManagersFilters() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'Invalid filter. "notuuid" is not a valid user id for filter has-managers.');
		$url = '/groups.json?filter[has-managers]=notuuid';
		$this->testAction($url, $this->options);
	}

/**
 * Test index fails with one good and one and one wrong "has-users" filter parameters.
 */
	public function testIndexFailsWithOneGoodAndWrongHasManagersFilters() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'Invalid filter. "notuuid" is not a valid user id for filter has-managers.');
		$usersToFind = [Common::uuid('user.id.betty'), 'notuuid'];
		$url = '/groups.json?filter[has-managers]=' . $usersToFind[0] . "," . $usersToFind[1];
		$this->testAction($url, $this->options);
	}

/**
 * Test index fails with wrong group name order
 */
	public function testIndexFailsWithWrongOrder() {
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);
		$this->setExpectedException('BadRequestException', 'javascript:alert("ok");" is not a valid order.');
		$url = '/groups.json?order=javascript:alert("ok");';
		$this->testAction($url, $this->options);
	}
}