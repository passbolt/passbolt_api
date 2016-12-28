<?php
/**
 * HealthCheck Controller Tests
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('HealthCheckController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}

class HealthCheckControllerTest extends ControllerTestCase {
	public $fixtures = [
		'app.user',
		'app.role',
		'core.cakeSession',
	];

	public function testAccessChecksDebugOn() {
		$this->testAction('/healthcheck');
		$this->assertTrue(is_bool($this->vars['checks']['dbFile']), 'dbFile check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['dbConnect']), 'dbConnect check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['adminCount']), 'adminCount check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['settings']), 'settings check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['phpVersion']), 'phpVersion check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['tmp']), 'tmp check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['debug']), 'debug check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['validation']), 'validation check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['ssl']), 'ssl check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['sslForce']), 'sslForce check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['gpg']), 'gpg check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['gpgKeyDefault']), 'gpgKeyDefault check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['selenium']), 'selenium check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['registration']), 'registration check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['jsProd']), 'jsProd check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['debugKit']), 'debugKit check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['phpunit']), 'phpunit check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['phpunitVersion']), 'phpunitVersion check should not be empty');
		$this->assertTrue(is_bool($this->vars['checks']['imgPublicWritable']), 'phpunitVersion check should not be empty');
	}
}