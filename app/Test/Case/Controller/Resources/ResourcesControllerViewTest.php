<?php
/**
 * Resources Controller View Tests
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
App::uses('PermissionMatrix', 'DataSeleniumTests.Data');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class ResourcesControllerViewTest extends ControllerTestCase
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

	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->Resource = ClassRegistry::init('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

/**
 * Test view resour return bad request if resource id is not valid
 */
	public function testViewResourceIdNotValid() {
		// test an error bad id
		$this->setExpectedException('BadRequestException', 'The resource id is not valid.');
		$this->testAction("/resources/badid.json?children=true", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

/**
 * Test view return not found if resource does not exist
 */
	public function testViewResourceDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$result = json_decode($this->testAction("/resources/{$id}.json", array(
			'method' => 'get',
			'return' => 'contents'
		)), true);
	}

/**
 * Test view return not found if user does not have permission to see the resource
 */
	public function testViewAndPermission() {
		$resId = Common::uuid('resource.id.canjs');
		$this->setExpectedException('NotFoundException', 'The resource does not exist.');
		$this->testAction("/resources/{$resId}.json", array(
			'method' => 'get',
			'return' => 'contents'
		));
	}

/**
 * Test deleting the resource and getting a notfoundexception on a view afterwards
 */
	public function testViewDeletedResource() {
		$rsId = Common::uuid('resource.id.debian');

		// delete the resource
		$result = json_decode($this->testAction("/resources/$rsId.json", array(
			'method' => 'delete',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"delete /resources/$rsId.json : The test should return a success but is returning {$result['header']['status']}");

		// Check error on view the resource
		$this->setExpectedException('NotFoundException', 'The resource does not exist');
		$this->testAction("/resources/{$rsId}.json", array('return' => 'contents', 'method' => 'get'));
	}

/**
 * test if the object returned is a success one
 */
	public function testViewSuccess() {
		$resId = Common::uuid('resource.id.apache');
		$result = json_decode($this->testAction("/resources/{$resId}.json", array(
			'return' => 'contents',
			'method' => 'get'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status']);
		$this->assertEquals('apache', $result['body']['Resource']['name']);
	}
}
