<?php
/**
 * Gpgkeys Controller Tests
 *
 * @copyright (c) 2015-2016 Bolt Softwares Pvt Ltd
 *                2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('GpgkeysController', 'Controller');
App::uses('Gpgkey', 'Model');
App::uses('User', 'Model');

// Uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class GpgkeysControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
		'app.gpgkey',
		'app.group',
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log'
	);

/**
 * Setup
 */
	public function setUp() {
		$this->User = ClassRegistry::init('User');
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		parent::setUp();

		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);
	}

/**
 * TEST INDEX
 */
/**
 * Test index success
 */
	public function testIndexSuccess() {
		$result = json_decode($this->testAction('/gpgkeys.json', ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/gpgkeys.json return something');
	}

/**
 * Test index success if no key is set
 */
	public function testIndexSuccessEmpty() {
		$this->Gpgkey->deleteAll(['Gpgkey.id <>' => null]);
		$result = json_decode($this->testAction('/gpgkeys.json', ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/gpgkeys.json return empty data');
	}

/**
 * Test index with the legacy modified filter set in the past
 */
	public function testIndexFiltersModifiedLegacyPast() {
		// Test filter modified_after with a date in the past.
		$url = '/gpgkeys.json?modified_after=' . strval(strtotime('1980-12-14 00:00:00'));
		$result = json_decode($this->testAction($url, ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}

/**
 * Test index with the legacy modified filter set in the future
 */
	public function testIndexFiltersModifiedLegacyFuture() {
		$url = '/gpgkeys.json?modified_after=' . strval(strtotime('2026-12-14 00:00:00'));
		$result = json_decode($this->testAction($url, ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}

/**
 * Test index with the legacy modified filter set in the past
 */
	public function testIndexFiltersModifiedPast() {
		// Test filter modified_after with a date in the past.
		$url = '/gpgkeys.json?filter[modified-after]=' . strval(strtotime('1980-12-14 00:00:00'));
		$result = json_decode($this->testAction($url, ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}

/**
 * Test index with the legacy modified filter set in the future
 */
	public function testIndexFiltersModifiedFuture() {
		$url = '/gpgkeys.json?filter[modified-after]=' . strval(strtotime('2026-12-14 00:00:00'));
		$result = json_decode($this->testAction($url, ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS);
	}

/**
 * Test index with invalid modified filter date
 */
	public function testIndexFiltersModifiedInvalid() {
		$url = '/gpgkeys.json?filter[modified-after]=' . 'not a timestamp';
		$this->setExpectedException('BadRequestException', "Invalid filter. \"not a timestamp\" is not a valid timestamp for filter modified-after.");
		$this->testAction($url, ['return' => 'contents', 'method' => 'GET']);
	}

/**
 * Test index with invalid legacy modified filter date
 */
	public function testIndexFiltersModifiedLegacyInvalid() {
		$url = '/gpgkeys.json?modified_after=' . 'not a timestamp';
		$this->setExpectedException('BadRequestException', "Invalid filter. \"not a timestamp\" is not a valid timestamp for filter modified-after.");
		$this->testAction($url, ['return' => 'contents', 'method' => 'GET']);
	}

/**
 * TEST VIEW
 */
/**
 * Test view with a uuid not valid.
 */
	public function testViewUserIdNotValid() {
		$this->setExpectedException('BadRequestException', 'The user id is not valid');
		$this->testAction("/gpgkeys/badid.json", ['method' => 'get', 'return' => 'contents']);
	}

/**
 * Test view with uuid non existing.
 */
	public function testViewUserDoesNotExist() {
		$id = Common::uuid('not-valid-reference');
		$this->setExpectedException('NotFoundException', 'The user does not exist.');
		$this->testAction("/gpgkeys/{$id}.json", ['method' => 'get', 'return' => 'contents']);
	}

/**
 * Test view success
 */
	public function testViewSuccess() {
		$gpgkey = $this->Gpgkey->findByUserId(Common::uuid('user.id.ada'));
		$url = "/gpgkeys/{$gpgkey['Gpgkey']['user_id']}.json";
		$result = json_decode($this->testAction($url, ['return' => 'contents', 'method' => 'GET'], true));
		$this->assertEquals($result->header->status, Status::SUCCESS, '/gpgkey return something');
	}

/**
 * TEST ADD
 */
/**
 * Test adding a key.
 */
	public function testAddSuccess() {
		$pubKey = file_get_contents( Configure::read('GPG.testKeys.path') . 'test_public.key');
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);
		$json = json_decode(
			$this->testAction('/gpgkeys.json',
			array(
				'data' => array(
					'Gpgkey' => array(
						'key' => $pubKey
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)),
			true
		);
		$this->assertEquals(
			Status::SUCCESS,
			$json['header']['status'],
			"Add : /gpgkeys.json : The test should return sucess but is returning " . print_r($json, true)
		);

		$gpgkey = $this->Gpgkey->find(
			'first',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $user['User']['id'],
					'Gpgkey.deleted' => false
				)
			));

		$this->assertEquals(
			$gpgkey['Gpgkey']['key'],
			$pubKey,
			"Add : /gpgkeys.json : after add, the content of the keys is not the same"
		);
	}

/**
 * Test that adding a key removes the user previous keys.
 */
	public function testAddRemovePreviousKeys() {
		$pubKey[] = file_get_contents(Configure::read('GPG.testKeys.path') . 'test_public.key');
		$pubKey[] = '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

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
=OuFb
-----END PGP PUBLIC KEY BLOCK-----
';
		$pubKey[] = '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFQcTVQBCACdQRe0m3hh0pzzbjL43M/GYcAhR6yVHOatxRXAm1s5qdTtvjZ6
V5c18VE263glEsR2h1243ToJCM/vzCr6hRQmGjoQjZ7VGII4Hro6v1Y5f8ENaQv8
uNF4gtdqKjO5oJosEwxjbsJs3o3igyLYbM9B36KVQmzqI7t3xS88pipBPePVZaNi
FQBZrIyWT3H88nZv1BGsxpZ73asnsLmK3MTXwfqPSvWOU3/xFsDLw2+uh+j/U4Bh
hoJoiIgu7wtQ3eUX3ZQzfD0k4DyiHFGLZEBr2HbcGNWrk3E84A/5yW1bYCMJjIU+
jhVjWr7frE+4jsEYgYjcDO13YUvvKsD+7xYjABEBAAG0HWtldmluIG11bGxlciA8
a2V2aW5AdGVzdC5vcmc+iQEyBBABCAAmBQJUHE1ZBgsJCAcDAgkQsBSWBu26K5sE
FQgCCgMWAgECGwMCHgEAAKE5B/9NOpW9Mr5SWR4edHt/TeaB7guCDHlmooYxFe9Z
UoCuAI0bjRAEtQH4sSh4fJ9Rdxbel+P4WdmIo1oCHjvx3mmIQDzXSwvSe1cQWF02
KgjRzbJQELP6SivsQB2Bk/T9W/r36PVJtzk2Yasd7sacmeDNGRfJwosISbFCeBQN
Nqhd9pE3QbnQt7u8319vcN8pkSy4YJbh67K9HECipPF1mxQvPnJ1u7QGD3WICJDA
W/F5S7AhRFtZdL6KkOxj/ieeEmzm0lKkJDpjEDR/XMGSUuMUhqUt7z9IPRVzTPNh
ioYOHuzSl5TRxKB7fHzO8KDfrr7xsyKnTD50O6Ywocd/sIZnuQENBFQcTVkBCACG
lG8aloAIStEYjcsyQHvD8sSjN2TL715jSnEZycVtwSdawNT0jGkBRUCLF2eGRM5J
teWeyvUOt7Bt/3+QYKUEtmsFT7v1+6rLCbR1mITwXAjCydQatnUstg7e5cYDcL2B
jRMRO38pELnpBcEm6XFWgpaEJp+sSxniBS6l+N7XYDqhRoMe4ihN/kFdjC67/Nup
XSf2dBQOOXhetFO94M4b26RNnJwHc07yAb+P41T+KVYSr6rUym8wGGFU1BfYaEEx
7Hpo26M1OJgeJ9+BO+yHoknE0V9fs4bZunmUwLC5YFRzedFVn6At184gJd4Q8gN8
r2jC6l2ehuPNwTodSCwzABEBAAGJAR8EGAEIABMFAlQcTV8JELAUlgbtuiubAhsM
AADA0Af+NCY4DUurwGWewqTtD5lzXW3FUgsnBTvY9atddZuxiLuzBRviXQQyrq5p
GPhQslPwaZ+48q57A8JzTxQcwEL4ACKYuNUKGPU+3HecSYackGLHHIJSw9D50H1l
m8Ud5gWrKyjaGkHVvXkJMoFlzKLb0cX2tXuaIOPzWL49/7jgemdU4fIOs5PTOTI+
RCLENU3fPheNw0Dcu3uuhqCfpDjLfaVl2V6kU9yN4ujJdpUvZ29nmjUA0rA2jm2h
ketMyG9W1lYKDJ/vYVtWoFc2PBb+5bGR+KpSFSEo72Qpj+iidPna1JRHYqTaqCIm
x0kL7izI0GKheZwmWTsww5V+bCP5+g==
=i2Cb
-----END PGP PUBLIC KEY BLOCK-----
';
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);

		// Number of insertion rounds.
		$rounds = count($pubKey);
		foreach ($pubKey as $key) {
			$result = json_decode(
				$this->testAction('/gpgkeys.json',
					array(
						'data' => array(
							'Gpgkey' => array(
								'key' => $key
							),
						),
						'method' => 'post',
						'return' => 'contents'
					)),
					true
				);
			// For each round, test that the add was succesful.
			$this->assertEquals(
				Status::SUCCESS,
				$result['header']['status'],
				"Add : /gpgkeys.json : The test should return success but is returning " . print_r($result, true)
			);
		}

		// Count the number of deleted keys.
		// Logically, it should be equal to $round.
		$nbDeletedKeys = $this->Gpgkey->find(
			'count',
			array (
				'conditions' => array(
					'Gpgkey.user_id' => $user['User']['id'],
					'Gpgkey.deleted' => true
				)
			));

		// Assertion.
		$this->assertEquals($nbDeletedKeys, $rounds,
			"Add : /gpgkeys.json : after add, the number of deleted keys in the db should be " . ($rounds)
		);
	}

/**
 * Test adding an invalid key.
 */
	public function testAddInvalidKey() {
		$pubKey = 'invalidkey';
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$this->User->setActive($user);
		$this->setExpectedException('HttpException', 'The gpgkey provided could not be used');
		$this->testAction('/gpgkeys.json',
			array(
				'data' => array(
					'Gpgkey' => array(
						'key' => $pubKey
					),
				),
				'method' => 'post',
				'return' => 'contents'
			)
		);
	}

/**
 * Test adding with no data
 */
	public function testAddNoData() {
		$this->setExpectedException('BadRequestException', 'No key data provided.');
		$data = ['Something' => []];
		$this->testAction('/gpgkeys.json', ['method' => 'post', 'return' => 'contents', 'data' => $data]);
	}

/**
 * Test adding with no key data.
 */
	public function testAddNoKeyData() {
		$this->setExpectedException('BadRequestException', 'No key data provided.');
		$data = ['Gpgkey' => []];
		$this->testAction('/gpgkeys.json', ['method' => 'post', 'return' => 'contents', 'data' => $data]);
	}

/**
 * Test adding with an empty key.
 */
	public function testAddEmptyKeyData() {
		$this->setExpectedException('BadRequestException', 'No key data provided.');
		$data = ['Gpgkey' => ['key' => []]];
		$this->testAction('/gpgkeys.json', ['method' => 'post', 'return' => 'contents', 'data' => $data]);
	}
}