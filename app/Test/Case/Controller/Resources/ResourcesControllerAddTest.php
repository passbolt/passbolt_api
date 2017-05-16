<?php
/**
 * Resources Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 * 				  2017-present Passbolt SARL
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

class ResourcesControllerAddTest extends ControllerTestCase
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

	public function setUp()
	{
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->Resource = ClassRegistry::init('Resource');
		$user = $this->User->findById(Common::uuid('user.id.dame'));
		$this->User->setActive($user);
	}

	private function _encryptSecretFor($usersUuid, $resourceId, $text) {
		$gpg = new \Passbolt\Gpg();
		$secretData = array();

		foreach ($usersUuid as $userUuid) {
			$gpgKey = $this->Gpgkey->findByUserId($userUuid);
			$gpg->setEncryptKey($gpgKey['Gpgkey']['key']);
			$armoredSecret = $gpg->encrypt($text);
			$secretData[] = array(
				'user_id' => $usersUuid,
				'resource_id' => $resourceId,
				'data' => $armoredSecret
			);
		}

		return $secretData;
	}

	public function testAdd() {
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test1',
					'username' => 'test1',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(Common::uuid('user.id.dame')), '', 'testAdd secret'),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);

		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return sucess but is returning {$result['header']['status']} : " . print_r($result, true));

		// check that the resource has been saved has been saved
		$resource = $this->Resource->findByName("test1");
		$this->assertNotEmpty($resource);
	}

	/**
	 * Test adding a resource with a secret
	 */
	public function testAddWithSecret()
	{
		$result = json_decode($this->testAction('/resources.json', array(
			'data' => array(
				'Resource' => array(
					'name' => 'test3',
					'username' => 'test3',
					'uri' => 'http://www.google.com',
					'description' => 'this is a description'
				),
				'Secret' => $this->_encryptSecretFor(array(Common::uuid('user.id.dame')), '', 'testAddWithSecret secret'),
			),
			'method' => 'post',
			'return' => 'contents'
		)), true);
		$this->assertEquals(Status::SUCCESS, $result['header']['status'],
			"Add : /resources.json : The test should return success but is returning " . print_r($result, true));

//		var_dump($result['body']);
		// check that Secret has been saved
		$secret = $this->Resource->Secret->findByResourceId($result['body']['Resource']['id']);
		$this->assertTrue(!empty($secret), "Add : /resources.json : Secret should have been inserted but is not");
	}

	/**
	 * Test adding a resource with a wrong secret, and test that the rollback was effective.
	 */
	public function testAddWithWrongSecretRollback()
	{
		$user = $this->User->findById(Common::uuid('user.id.marlyn'));
		$this->User->setActive($user);

		// Error : name is empty
		try {
			$this->testAction('/resources/add.json', array(
				'data' => array(
					'Resource' => array(
						'name' => 'testAddSecretRollback',
						'username' => 'test1',
						'uri' => 'http://www.google.com',
						'description' => 'this is a description'
					),
					'Secret' => array(
						array(
							'data' => 'wrong secret'
						),
					)
				),
				'method' => 'Post',
				'return' => 'contents'
			));
		} catch (Exception $e) {
			$this->assertEquals($e->getMessage(), 'Could not validate secret model');
			// Get the resource again, and assert it's the exact same
			$resourceAfterCreate = $this->Resource->find('first', [
				'conditions' => [
					'name' => 'testAddSecretRollback'
				],
				'contain' => ['Secret']
			]);

			$this->assertEmpty($resourceAfterCreate,
				'After a secret validation error, the resource should not exist in the database');
		}
	}
}
