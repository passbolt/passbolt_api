<?php
/**
 * Trackable Behavior Test
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('User', 'Model');

/**
 * Class TrackedModel
 * Mocked model to test the trackableBehavior.
 */
class TrackedModel extends AppModel {

	/**
	 * Model behaviors
	 *
	 * @link http://api20.cakephp.org/class/model#
	 */
	public $actsAs = [ 'Trackable' ];
}

class TrackableTest extends CakeTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.secret',
		'app.file_storage',
		'app.group',
		'app.groups_user',
		'app.permissions_type',
		'app.permission',
		'app.permission_view',
		'app.trackedModel',
		'core.cakeSession',
	);

	public function setUp() {
		parent::setUp();
		$this->User     = ClassRegistry::init( 'User' );
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}

	/**
	 * Create an entry and test that created_by is populated correctly.
	 *
	 * Assert that the value of created_by should be equal to the logged in user uuid.
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testCreateDefault() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$model = $trackedModel->save([
			'example_field' => 1
		]);

		$model = $trackedModel->findById($model['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $adaId);
	}

	/**
	 * Update an entry and test that modified_by is populated correctly.
	 *
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testUpdateDefault() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$modelAfterSave = $trackedModel->save([
			'example_field' => 1
		]);

		$this->User->setInactive();
		$carolId = Common::uuid('user.id.carol');
		$user = $this->User->findById($carolId);
		$this->User->setActive($user);
		// Update.
		$trackedModel->id = $modelAfterSave['TrackedModel']['id'];
		$trackedModel->save([
			'example_field' => 0
		]);

		$model = $trackedModel->findById($modelAfterSave['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $carolId);
	}

	/**
	 * Create an entry while passing a fieldList parameter and test that created_by is populated correctly.
	 *
	 * Assert that the value of created_by should be equal to the logged in user uuid.
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testCreateWithFieldList() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$model = $trackedModel->save(
			[ 'example_field' => 1 ],
			true,
			[ 'example_field' ]
		);

		$model = $trackedModel->findById($model['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $adaId);
	}

	/**
	 * Update an entry while passing a fieldList parameter and test that modified_by is populated correctly.
	 *
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testUpdateWithFieldList() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$modelAfterSave = $trackedModel->save([
			'example_field' => 1
		]);

		$this->User->setInactive();
		$carolId = Common::uuid('user.id.carol');
		$user = $this->User->findById($carolId);
		$this->User->setActive($user);
		// Update.
		$trackedModel->id = $modelAfterSave['TrackedModel']['id'];
		$trackedModel->save(
			[ 'example_field' => 0 ],
			true,
			[ 'example_field' ]
		);

		$model = $trackedModel->findById($modelAfterSave['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $carolId);
	}

	/**
	 * Create an entry while passing created_by in the fieldList parameter with debug mode activated and test that the created_by field is overriden to the one given.
	 *
	 * Assert that the value of created_by should be equal to the one provided in the params.
	 * Assert that the value of modified_by should be equal to the one provided in the params.
	 */
	public function testCreateFieldOverridenOnDebug() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$testUuid = Common::uuid('user.id.test');

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$model = $trackedModel->save(
			[ 'example_field' => 1, 'created_by' => $testUuid ]
		);

		$model = $trackedModel->findById($model['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $testUuid);
	}

	/**
	 * Create an entry while passing modified_by in the fieldList parameter with debug mode activated and test that the modified_by field is overriden to the one given.
	 *
	 * Assert that the value of modified_by should be equal to the one provided in the params.
	 */
	public function testUpdateFieldOverridenOnDebug() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$modelAfterSave = $trackedModel->save([
			'example_field' => 1
		]);

		$testUuid = Common::uuid('user.id.test');
		$this->User->setInactive();
		$carolId = Common::uuid('user.id.carol');
		$user = $this->User->findById($carolId);
		$this->User->setActive($user);
		// Update.
		$trackedModel->id = $modelAfterSave['TrackedModel']['id'];
		$trackedModel->save(
			[ 'example_field' => 0, 'modified_by' => $testUuid ]
		);

		$model = $trackedModel->findById($modelAfterSave['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $testUuid);
	}

	/**
	 * Create an entry while passing created_by in the fieldList parameter with debug mode set to off and test that the created_by field is not overriden by the one given.
	 *
	 * Assert that the value of created_by should be equal to the logged in user uuid.
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testCreateFieldOverridenWithoutDebug() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$testUuid = Common::uuid('user.id.test');

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$initialDebugState = Configure::read('debug');
		Configure::write('debug', 0);
		$model = $trackedModel->save(
			[ 'example_field' => 1, 'created_by' => $testUuid ]
		);
		Configure::write('debug', $initialDebugState);

		$model = $trackedModel->findById($model['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
	}

	/**
	 * Update an entry while passing modified_by in the fieldList parameter with debug mode set to off and test that the modified_by field is not overriden by the one given.
	 *
	 * Assert that the value of modified_by should be equal to the logged in user uuid.
	 */
	public function testUpdateFieldOverridenWithoutDebug() {
		$adaId = Common::uuid('user.id.ada');
		$user = $this->User->findById($adaId);
		$this->User->setActive($user);

		$trackedModel = ClassRegistry::init('TrackedModel');
		$trackedModel->create();
		$modelAfterSave = $trackedModel->save([
			'example_field' => 1
		]);

		$testUuid = Common::uuid('user.id.test');
		$this->User->setInactive();
		$carolId = Common::uuid('user.id.carol');
		$user = $this->User->findById($carolId);
		$this->User->setActive($user);
		// Update.
		$trackedModel->id = $modelAfterSave['TrackedModel']['id'];
		$initialDebugState = Configure::read('debug');
		Configure::write('debug', 0);
		$trackedModel->save(
			[ 'example_field' => 0, 'modified_by' => $testUuid ]
		);
		Configure::write('debug', $initialDebugState);

		$model = $trackedModel->findById($modelAfterSave['TrackedModel']['id']);
		$this->assertEquals($model['TrackedModel']['created_by'], $adaId);
		$this->assertEquals($model['TrackedModel']['modified_by'], $carolId);
	}
}