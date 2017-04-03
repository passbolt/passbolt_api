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
 * Compare the results with what is inside groups_resources_permissions mmatrix.
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
}