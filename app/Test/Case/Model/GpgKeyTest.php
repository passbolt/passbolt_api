<?php
/**
 * Role Model Test
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @package	   app.Test.Case.Model.RoleTest
 * @since		 version 2.12.7
 * @license	   http://www.passbolt.com/license
 */
App::uses('User', 'Model');

class GpgkeyTest extends CakeTestCase {

	public $fixtures = array('app.user', 'app.gpgkey');

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
		$user = $this->User->findByUsername('user@passbolt.com');
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

//	public function testGeneratedKeyInfo() {
//		$key = '-----BEGIN PGP PUBLIC KEY BLOCK-----
//
//Version: OpenPGP.js v0.7.2
//
//Comment: http://openpgpjs.org
//
//xsBNBFVDHW8BCADBxipSynxBa+vMDDOZa95EiWIk5lRQiP6kKe/fJhcNxynU
//cL6O0kixgGD+8Dl1/JrgUIhYTgQWAu+CeLy+Rfa3mMei3VBAGuBaYuDDDcoF
//aK9H00wsHvzLlbxW8Wr7HkOVQfC73GHbMUi/FQeD/pHEffEYg68ohGLfwSPg
//rGaGKzqVUXcKoiqQSQUo3ryM3RhuXRcN2CLtmem1vCYY3BNSEnKUsQeuaD1c
//ufX8x3kkRqAdQVevZjTuc2Ujw675k69OMmRJ9LBBz1LBYmmq4b9Wr3QXk6Ye
//Ok6esOgd3oyghBby6KgTZ9Q7zJxCK8DJ+Z4hFqP8Qyej960kyl0uOP8RABEB
//AAHNLWtldmluIG11bGxlciA8a2V2aW4ubXVsbGVyQGNsaWNrb25mcmVuY2gu
//Y29tPsLAcgQQAQgAJgUCVUMdcgYLCQgHAwIJEHN6/zDdgn6wBBUIAgoDFgIB
//AhsDAh4BAAAyFQf+LnnEZ/pBupixwavP24kL1kD/kcf0SAq1RDHxrDpRBrr0
//GmX/31XaQJMTSAtYtGLOPFN4L1Go+RGANNv31lY1whBfsVTFzZE0NtcGATQ1
//gdoN2oQy6nCg+NcadaieDC7wiY2VUvhcfFShq4buAiwyPhKltgudxDzem3vB
//g+AEueSLt4ZNRTrD78a7DePJGTvXVXRawzHO1xf8toLNlLSOKi+uDx0LFSRJ
//gN8/rrvS6D1ieMBAsSAau5kQV7P2VzUNZ++8UxESBpaZ2p01aBfyXnehwyiR
//iXq0xs3kg64cVsFDh6RqgtBVCyb11pL/hCum51w5Wf4ysSulCbbdF2Xicc7A
//TQRVQx1yAQgAipF8cm2pG8G91PCrnGRqMuqK5i3Rdk69r2XWE9B/sUX/67jM
//IbgC8EOWQglXERIuVdjBCoB8FEzJ8pYfsjuQeXfTHf1T8cjOtGy+aqFI5cL1
//f2A6ZDHVp4CjiB4OLon+GjonrCtk+fD6JdjqLLFP8OsGo8EQxOnJFMyMK3zf
//Qz6KedJWftwJb5r1NsAh9pmmCMfgS/rGgo7gm4ckMWkthoiSpDPRrCydEh4J
//zd3+FLvqpz9hCMUt349Qrp6XeBfIQbeoiw3jx3W8xTRTo4/O8of2qowMud6q
//hwy5EOkzpzplPZ+E/j5Z0Fr+D4fq4UvUGTPS9g6CHLN6MVR5bh107QARAQAB
//wsBfBBgBCAATBQJVQx10CRBzev8w3YJ+sAIbDAAAEu8H/0kgOGE1QudDEALT
//fCqhk1apPAOMLgckwWTP+BvlcHCwTWvWbUWMhTl1fgM2zczTjl6jT4kRonxU
//UW7yD+DURmgMrbZyVctwLOpXub9852jeF8a2NcwwwfYKvLykYTX8qZE/cqwz
//DtxeOb5Zmux5HrfYdHxwu2pokBd1LEm1JiY5Tg88V5AQNwSfPP07oSaVKHsW
//bkmapRxg7uI7FsQKExnH5ZHfmLTHY+ClO7YQnR7iI5xQAmy9GI2k4IeOAKzx
///s+rUKbBol5+ArbsJX7Yexf78+1pEnrOj1VvZn9GuVFj0iU9c8M8b1a8K41n
//JV+d0BnpZzkVpti7wRZ0pijpC5o=
//
//=DdAr
//
//-----END PGP PUBLIC KEY BLOCK-----';
//		$info = $this->Gpgkey->info($key);
//		// TODO : complete test
//	}

	/**
	 * Test UserId Validation
	 * @return void
	 */
	public function testBitsValidation() {
		$testcases = array(
			'' => false,
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
			'<user@passbolt.net>' => true,
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
			'000876RF' => true,
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
			'120F87DDE5A438DE89826D464F8194025FD2D92C' => true,
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
	 * Test Expires Validation
	 * @return void
	 */
	public function testExpiresValidation() {
		$testcases = array(
			'' => true,
			'1980-12-14 00:00:00' => false,
			'2015-12-14 00:00:00' => true,
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
		$testcases = array(
			'' => false,
			'1980-12-14 00:00:00' => true,
			'2015-12-14 00:00:00' => false,
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
}
