<?php
/**
 * InstallShellTest Test file
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('ShellDispatcher', 'Console');
App::uses('ConsoleOutput', 'Console');
App::uses('ConsoleInput', 'Console');
App::uses('Shell', 'Console');
App::uses('CakeSchema', 'Model');
App::uses('SchemaShell', 'Console/Command');
App::uses('InstallShell', 'Console/Command');

/**
 * InstallShellTest class
 *
 * @package      app.Test.Case.Console.Command
 */
class InstallShellTest extends CakeTestCase {

/**
 * @var string original value for environment variable GNUPGHOME
 */
	private $__originalEnv;

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		$out = $this->getMock('ConsoleOutput', array(), array(), '', false);
		$in = $this->getMock('ConsoleInput', array(), array(), '', false);
		$this->Shell = $this->getMock(
			'InstallShell',
			array('in', 'out', 'hr', 'error', 'err', '_stop'),
			array($out, $out, $in)
		);
		$this->Shell->params['no-admin'] = true;
		$this->Shell->params['quiet'] = true;
		$this->Shell->params['connection'] = 'test';

		$this->__originalEnv = getenv('GNUPGHOME');

	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		putenv('GNUPGHOME=' . $this->__originalEnv);
		parent::tearDown();
	}

/**
 * Set production config to be able to perform a production install
 *
 * @return void
 */
	protected function _setTestProductionConfig() {
		Configure::write('debug', 0);
		Configure::write('GPG.serverKey', [
			'id' => 'D8A7B3D3',
			'fingerprint' => 'C73D232B0BFF4B27F4B5C8FB4CA07E2AD8A7B3D3',
			'public' => Configure::read('GPG.testKeys.path') . DS . 'server_prod_unsecure_public.key',
			'private' => Configure::read('GPG.testKeys.path') . DS . 'server_prod_unsecure_private.key',
			'passphrase' => ''
		]);
	}

/**
 * Test passbolt keyring location does not exist
 *
 * @return void
 */
	public function testGpgKeyringDoesNotExist() {
		// use put_env directly since the config item is set as an environment variable in the bootstrap
		putenv('GNUPGHOME=/bogus/location/.gnupg');
		$this->setExpectedException('CakeException', 'GPG Keyring is not available or not writable.');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt keyring location does not exist
 *
 * @return void
 */
	public function testGpgKeyringNotWritable() {
		// use put_env directly since the config item is set as an environment variable in the bootstrap
		putenv('GNUPGHOME=/root/.gnupg');
		$this->setExpectedException('CakeException', 'GPG Keyring is not available or not writable.');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no private GPG server key fingerprint configuration found
 *
 * @return void
 */
	public function testGpgServerKeyFingerprintConfigNotFound() {
		Configure::delete('GPG.serverKey.fingerprint');
		$this->setExpectedException('CakeException', 'The GnuPG config for the server is not available or incomplete');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no private GPG server key configuration found
 *
 * @return void
 */
	public function testGpgServerKeyPrivateConfigNotFound() {
		Configure::delete('GPG.serverKey.private');
		$this->setExpectedException('CakeException', 'The GnuPG config for the server is not available or incomplete');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no private GPG server key configuration found
 *
 * @return void
 */
	public function testGpgServerKeyPublicConfigNotFound() {
		Configure::delete('GPG.serverKey.public');
		$this->setExpectedException('CakeException', 'The GnuPG config for the server is not available or incomplete');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no private GPG server key found
 *
 * @return void
 */
	public function testGpgServerKeyPrivateNotFound() {
		$privatePath = TMP . DS . 'no_private_key_here.key';
		Configure::write('GPG.serverKey.private', $privatePath);
		$this->setExpectedException('CakeException', 'No private key found at the given path ' . $privatePath);
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no private GPG server key found
 *
 * @return void
 */
	public function testGpgServerKeyPrivateDoNotMatchFingerprint() {
		Configure::write('GPG.serverKey.fingerprint', 'C73D232B0BFF4B27F4B5C8FB4CA07E2AD8A7B3D3');
		Configure::write('GPG.serverKey.public', Configure::read('GPG.testKeys.path') . DS . 'server_prod_unsecure_public.key');
		$this->setExpectedException('CakeException', 'The private key does not match the fingerprint mentioned in the config');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no public GPG server key found
 *
 * @return void
 */
	public function testGpgServerKeyPublicNotFound() {
		$privatePath = TMP . DS . 'no_public_key_here.key';
		Configure::write('GPG.serverKey.public', $privatePath);
		$this->setExpectedException('CakeException', 'No public key found at the given path ' . $privatePath);
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be install if no public GPG server key found
 *
 * @return void
 */
	public function testGpgServerKeyPublicDoNotMatchFingerprint() {
		Configure::write('GPG.serverKey.fingerprint', 'C73D232B0BFF4B27F4B5C8FB4CA07E2AD8A7B3D3');
		Configure::write('GPG.serverKey.private', Configure::read('GPG.testKeys.path') . DS . 'server_prod_unsecure_private.key');
		$this->setExpectedException('CakeException', 'The public key does not match the fingerprint mentioned in the config');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can't be installed in production with default Gpg Server key
 *
 * @return void
 */
	public function testProductionGpgServerKey() {
		Configure::write('debug', 0);
		Configure::write('GPG.serverKey.fingerprint', '2FC8945833C51946E937F9FED47B0811573EE67E');
		$this->setExpectedException('CakeException', 'Default GnuPG server key cannot be used in production. Please change the values of \'GPG.server\' in \'APP/Config/app.php\' with your server key information. If you don\'t have yet a server key, please generate one, take a look at the install documentation.');
		$this->Shell->initGpgKeyring();
	}

/**
 * Test passbolt can be install in development
 *
 * @return void
 */
	public function testDevelopmentInstall() {
		$this->Shell->main();
	}

/**
 * Test passbolt can be install in production
 *
 * @return void
 */
	public function testProductionInstall() {
		$this->_setTestProductionConfig();
		$this->Shell->main();
	}
}
