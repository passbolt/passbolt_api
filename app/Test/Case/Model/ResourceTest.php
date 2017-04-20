<?php
/**
 * Resource Model Test
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Resource', 'Model');
App::uses('Secret', 'Model');
App::uses('AppTestCase', 'Test');

class ResourceTest extends AppTestCase {

	public $fixtures = array(
		'app.resource',
		'app.user',
		'app.role',
		'app.secret',
		'app.profile',
		'app.gpgkey',
		'app.file_storage',
		'app.groupsUser',
		'app.group',
		'app.permissionsType',
		'app.permission',
		'app.permission_view',
		'core.cakeSession',
	);

	public function setUp() {
		parent::setUp();
		$this->Resource = ClassRegistry::init('Resource');
		$this->User = ClassRegistry::init('User');
	}

	public function tearDown() {
		parent::tearDown();
		// Make sure there is no session active after each test
		$this->User->setInactive();
	}
/**
 * Test Name Validation
 *
 * @return void
 */
	public function testNameValidation() {
		$len = 64;
		$testcases = array(
			// Not empty
			'' => false,
			// Email
			'test@test.com' => false,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => true,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => true,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => true,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => true,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => true,
			// Spaces
			'txt with spaces' => true,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => true,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('name' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource name with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the resource name with "' . $testcase . '" should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('name'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 *
 * @return void
 */
	public function testUsernameValidation() {
		$len = 64;
		$testcases = array(
			// Empty
			'' => true,
			// Email are not accepted
			'test@test.com' => true,
			// too short
			'sh' => false,
			// too long
			'toolong' . self::randString($len - 6, self::getMask('alphaASCII')) => false,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => false,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => false,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => false,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => false,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => false,
			// Spaces
			'txt with spaces' => false,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => false,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('username' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource username with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource username with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('username'))), $result, $msg);
		}
	}

/**
 * Test Username Validation
 *
 * @return void
 */
	public function testUriValidation() {
		// Quick test, the uri validator is part of the Cake core.
		$testcases = array(
			'' => true,
			't' => false,
			'?!#' => true,
			'test' => true,
			'test@test.com' => true,
			'test<' => false,
			'http://www.passbolt.com' => true,
			'192.168.10.3' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('uri' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource uri with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource uri with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('uri'))), $result, $msg);
		}
	}

/**
 * Test expiry Date Validation
 *
 * @return void
 */
	public function testExpiryDateValidation() {
		$testcases = array(
			'' => true,
			'14 Decembre 1980' => false,
			'27-12-2006' => false,
			'2006-14-12' => false,
			'2024-12-24' => true
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('expiry_date' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource expiry date with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource expiry date with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('expiry_date'))), $result, $msg);
		}
	}

/**
 * Test Description Validation
 *
 * @return void
 */
	public function testDescriptionValidation() {
		$len = 255;
		$testcases = array(
			// Not empty
			'' => true,
			// Email are not accepted
			'test@test.com' => true,
			// too short
			'sh' => true,
			// Short but enough
			'sho' => true,
			// Long but not too long
			'long' . self::randString($len - 4, self::getMask('alphaASCII')) => true,
			// Languages
			'ASCII' . self::randString($len - 5, self::getMask('alphaASCII')) => true,
			'ASCIIUPPER' . self::randString($len - 10, self::getMask('alphaASCIIUpper')) => true,
			'ACCENT' . self::randString($len - 6, self::getMask('alphaAccent')) => true,
			'LATIN' . self::randString($len - 5, self::getMask('alphaLatin')) => true,
			'CHINESE' . self::randString($len - 7, self::getMask('alphaChinese')) => true,
			'ARABIC' . self::randString($len - 6, self::getMask('alphaArabic')) => true,
			'RUSSIAN' . self::randString($len - 7, self::getMask('alphaRussian')) => true,
			// Spaces
			'txt with spaces' => true,
			"txt\twith\ttabs" => false,
			"txt\nwith\nnew\nlines" => false,
			// Special characters
			',.-_([)]\'' => true,
			'?!#' => false,
			// Digit accepted
			'0123456789' => true,
			// Html
			'<strong>test</strong>' => false,
			// Too long
			'TOOLONG' . self::randString(100000, self::getMask('alphaASCII')) => false,
		);
		foreach ($testcases as $testcase => $result) {
			$resource = array('Resource' => array('description' => $testcase));
			$this->Resource->set($resource);
			if ($result) {
				$msg = 'validation of the resource description with ' . $testcase . ' should validate';
			} else {
				$msg = 'validation of the resource description with ' . $testcase . ' should not validate';
			}
			$this->assertEquals($this->Resource->validates(array('fieldList' => array('description'))), $result, $msg);
		}
	}

	/**
	 * Test GetFindFields
	 */
	public function testGetFindFields() {
		$default = ['fields' => []];
		$this->assertNotEquals($default, Resource::getFindFields('view'), 'Find fields missing for view');
		$this->assertNotEquals($default, Resource::getFindFields('index'), 'Find fields missing for index');
		$this->assertNotEquals($default, Resource::getFindFields('delete'), 'Find fields missing for delete');
		$this->assertNotEquals($default, Resource::getFindFields('Resource::edit'), 'Find fields missing for delete');
		$this->assertNotEquals($default, Resource::getFindFields('save'), 'Find fields missing for delete');
		$this->assertNotEquals($default, Resource::getFindFields('delete'), 'Find fields missing for delete');

		$this->assertEquals($default, Resource::getFindFields('rubish'), 'Find fields should be empty for wrong find');
	}

	/**
	 * Test GetFindFields
	 */
	public function testGetFindConditions() {
		$default = ['conditions' => []];
		$this->assertNotEquals($default, Resource::getFindConditions('add'), 'Find conditions missing for add');
		$this->assertNotEquals($default, Resource::getFindConditions('edit'), 'Find conditions missing for edit');
		$this->assertNotEquals($default, Resource::getFindConditions('view'), 'Find conditions missing for view');
		$this->assertNotEquals($default, Resource::getFindConditions('index'), 'Find conditions missing for index');

		// Default conditions
		$conditions = ['conditions' => ['Resource.deleted' => 0]];
		// filter cases checks
		$cases = ['favorite', 'own', 'shared'];
		foreach($cases as $case) {
			$this->assertNotEquals($conditions, Resource::getFindConditions('index', Role::USER, ['case' => $case]),
				'Find conditions missing for index case ' . $case);
		}
		// search by keyword
		$this->assertNotEquals($conditions, Resource::getFindConditions('index', Role::USER, ['keywords' => 'one or two']),
				'Find conditions missing for index by keywords');
		// order cases checks
		$cases = ['modified', 'expiry_date'];
		foreach($cases as $case) {
			$this->assertNotEquals($conditions, Resource::getFindConditions('index', Role::USER, ['order' => $case]),
					'Find conditions missing for index case ' . $case);
		}

		$this->assertEquals($default, Resource::getFindConditions('rubish'), 'Find conditions should be empty for wrong find');
	}

	/**
	 * Test that when soft delete a resource it is still in db and his permissions have been revoked
	 */
	public function testSoftDelete() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$resourceId = Common::uuid('resource.id.apache');
		$this->Resource->softDelete($resourceId);

		// Resource deleted field should be set at true
		$resourceF = $this->Resource->findById($resourceId);
		$this->assertEqual($resourceF['Resource']['deleted'], 1);

		// The resource should has been marked as soft deleted
		$this->assertTrue($this->Resource->isSoftDeleted($resourceId));
	}

	/**
	 * Test the softDeleted function
	 */
	public function testIsSoftDeleted() {
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);

		$id = Common::uuid('not-valid-reference');
		$this->assertTrue($this->Resource->isSoftDeleted($id));

		$id = Common::uuid('resource.id.apache');
		$this->assertFalse($this->Resource->isSoftDeleted($id));

		// test with empty id
		$this->assertFalse($this->Resource->isSoftDeleted());

		// Soft delete the resource
		$this->Resource->softDelete($id);
		// The resource should has been marked as soft deleted
		$this->assertTrue($this->Resource->isSoftDeleted($id));
	}

	/*****************************
	 * SAVE SECRETS
	 *****************************/
	/**
	 * Test save a list of secrets corresponding to a resource.
	 */
	public function testSaveSecretsEmptySecrets() {
		$this->setExpectedException('Exception', 'The list of secrets provided is invalid');
		$this->Resource->saveSecrets(Common::uuid(), []);
	}

	/**
	 * Test save a list of secrets with a missing user
	 */
	public function testSaveSecretsNotEnoughUsers() {
		// As Ada
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// I access a resource that is shared with multiple users
		$conditions = ['conditions' => ['name' => 'apache'], 'contain' => ['Secret']];
		$resource = $this->Resource->find('first', $conditions);

		// And I try to save the same secret but with a missing user
		// It should throw an exception
		unset($resource['Secret'][3]);
		$this->setExpectedException('Exception', 'The list of secrets provided is invalid');
		$this->Resource->saveSecrets($resource['Resource']['id'], $resource['Secret']);
	}

	/**
	 * Test save a list of secrets that cannot validate
	 */
	public function testSaveSecretsInvalidate() {
		// As Ada
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// I access a resource that is shared with multiple users
		$conditions = ['conditions' => ['name' => 'apache'], 'contain' => ['Secret']];
		$resource = $this->Resource->find('first', $conditions);

		// And I try to save the same secrets but with one missing some data
		// It should throw an exception
		unset($resource['Secret'][3]['resource_id']);
		$resource['Secret'][3]['data'] = 'bugs gpg data';
		$this->setExpectedException('ValidationException', 'Could not validate secret model');
		$this->Resource->saveSecrets($resource['Resource']['id'], $resource['Secret']);
	}

	/**
	 * Test save a list of secrets success
	 */
	public function testSaveSecretsSuccess() {
		// As Ada
		$user = $this->User->findById(Common::uuid('user.id.ada'));
		$this->User->setActive($user);

		// I access a resource named cpp1-pwd1 that is shared with
		// Ada, Dame, Lynne = Owner
		// Jean = read
		$conditions = ['conditions' => ['name' => 'apache'], 'contain' => ['Secret']];
		$resource = $this->Resource->find('first', $conditions);

		// And I try to save the same secrets but with one missing some data
		// It should save alright
		$result = $this->Resource->saveSecrets($resource['Resource']['id'], $resource['Secret']);
		$this->assertTrue($result, 'TestSaveSecret results should be true');
	}
}
