<?php
/**
 * Secret Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @package       app.Test.Case.Model.ResourceTest
 * @since         version 2.12.7
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Secret', 'Model');

class SecretTest extends CakeTestCase {

	public $fixtures = array(
		'app.secret',
		'app.resource',
		'app.user',
		'app.gpgkey',
		'app.profile',
		'app.groupsUser',
		'app.fileStorage',
		'app.group',
		'app.role',
		'core.cakeSession'
	);

	public function setUp() {
		parent::setUp();
		$this->Secret = ClassRegistry::init('Secret');
		$this->Secret->Resource->Behaviors->disable('Permissionable');
	}

/**
 * Test User Validation
 * @return void
 */
	public function testUserIdValidation() {
		$testcases = array(
			'' => false, '?!#' => false, Common::uuid('user.id.carol') => true,
			'bbd56042-cccc-11e1-a0c5-080027796c4b' => false
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array('user_id' => $testcase));
			$this->Secret->set($secret);
			if ($result) {
				$msg = 'validation of the secret user_id with "' . $testcase . '" should validate';
			} else {
				$msg = 'validation of the secret user_id with "' . $testcase . '" should not validate';
			}
			$this->assertEquals($this->Secret->validates(array('fieldList' => array('user_id'))), $result, $msg);
		}
	}

/**
 * Test Resource Validation
 * @return void
 */
	public function testResourceIdValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			Common::uuid('resource.id.apache') => true,
			'509bb871-3b14-4877-8a88-fb098cebc04b' => false
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array('resource_id' => $testcase, 'user_id' => Common::uuid('user.id.frances')));
			$this->Secret->create();
			$this->Secret->set($secret);
			if($result) $msg = 'validation of the secret resource_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the secret resource_id with ' . $testcase . ' should not validate';
			$this->assertEquals($this->Secret->validates(array('fieldList' => array('resource_id'))), $result, $msg);
		}
	}

/**
 * Test Data Validation
 * @return void
 */
	public function testDataValidation() {
		$testcases = array(
			'' => false,
			'!#*' => false,
			'-----BEGIN PGP MESSAGE-----
Version: GnuPG v1.4.12 (GNU/Linux)

hQEMAwvNmZMMcWZiAQf9HpfcNeuC5W/VAzEtAe8mTBUk1vcJENtGpMyRkVTC8KbQ
xaEr3+UG6h0ZVzfrMFYrYLolS3fie83cj4FnC3gg1uijo7zTf9QhJMdi7p/ASB6N
y7//8AriVqUAOJ2WCxAVseQx8qt2KqkQvS7F7iNUdHfhEhiHkczTlehyel7PEeas
SdM/kKEsYKk6i4KLPBrbWsflFOkfQGcPL07uRK3laFz8z4LNzvNQOoU7P/C1L0X3
tlK3vuq+r01zRwmflCaFXaHVifj3X74ljhlk5i/JKLoPRvbxlPTevMNag5e6QhPQ
kpj+TJD2frfGlLhyM50hQMdJ7YVypDllOBmnTRwZ0tJFAXm+F987ovAVLMXGJtGO
P+b3c493CfF0fQ1MBYFluVK/Wka8usg/b0pNkRGVWzBcZ1BOONYlOe/JmUyMutL5
hcciUFw5
=TcQF
-----END PGP MESSAGE-----' => true
		);
		foreach ($testcases as $testcase => $result) {
			$secret = array('Secret' => array(
				'resource_id' => Common::uuid('resource.id.cpp1-pwd1'),
				'user_id' => Common::uuid('user.id.carol'),
				'data' => $testcase
			));
			$this->Secret->create();
			$this->Secret->set($secret);
			if($result) $msg = 'validation of the secret data with ' . $testcase . ' should validate';
			else $msg = 'validation of the secret data with ' . $testcase . ' should not validate';
			$validate = $this->Secret->validates(array('fieldList' => array('data')));
			$msg .= print_r($secret, true);
			$msg .= print_r($this->Secret->validationErrors, true);
			$this->assertEquals($validate, $result, $msg);
		}
	}
}
