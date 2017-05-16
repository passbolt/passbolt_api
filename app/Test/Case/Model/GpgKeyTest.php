<?php
/**
 * Role Model Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('User', 'Model');
App::uses('Role', 'Model');

class GpgkeyTest extends CakeTestCase {

	public $fixtures = array(
		'app.user',
		'app.role',
		'app.gpgkey'
	);

	public $autoFixtures = true;

	/**
	 * Setup
	 * @return void
	 */
	public function setup() {
		parent::setUp();
		$this->Gpgkey = ClassRegistry::init('Gpgkey');
		$this->User = ClassRegistry::init('User');
	}

	/**
	* Test if the fixtures keys as set in the database
	* @return void
	*/
	public function testSetup() {
		$k = $this->Gpgkey->find('first', array('conditions' => array('key_id' => '00000000')));
		$this->assertEquals(empty($k), true, 'Should not find any key');
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUserIdValidation() {
		$user = $this->User->findById(Common::uuid('user.id.user'));
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'aaa00003-c5cd-11e1-a0c5-080027z!6c4c' => false,
			'zzz00003-c5cd-11e1-a0c5-080027796c4c' => false,
			'aaa00003-c5cd-11e1-a0c5-080027796c4c' => false,
			$user['User']['id'] => true,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('user_id' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the user_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the user_id with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('user_id')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testKeyValidation() {
		$testcases = array(
			'' => false,
			'?!#' => false,
			'test' => false,
			'-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

mQENBFRso0cBCAC+J/b4LoML0L9/xlIs3/TZKC9CSVTQ2xljs3hdawvGi/+p210M
doXev6optgaDPj0q61HaCR1XhrCa7gK9jEC54M91LwrRzm5nBT9Fez/wezXn2I0v
56RIAn42k3OcDwWUDdPenzZS+/4/efJPyb/XO7sZMiD+OjjpXwNNu9ezqSvNZ1uo
/VcMHBTkQ0NqETO5Yt5KX9JkrKP2Q0BR2BVHGHp7K/PJiWnN+T8dTFr6RsiZsVWs
dD/5IPSkNAsi8E8fguuWecQtMftled/36QjlaXYgZ/U1kVi2mDUebd6oxTvB85fm
pCvIekFRNqs6TAd4de+pDBsbYY+vsE1tCsxvABEBAAG0JFBhc3Nib2x0IFBHUCA8
cGFzc2JvbHRAcGFzc2JvbHQuY29tPokBPQQTAQoAJwUCVGyjRwIbAwUJB4YfgAUL
CQgHAwUVCgkICwUWAgMBAAIeAQIXgAAKCRBPgZQCX9LZLAk6CACop+n6hgaCrFWU
m5EaT2+XBBw9rEbcISCH8Zeh2Xk1RmLOiTLSYRka8qnUcEBbSq8EOoJsfNdWEK8d
QwhearHZjRCUjrQMPsMwwKhKrkG7RR7VI+hN+7H7Joyq3UDE7S+55vvWd7hSZbPl
buhPWBirviN1Lovk2tZbI7ClW1+Cx9uK3lad1LywlPsxkCKbRfDcWrnLFKk1UnYi
229ZXCYjuJbzfPRWx039nVVt6IoOZnLCil5G9d5AFt5Ro7WFdormTsfP+EehLI7q
szrEVD2ZQgn+rSF8P97DLABDa28+JfTsnivVQn5cyLR6x+XTJp96SSprm5nY0C3+
ybog/dDFuQENBFRso0cBCAC50ryBhhesYxrJEPDvlK8R0E8zCxv7I6fXXgORNyAW
PAsZBUsaQizTUsP9VpO6Y0gOPGxvcGP9xSc+01n1stM9S7/+utCfm8yD4UtP9Ric
mkq/T/w/l9iLFypo6al47HW28mQlMvbUWSkMoK9JXRpB2c2VPmN8UXVQX4cQ++ad
YQNnRgSo3n+VdvIKgSW3rkcQIriGX3P79cciqAA/NzkivNyZSQaVBLJioO+kDkYu
Q+oIstvEusmHIon0Ltggi8B6LM5vAQpBRwQ9dfUgAbpQpfzm8VUkCGmsUr5hnOO3
tmaWOTKZcpXiF5+rW2NrqiAhRhm44s+JipmTE++u/6X9ABEBAAGJASUEGAEKAA8F
AlRso0cCGwwFCQeGH4AACgkQT4GUAl/S2Sx2LQgAoXOxfA5pOCm9UP2f2pQA7hyv
DEppROxkBLVcnZdpVFw4yrVQh/IWHSxcX0rcrTPlBjjFpTos+ACOZ5EKSRCHjIqF
biraG5/2YjKa5cqc7z/W9bSuhmWizPBpXlQk6MohG6jXlw7OyVosisbHGobFa5CW
hF+Kc8tb0mvk9vmqn/eDYnGYcSftapyGB3lq7w4qtKzlvn2g2FlnxJCdnrG3zGtO
Kqusl1GcnrNFuDDtDwZS1G+3T8Y8ZH8tRnTwrSeO3I7hw/cdzCEDg4isqFw371vz
UghWsISL244Umc6ZmTufAs+7/6sNNzFAb5SzwVmpLla1x3jth4bwLcJTGFq/vw==
=GG/Z
-----END PGP PUBLIC KEY BLOCK-----' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('key' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the key with ' . $testcase . ' should validate';
			else $msg = 'validation of the  key with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('key')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	public function testGeneratedByOpenpgpjsKeyValidation() {
		$key = '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

xsBNBFYxBmABCACnjiQcDOzinbLBiVS87hV4HqBgllTXHzUsdVQjRIkLAq/9
2KKxrY2ruffL7Y8Jw5ibuc95n/17j2j9kFNayuhhiJlFbCh9SQPKaY67TBMl
A4M54mY60i8/JdUxabKYGcep9gQrWMYTGHdwFyKIHAWlJ69ptFEzaatfXq2+
rLSrdbHPPnXNWRQs19twR2iqZGOjL//uKyv/X+IQz9o0JLaQL99NeiMKRg5V
0wBxgrjFtw+oiuUMVO62Rv4io6WNkwWCaw7VAmiHqCBsBKI0fsed1usvqL9Y
fqnYSWM+koweqd+lrSE3pgpjuwZtkZQ5Ij8SV4uWFPUabC3gV9o+2UiDABEB
AAHNIWtldmluIG11bGxlciA8a2V2aW5AcGFzc2JvbHQuY29tPsLAcgQQAQgA
JgUCVjEGYgYLCQgHAwIJEN+cGCNt4Iy8BBUIAgoDFgIBAhsDAh4BAAD5vQf9
EiGG+NnH/LdqCZ+VQzkXr+cZC9ma1MpZgCvbQmRB7LIU8i6IUdoTSVtVKiDs
UvI4sWNC1AGeKr7oy257r9QFRwUZ71cahN5gLwrd3qGzJfVTbiGq1DJI+EcI
7VNhBDVFpei5Z2I26t989rNAZwfY5cW0Ko6RP0PuV4T/57q/VyiUDDMoqEgc
3JUuWiDul4xDXIx/6XYC5hAM4APD7Q7Fc+Oll6LQSEo0WZ+ReX3q1vllvEAU
QTRABXTv0q8sK9k0OTjMKPaJBOHsNpHeKo/EwYnInlVlId8sI5w+O9Y9WOHY
zqpyoTRC0X1Mm72+OJZaWiipp2ieGP+PoZwZM6Utdc7ATQRWMQZiAQgAmfMW
LKyKiHcKie/wePcD3WW+DO6Va/RDjMHPp77jRP3D+uY3mv3HDG4naZq0Lwz5
+gA62Q5AiGruKPPBIKmvm+CBRnJYS1upZ9Yi2wKUUzYgOfkHTiz96QnSnqB+
zAyEVtpqp5mYTmsDFlub8cWZia8EhFpBV03Bc3d0hdpxaDjzgphpd4JJTs2c
Eyije5rpBuiBApcWSfA7wQnYWzV4eStlssGX5FV2qGPz967gmxqUcuotmIaw
zDDx/tpxdY5WtVwhQ1uMe1suQ5325OgDAjFRPLJP/y79hMNsKddmO/79MzX6
X6/Gwom1qFawO5uHNtmmx/Cg48/qPRnj5RjokQARAQABwsBfBBgBCAATBQJW
MQZkCRDfnBgjbeCMvAIbDAAAL04H/1GfEKDO9uFI0RvV3lpzIiwrme77soVX
Tieur32XgU557sweDJDaGj9Mp8EcBw2/2ZEXbFH+w0ffjJDXlvhfDr5UsM9S
cYxmKhJpdhM5rikMHf4V4xWO+jqKWkvZZbYMEfEvFrZkgiwZP6tFFlpZ+sEd
sgJnsrpPwKDG/8L3nGbYD8xO9UuwXghspjCzIhl++HL5oXDUGiWRSaTSYmGx
bwJo8LBoJubslDFkRlJXbVMb2W34oNnJpnz2IRpVsGXzGlK4WBA0rKurU9+O
CMmXDYbfAFcNiUiXNNBdvzlbLf2N1LB5jShp9l+eDQgGKvaOKVaatX+nSp7R
ffvdXuT2n3w=
=bTbt
-----END PGP PUBLIC KEY BLOCK-----';

		$gpgkey = array('Gpgkey' => array('key' => $key));
		$this->Gpgkey->set($gpgkey);
		$validate = $this->Gpgkey->validates(array('fieldList' => array('key')));
		$this->assertEquals($validate, true, 'validation of the key should have returned true');
	}


	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testBitsValidation() {
		$testcases = array(
			'' => true,
			'?!#' => false,
			'12' => true,
			'12a' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('bits' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the bits with ' . $testcase . ' should validate';
			else $msg = 'validation of the bits with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('bits')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testUidValidation() {
		$testcases = array(
			'' => false,
			'user' => true,
			'<user@passbolt.net>' => false,
			'user <user@passbolt.net>' => true,
			'user (user1) <user@passbolt.net>' => true,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('uid' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the uid with ' . $testcase . ' should validate';
			else $msg = 'validation of the uid with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('uid')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test KeyId Validation
	 * @return void
	 */
	public function testKeyIdValidation() {
		$testcases = array(
			'' => false,
			'000876AF' => true,
			'000876RF' => false,
			'8745AE100' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('key_id' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the key_id with ' . $testcase . ' should validate';
			else $msg = 'validation of the key_id with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('key_id')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test Fingerprint Validation
	 * @return void
	 */
	public function testFingerprintValidation() {
		$testcases = array(
			'' => false,
			'55C6CD7D3DCBACF4D3432245EFAA789F48BF426F' => true,
			'459B102D43F683E7EFC523610EC547AD9E7FA152' => false, // Should not validate because already used in database for nancy. Not unique.
			'459B102D43F683Z7EFC523610EC547AD9E7FA152' => false,
			'8745AE100' => false,
			'120F87DDE5A438DE89826D464F8194025FD2D92CG' => false
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('fingerprint' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of the fingerprint with ' . $testcase . ' should validate';
			else $msg = 'validation of the fingerprint with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('fingerprint')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test isValidFingerprint method
	 */
	public function testIsValidFingerprint() {
		$testcases = array(
			'' => false,
			'120F87DDE5A438DE89826D464F8194025FD2D92C' => true,
			strtolower('120F87DDE5A438DE89826Z464F8194025FD2D92C') => false,
			'120F87DDE5A438DE89826Z464F8194025FD2D92C' => false,
			'8745AE100' => false,
			'120F87DDE5A438DE89826D464F8194025FD2D92CG' => false
		);
		foreach ($testcases as $testcase => $result) {
			if($result) $msg = 'validation of key_created with ' . $testcase . ' should validate';
			else $msg = 'validation of key_created with ' . $testcase . ' should not validate';
			$r = Gpgkey::isValidFingerprint($testcase);
			$this->assertEquals($r, $result, $msg);
		}
	}

	/**
	 * Test Expires Validation
	 * @return void
	 */
	public function testExpiresValidation() {
		$testcases = array(
			'' => true,
			'1980-12-14 00:00:00' => false,
			'2025-12-14 00:00:00' => true,
			'ghdjsk tt gg' => false
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('expires' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of expires with ' . $testcase . ' should validate';
			else $msg = 'validation of expires with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('expires')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test Key Created Validation
	 * @return void
	 */
	public function testKeyCreatedValidation() {
		// Test for a key generated on a system that is not on time. (6 hours difference).
		$inSixHours = date('Y-m-d H:i:s', strtotime('+6 hour'));
		$testcases = array(
			'' => false,
			'1980-12-14 00:00:00' => true,
			'2025-12-14 00:00:00' => false,
			$inSixHours => true,
			'ghdjsk tt gg' => false
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('key_created' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of key_created with ' . $testcase . ' should validate';
			else $msg = 'validation of key_created with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('key_created')));
			$this->assertEquals($validate, $result, $msg);
		}
	}

	/**
	 * Test Type Validation
	 * @return void
	 */
	public function testTypeValidation() {
		$testcases = array(
			'' => true,
			'RSA' => true,
			'ELGAMAL' => true,
			'DSA' => true,
			'ECC' => true,
			'ECDSA' => true,
			'DH' => true,
			'DHU' => false,
		);
		foreach ($testcases as $testcase => $result) {
			$gpgkey = array('Gpgkey' => array('type' => $testcase));
			$this->Gpgkey->set($gpgkey);
			if($result) $msg = 'validation of type with ' . $testcase . ' should validate';
			else $msg = 'validation of type with ' . $testcase . ' should not validate';
			$validate = $this->Gpgkey->validates(array('fieldList' => array('type')));
			$this->assertEquals($validate, $result, $msg);
		}
	}


	/**
	 * Test getFindFields
	 */
	public function testGetFindFields() {
		$default = ['fields' => []];
		$defaultCases = ['not_existing_case'];
		$customCases = ['GpgKey::view', 'GpgKey::index', 'GpgKey::save'];

		// Default fields return.
		foreach($defaultCases as $case) {
			$this->assertEquals($default, Gpgkey::getFindFields($case), "Find fields missing for case : $case");
		}

		// Custom fields return.
		foreach($customCases as $case) {
			$this->assertNotEquals($default, Gpgkey::getFindConditions($case), "Find fields should be empty for case : $case");
		}
	}

	/**
	 * Test getFindConditions
	 */
	public function testGetFindConditions() {
		$default = ['conditions' => []];
		$defaultCases = ['not_existing_case', 'GpgKey::save'];
		$customCases = ['GpgKey::view', 'GpgKey::index'];

		// Test find conditions cases == default.
		foreach($defaultCases as $case) {
			$this->assertEquals($default, GpgKey::getFindConditions($case), "Find conditions should be empty for case : $case");
		}

		// Test find conditions cases != default.
		foreach($customCases as $case) {
			$this->assertNotEquals($default, GpgKey::getFindConditions($case), "Find conditions missing for case : $case");
		}
	}

	/**
	 * Check checkExpireIsInFuture parameter is null
	 */
	public function testCheckExpireIsInFutureNotNull() {
		$this->assertFalse($this->Gpgkey->checkExpireIsInFuture(null));
	}

	/**
	 * Check checkKeyIsImportable parameter is null
	 */
	public function testCheckKeyIsImportableNotNull() {
		$this->assertFalse($this->Gpgkey->checkKeyIsImportable(null));
	}

	/**
	 * Check checkTypeExist parameter is null
	 */
	public function testCheckTypeExistNotNull() {
		$this->assertFalse($this->Gpgkey->checkTypeExist(null));
	}

	/**
	 * Check checkCreatedIsInPast parameter is null
	 */
	public function testCheckCreatedIsInPastNotNull() {
		$this->assertFalse($this->Gpgkey->checkCreatedIsInPast(null));
	}

}
