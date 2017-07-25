<?php
/**
 * This class has for aim to test the user model find functions.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.UserTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class UserFindFilterTest extends CakeTestCase {

	public $fixtures = array(
		'app.group',
		'app.groups_user',
		'app.user',
		'app.resource',
		'app.profile',
		'app.file_storage',
		'app.gpgkey',
		'app.role',
		'core.cakeSession',
		'app.permissionsType',
		'app.permission',
		'app.permission_view',
		'app.authentication_token',
		'app.controller_log'
	);

	public $autoFixtures = true;

	/**
	 * Setup
	 *
	 * @return void
	 */
	public function setup() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Permission = ClassRegistry::init('Permission');
	}

	/*****************************
	 * is-active filter
	 *****************************/

	// As LU I should not be able to filter on active field. By default only active users are returned
	public function testLUShouldntFindInactiveUsers() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		// Filtering on inactive users shouldn't return the inactive users but all active users
		$findData = ['filter' => ['is-active' => false]];
		$findOptions = $this->User->getFindOptions('User::index', User::get('Role.name'), $findData);
		$users = $this->User->find('all', $findOptions);
		$usersIds = Hash::extract($users, "{n}.User.id");
		$this->assertContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertNotContains(Common::uuid('user.id.orna'), $usersIds);
	}

	// As LU I should be able to filter on active field. By default only active users are returned
	public function testADFindActiveUsers() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Filtering on inactive users shouldn't return the inactive users but all active users
		$findData = ['filter' => ['is-active' => true]];
		$findOptions = $this->User->getFindOptions('User::index', User::get('Role.name'), $findData);
		$users = $this->User->find('all', $findOptions);
		$usersIds = Hash::extract($users, "{n}.User.id");
		$this->assertContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertNotContains(Common::uuid('user.id.orna'), $usersIds);
	}

	// As LU I should be able to filter on active field. By default only active users are returned
	public function testADFindInactiveUsers() {
		$user = $this->User->findById(Common::uuid('user.id.admin'));
		$this->User->setActive($user);

		// Filtering on inactive users shouldn't return the inactive users but all active users
		$findData = ['filter' => ['is-active' => false]];
		$findOptions = $this->User->getFindOptions('User::index', User::get('Role.name'), $findData);
		$users = $this->User->find('all', $findOptions);
		$usersIds = Hash::extract($users, "{n}.User.id");
		$this->assertNotContains(Common::uuid('user.id.ada'), $usersIds);
		$this->assertContains(Common::uuid('user.id.orna'), $usersIds);
	}

}
