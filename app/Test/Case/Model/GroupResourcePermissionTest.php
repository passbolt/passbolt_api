<?php
/**
 * GroupResource Permission Test
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @package       app.Test.Case.Model.GroupResourcePermissionTest
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('GroupResourcePermission', 'Model');
App::uses('PermissionMatrix', 'DataSeleniumTests.Data');

if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GroupResourcePermissionTest extends CakeTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.profile',
		'app.file_storage',
		'app.role',
		'app.group',
		'app.groupsUser',
		'app.permissionsType',
		'app.permission',
		'app.permission_view',
		'app.gpgkey',
		'core.cakeSession'
	);

/**
 * Setup.
 */
	public function setUp() {
		parent::setUp();
		$this->User = Common::getModel('User');
		$this->Group = ClassRegistry::init('Group');
		$this->Resource = ClassRegistry::init('Resource');
		$this->Permission = ClassRegistry::init('Permission');
		$this->GroupResourcePermission = ClassRegistry::init('GroupResourcePermission');
		$this->PermissionType = ClassRegistry::init('PermissionType');

		$this->User->get();
		$this->session = new CakeSession();
		$this->session->init();
	}

/**
 * Teardown.
 */
	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}


/**
 * Test function findAuthorizedResources().
 *
 * Compare the results with what is inside groups_resources_permissions matrix.
 */
	public function testFindAuthorizedResources() {

		// Log in Irene.
		// findAuthorizedResources will not return a valid result if
		// the call is made by a non member of the group.
		$user = $this->User->findById(Common::uuid('user.id.irene'));
		$this->User->setActive($user);

		$resources = $this->GroupResourcePermission->findAuthorizedResources(
			Common::uuid('group.id.developer')
		);

		// Build an array of obtained resources id.
		$resources = Hash::extract($resources, '{n}.Resource.id');

		// Get permission matrix for groups.
		$matrixPath = App::pluginPath('DataSeleniumTests') . '/Data/groups_resources_permissions.csv';
		$matrix = PermissionMatrix::importCsv($matrixPath, 'resource');

		// Build an array of expected resources id.
		$expectedResources = [];
		foreach ($matrix as $resourceAlias => $groupPermissions) {
			foreach ($groupPermissions as $groupAlias => $permission) {
				if ($groupAlias == 'developer' && $permission != '0') {
					$expectedResources[] = Common::uuid('resource.id.' . $resourceAlias);
				}
			}
		}

		// Sort the 2 arrays alphabetically.
		sort($resources);
		sort($expectedResources);

		// Assert that the 2 arrays are same.
		$this->assertEquals($resources, $expectedResources);
	}

/**
 * Test FindUnauthorizedResources() with a permission type.
 *
 * Compare the results with what should be returned according to the permission matrix.
 */
	public function testFindAuthorizedResourcesWithPermission() {
		// Log in Irene.
		// findAuthorizedResources will not return a valid result if
		// the call is made by a non member of the group.
		$user = $this->User->findById( Common::uuid( 'user.id.irene' ) );
		$this->User->setActive( $user );

		$resources = $this->GroupResourcePermission->findAuthorizedResources(
			Common::uuid( 'group.id.developer' ),
			PermissionType::OWNER
		);

		// Build an array of obtained resources id.
		$resourceIds = Hash::extract( $resources, '{n}.Resource.id' );

		// Expected groups
		$expectedResourceIds = [Common::uuid('resource.id.cakephp'), Common::uuid('resource.id.enlightenment'), Common::uuid('resource.id.grogle')];

		foreach($resourceIds as $resourceId) {
			$this->assertTrue(in_array($resourceId, $expectedResourceIds), 'The group returned is not part of the groups expected');
		}

		// Assert that the same number of elements as the number expected is returned.
		$this->assertEquals(count($resourceIds), count($expectedResourceIds));
	}



/**
 * Test FindUnauthorizedResourcesForUsers().
 *
 * Compare the results with what should be returned according to the permission matrix.
 */
	public function testFindUnauthorizedResourcesForUsers() {
		$user = $this->User->findById(Common::uuid('user.id.hedy'));
		$this->User->setActive($user);


		///// TEST 1: test for board group and user betty //////
		$groupId = Common::uuid('group.id.board');
		$userIds = [
			Common::uuid('user.id.grace'),
		];

		// board can access to: cakephp, chai, composer, debian, enlightenment, fosdem, framasoft, fsfe, grogle, grunt, gnupg, git.
		// grace can access to: cakephp, composer, debian, docker, enlightenment, framasoft, fsfe, ftp, grogle, gnupg, git, inkscape.
		// the 3 secrets which grace can't access should be: chai, fosdem, and grunt.

		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIds);
		$resourceIds = Hash::extract($resources[Common::uuid('user.id.grace')], '{n}.Resource.id');

		// List of expected resources.
		$expectedResourcesGrace = [
			Common::uuid('resource.id.chai'),
			Common::uuid('resource.id.fosdem'),
			Common::uuid('resource.id.grunt'),
		];

		$this->assertEquals(count($expectedResourcesGrace), count($resourceIds));
		foreach($expectedResourcesGrace as $expectedResource) {
			$this->assertTrue(in_array($expectedResource, $resourceIds));
		}

		///// TEST 2: test for board group and user frances //////
		$userIds = [
			Common::uuid('user.id.frances'),
		];

		// board can access to: cakephp, chai, composer, debian, enlightenment, fosdem, framasoft, fsfe, grogle, grunt, gnupg, git.
		// frances can access to: nothing
		// the secrets which frances can't access to should be the same as groups

		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIds);
		$resourceIds = Hash::extract($resources[Common::uuid('user.id.frances')], '{n}.Resource.id');

		// List of expected resources.
		$expectedResourcesFrances = [
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

		$this->assertEquals(count($expectedResourcesFrances), count($resourceIds));
		foreach($expectedResourcesFrances as $expectedResource) {
			$this->assertTrue(in_array($expectedResource, $resourceIds));
		}

		///// TEST 3: test for board group and multiple users frances and grace //////
		$userIds = [
			Common::uuid('user.id.frances'),
			Common::uuid('user.id.grace'),
		];
		$resources = $this->GroupResourcePermission->findUnauthorizedResourcesForUsers($groupId, $userIds);
		$this->assertEquals(count($resources), count($userIds));

		$resourceIds = array_merge(
			Hash::extract($resources[Common::uuid('user.id.frances')], '{n}.Resource.id'),
			Hash::extract($resources[Common::uuid('user.id.grace')], '{n}.Resource.id')
		);

		// The count of the result should be equal to the number of items expected for jean and for Irene (see above).
		$this->assertEquals(count($expectedResourcesFrances) + count($expectedResourcesGrace), count($resourceIds));
	}

/**
 * Test findSoleOwnerResources().
 */
	public function testFindSoleOwnerResources() {
		$groupId = Common::uuid('group.id.board');

		// 1) test that by default the group is not the sole owner of any resource.
		$soleOwnerResources = $this->GroupResourcePermission->findSoleOwnerResources($groupId);
		$this->assertEmpty($soleOwnerResources);

		// 2) Add a permission that sets him as the sole owner of a resource.
		// Save a resource.
		$this->Resource->create();
		// Unload permissionable behavior, so it will not create an additional permission while saving.
		$this->Resource->Behaviors->unload('Permissionable');
		$resource = $this->Resource->save([
			'name' => 'resource-test'
		]);

		// Set permission for this resource.
		$this->Permission->create();
		$this->Permission->save([
				'aco' => 'Resource',
				'aco_foreign_key' => $resource['Resource']['id'],
				'aro' => 'Group',
				'aro_foreign_key' => $groupId,
				'type' => PermissionType::OWNER
			]);

		$soleOwnerResources = $this->GroupResourcePermission->findSoleOwnerResources($groupId);
		$this->assertNotEmpty($soleOwnerResources);

		// Assert that the resource corresponds to what we expect.
		$this->assertEquals($soleOwnerResources[0]['Resource']['id'], $resource['Resource']['id']);
	}
}