<?php
/**
 * User Agent Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class UserAgentTest extends CakeTestCase {

	public $autoFixtures = true;

	public $fixtures = array(
		'app.user_agent'
	);

/**
 * Setup
 * @return void
 */
	public function setup() {
		parent::setUp();
		$this->UserAgent = ClassRegistry::init('UserAgent');
	}

/**
 * Test get validation rules
 * @return void
 */
	public function testGetValidationRules() {
		$rules = $this->UserAgent->getValidationRules();
		$this->assertNotEmpty($rules);
	}

/**
 * Test get
 */
 	public function testGet() {
		$ua = $this->UserAgent->get();
		$this->assertNotEmpty($ua);
 	}

/**
 * Test create if does not exist
 */
 	public function testCreateIfDoesNotExist() {
		$_SERVER['HTTP_USER_AGENT'] = 'phpunit test user agent';
		$ua_raw = env('HTTP_USER_AGENT');
		$this->assertTrue($ua_raw == 'phpunit test user agent', 'This UA should match the one set previously');

		$i = $this->UserAgent->find('count', array('conditions' => array('name' => $ua_raw)));
		$this->assertTrue($i == 0, 'User agent should not be found');

		$this->UserAgent->get();
		$i = $this->UserAgent->find('count', array('conditions' => array('name' => $ua_raw)));
		$this->assertTrue($i == 1, 'User agent should be stored in DB');

		$this->UserAgent->get();
		$i = $this->UserAgent->find('count', array('conditions' => array('name' => $ua_raw)));
		$this->assertTrue($i == 1, 'User agent should be unique in DB');
 	}

/**
 * Test create if does not exist validation error
 */
	public function testCreateIfDoesNotExistValidationException() {
		$characters = 'abcdefghijklmnopqrstuvwxyz ';
		$name = '';
		for ($i = 0; $i < 800; $i++) {
			$name .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		$ua['UserAgent']['id'] = Common::uuid($name);
		$ua['UserAgent']['name'] = $name;

		$this->setExpectedException('ValidationException');
		$this->UserAgent->createIfDoesNotExist($ua);
	}

/**
 * Test get truncate long user agents
 */
	public function testGetTruncateLongUserAgent() {
		$characters = 'abcdefghijklmnopqrstuvwxyz ';
		$name = '';
		for ($i = 0; $i < 800; $i++) {
			$name .= $characters[mt_rand(0, strlen($characters) - 1)];
		}
		$_SERVER['HTTP_USER_AGENT'] = $name;
		$this->UserAgent->get();

		$id = Common::uuid(substr($name, 0, 512));
		$i = $this->UserAgent->find('count', array('conditions' => array('id' => $id)));
		$this->assertTrue($i == 1, 'User agent should be stored in DB');
	}
}