<?php
/**
 * Resources Controller Tests
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('CakeSession', 'Model');
App::uses('CakeSession', 'Model/Datasource');
App::uses('AppController', 'Controller');
App::uses('ResourcesController', 'Controller');
App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('PermissionMatrix', 'DataTests.Data');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class ResourcesControllerIndexTest extends ControllerTestCase
{

	public $fixtures = array(
		'app.resource',
		'app.secret',
		'app.favorite',
		'app.log',
		'app.user',
		'app.gpgkey',
		'app.profile',
		'app.file_storage',
		'app.group',
		'app.groupsUser',
		'app.role',
		'app.gpgkey',
		'app.email_queue',
		'app.permission',
		'app.permissions_type',
		'app.permission_view',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

	/**
	 * @var options Default request action options for Index method
	 */
	protected $options;

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->Resource = ClassRegistry::init('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->options = ['return' => 'contents', 'method' => 'GET'];
		$this->User->setActive($user);
	}

	public function testIndexAndPermission() {
		$matrixPath = TESTS . '/Data/view_users_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'user');

		foreach ($matrix as $userAlias => $expectedPermissions) {
			$userId = Common::uuid('user.id.' . $userAlias);
			$user = $this->User->findById($userId);
			$this->User->setActive($user);

			$url = '/resources/index.json';
			$result = json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
			$this->assertEquals(Status::SUCCESS, $result['header']['status']);

			foreach ($expectedPermissions as $resourceAlias => $expectedPermission) {
				$resourceId = Common::uuid('resource.id.' . $resourceAlias);
				$path = $this->Resource->inNestedArray($resourceId, $result['body'], 'id');

				// If the user shouldn't be allowed to access the resource.
				if ($expectedPermission == PermissionType::DENY) {
					$this->assertTrue(empty($path),
						"{$url} : test should not contain '{$resourceAlias}' resource with user '{$userAlias}'");
				}
				// If the user should be allowed to access the resource.
				else {
					$this->assertTrue(!empty($path),
						"{$url} : test should contain '{$resourceAlias}' resource with user '{$userAlias}'");
				}
			}
		}
	}

	public function testIndexSuccess() {
		$url = '/resources/index.json';
		$result = json_decode($this->testAction($url, array('return' => 'contents', 'method' => 'get')), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"{$url} : The test should return a success but is returning {$result['header']['status']}");
		$this->assertTrue(!empty($result['body']), "{$url} : should contain result");
	}


	/**
	 * Test index entry point with "is-shared-with-group" filter parameter and one group provided.
	 *
	 * Assert that each resource returned belongs to the given group.
	 */
	public function testIndexWithFilterIsSharedWithGroup() {
		// test with normal user
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);

		// Call to entry point with contain params.
		$groupId = Common::uuid('group.id.board');
		$url = '/resources.json?filter[is-shared-with-group]=' .  $groupId;
		$res = $this->testAction($url, $this->options, true);
		$json = json_decode($res, true);

		// List of expected resource Ids.
		// Note that by default Hedy has access to more resources. So by filtering the groups resources
		// only, there should be less resources.
		$expectedResourceIds = [
			Common::uuid('resource.id.cakephp'),
			Common::uuid('resource.id.chai'),
			Common::uuid('resource.id.composer'),
			Common::uuid('resource.id.debian'),
			Common::uuid('resource.id.enlightenment'),
			Common::uuid('resource.id.fosdem'),
			Common::uuid('resource.id.framasoft'),
			Common::uuid('resource.id.fsfe'),
			Common::uuid('resource.id.grogle'),
			Common::uuid('resource.id.grunt'),
			Common::uuid('resource.id.gnupg'),
			Common::uuid('resource.id.git'),
		];

		$this->assertNotEmpty($json['body']);
		$this->assertCount(count($expectedResourceIds), $json['body']);

		// Makes a call without the parameter and assert that the result is different.
		$url = '/resources.json';
		$res = $this->testAction($url, $this->options, true);
		$jsonWithoutFilter = json_decode($res, true);
		$this->assertNotEquals(count($jsonWithoutFilter['body']), count($json['body']));
	}

	/**
	 * Test index entry point with invalid "is-shared-with-group" filter parameter
	 *
	 * Assert that each resource returned belongs to the given group.
	 */
	public function testIndexWithFilterIsSharedWithGroupInvalidParameter() {
		// test with normal user
		$user = $this->User->findById( Common::uuid( 'user.id.hedy' ) );
		$this->User->setActive( $user );

		// Call to entry point with contain params.
		$groupId = Common::uuid( 'group.id.board' );
		$url     = '/resources.json?filter[is-shared-with-group]=' . 'xxx-xxx-xxx';
		$this->setExpectedException('BadRequestException', 'Invalid filter. "xxx-xxx-xxx" is not a valid group id for filter is-shared-with-group.');
		$this->testAction( $url, $this->options, true );
	}
}
