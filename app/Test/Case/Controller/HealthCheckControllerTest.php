<?php
/**
 * HealthCheck Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
        $checks = [
            'devTools' => [
                'phpunit', 'phpunitVersion'
            ],
            'database' => [
                'connect', 'supportedBackend', 'tablesPrefix', 'tablesCount', 'defaultContent'
            ],
            'application' => [
                'latestVersion', 'schema', 'sslForce', 'seleniumDisabled', 'registrationClosed', 'jsProd'
            ],
            'core' => [
                'cache', 'debugDisabled', 'salt', 'cipherSeed'
            ],
            'configFile' => [
                'core', 'app', 'database', 'email'
            ],
            'environment' => [
                'phpVersion', 'pcre', 'tmpWritable', 'imgPublicWritable'
            ],
            'gpg' => [
                'gpgKey', 'gpgKeyDefault'
            ]
        ];
        foreach ($checks as $groupName => $groups) {
            foreach($groups as $check) {
                $this->assertTrue(
                    is_bool($this->vars['checks'][$groupName][$check]),
                    $groupName . '.' . $check .' check should not be empty'
                );
            }
        }
    }
}