<?php
/**
 * Gpg Utility Test
 *
 * @copyright	 Copyright 2012, Passbolt.com
 * @package	     app.Test.Case.Utility.GpgTest
 * @since		 version 2.12.7
 * @license	     http://www.passbolt.com/license
 */

// Uses Gpg Utility.
App::import('Model/Utility', 'Gpg');

class GpgTest extends CakeTestCase {

	/**
	 * Test if the constructor is working
	 * @return void
	 */
	public function testConstruct() {
		$gpg = new \Passbolt\Gpg();
		$this->assertTrue( is_object($gpg) );
		$this->assertTrue( $gpg instanceof \Passbolt\Gpg );
	}

	/**
	 * Test setEncryptKey().
	 */
	public function testSetAddEncryptKey() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Set encrypt key.
		$setKey = $gpg->setEncryptKey($publicKey);

		// Assert that the operation was successful.
		$this->assertTrue( $setKey );

		// Assert that the key info is populated.
		$this->assertTrue( !empty($gpg->encryptKeyInfo) );
	}

	/**
	 * Test setDecryptKey().
	 */
	public function testSetDecryptKey() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private.key');

		// Set decrypt key.
		$setKey = $gpg->setDecryptKey($publicKey);

		// Assert that operation was successful.
		$this->assertTrue( $setKey );
		$this->assertTrue( !empty($gpg->decryptKeyInfo) );
	}

	/**
	 * Test setSignKey().
	 */
	public function testGetKeyMarker() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Get key marker.
		$keyMarker = $gpg->getKeyMarker($publicKey);

		// Assert that the marker has the right format.
		$this->assertEquals($keyMarker, 'PGP PUBLIC KEY BLOCK');
	}

	/**
	 * Test isValidKey().
	 */
	public function testIsValidKey() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Assert that isValidKey return true.
		$validKey = $gpg->isValidKey($publicKey);
		$this->assertTrue($validKey);
	}

	/**
	 * Test isValidKey().
	 */
	public function testIsValidKeyWithInvalidData() {
		$gpg = new \Passbolt\Gpg();

		$data = [
			'',
			'testest',
			'-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

wrong content
-----END PGP PUBLIC KEY BLOCK-----'
		];

		// Foreach invalid key, assert that the function returns false.
		foreach($data as $key) {
			$validKey = $gpg->isValidKey($key);
			$this->assertFalse($validKey);
		}
	}

	/**
	 * Test getKeyInfo().
	 */
	public function testGetKeyInfo() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Get key info.
		$keyInfo = $gpg->getKeyInfo($publicKey);

		// Assert that the key info returned is conform to what we expect.
		$expected = [
			'fingerprint'   => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
			'bits'          => 4096,
			'type'          => 'RSA',
			'key_id'        => '5D9B054F',
			'key_created'   => 1439124511,
			'uid'           => 'Ada Lovelace <ada@passbolt.com>',
			'expires'       => 1565354911
		];
		$this->assertEquals($keyInfo, $expected);
	}

	/**
	 * Test getKeyInfo() with openpgpjs key.
	 */
	public function testGetKeyInfoOpenpgpjs() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_openpgpjs_nopassphrase.key');

		// Get key info.
		$keyInfo = $gpg->getKeyInfo($publicKey);

		// Assert that the key info returned is conform to what we expect.
		$expected = [
			'fingerprint'   => '051A166E300DAD845B255E37CF77639281B5479F',
			'bits'          => 2048,
			'type'          => 'RSA',
			'key_id'        => '81B5479F',
			'key_created'   => 1446130121,
			'uid'           => 'ada lovelace <ada@passbolt.com>',
			'expires'       => ''
		];
		$this->assertEquals($keyInfo, $expected);
	}

	/**
	 * Test getKeyInfo() with a weird key.
	 * The key seems valid, but for some reason Openpgp-php can't read the uid from it.
	 * So for now we treat it as an exception.
	 *
	 * @throws Exception
	 */
	public function testGetKeyInfoFromWeirdKey() {
		$key = '-----BEGIN PGP PUBLIC KEY BLOCK-----

Version: OpenPGP.js v0.7.2

Comment: http://openpgpjs.org

xsBNBFVDPCsBCACEoD8M8/OWckxRtN4dlB/LzDXQLKYtKprCSwXq9adTiTmI
S7QjVyL01j3e8mWw2rM8qQPf8Tcc2sXw6JoQD85Ul87W887ruSG9yeV/1rjh
m34q3ZMMtudwuZnwrFRyMHOonbllZ6nC+ikhW7yOtEjPjGU5IUAeOHZuGdB1
loCtJFrmL1yI5VqXa/5dCPMnuq3ivwG08AzKdDJj4FtDwtPqu8gwiiBMTIvc
ye3FNr1UzY8shCBeCM+c0rbeXmnymz/cnPsoaYmPk/wCE6jDQZvN7aqnYoXz
s5QBSIXhmjoF1R9pXJWFwWBWyiUUWd3+HtyqN39DE11Zufnmv/HL0RfDABEB
AAHNLWtldmluIG11bGxlciA8a2V2aW4ubXVsbGVyQGNsaWNrb25mcmVuY2gu
Y29tPsLAcgQQAQgAJgUCVUM8LQYLCQgHAwIJEBBHV2HtBkBqBBUIAgoDFgIB
AhsDAh4BAAA47gf/c7ImdYBOsQnptgtLnMpQxkvkMdeYPtBpF89QWwy6HIHP
pO9KfBDC44/K1+RT4UUGx5HsdUPGQGrDUp1RttCyykNsy2dduhkFTl6fap59
Zalk6jUkkJ7aVapgFhKhCsiIyhuR/DBEi+kTX4YE8OvPsAKHRc+tutUNX8hv
16CUKIoZpNbSiKeSlDHrUsE3tsYUo00n79Jmcudh/mMkul21B31tMXE4Kn3+
pVoRAuS16OVNgiA4B87Gowy9Ze2MR+f5c6M7vQSgY8L24itHTgmNsmewAHSQ
9XYjlQ8HeU2xUcG/tYklrI2bZ4Mtp26iKhGHb0ZLq8NoITtidm1A693tTc7A
TQRVQzwuAQgAjv2zqTpq4pg+46T+rQWhOTSZNtafslTgbMWmp8nZ0nXKo0xr
+Eui7SWtDKBszC6HpFiF/RWEgtpwzuuLP4OcF4a8+PLh2yUBBTqwJcn3NroU
baa8YgqYnfhgfDePWSJlXZlujnVJMT+E0W1zajvP8EYc0zhG5hgE0CO/U2K4
SBZV37fCDjKtIbHJd7jJtry/BPpyoLKIWDXmhw/PhLazL7iysFu+0QOt6eFK
/SUG3kyjJ7qo1e6kQ301U9ezGE87pHbS6/zAvtFPo2+5PWwAc/y/Ty8PYEKH
G6HYzpd6EqW0J0x1W6E9JzXGU/L2QWIc+KwfnDMVgZAnt40CsrnNrQARAQAB
wsBfBBgBCAATBQJVQzw0CRAQR1dh7QZAagIbDAAAp48H/jxI1rj9IEMYiWVR
KocPnXQ9BDkCX6Ty8tOn/e8i7Mxpiml1GX7pxigbSI1Si2uWayl7TH572M7Q
mnw1ThlvpMTjDdI1rdG6QgVEjzd3cHgH1yr2i+sduABTbfwxBSjzcItSs6yk
dV40FLmlknJY9iXjsVnnjK0k3V3TtfEL/f11EKTy4U58Fz+IupgTj7fOKbhj
tpWQFHMWY2wRxXGn+93Nrrv30nE59M7V8F4/mVTpH2buEKoukxgxoTGaSXeA
sJAhE5sAtWpAEh09OfArauZNXdvZ+RPscSU3X2MyYkh4uaT722knDHeqj/48
Y163Zeuqb7k4oayBB2o188VJy/E=

=nhkC

-----END PGP PUBLIC KEY BLOCK-----';

		$gpg = new \Passbolt\Gpg();

		$this->setExpectedException('Exception', 'Invalid key');

		// Get key info.
		$gpg->getKeyInfo($key);
	}

	/**
	 * Test importKeyIntoKeyring().
	 */
	public function testImportKeyIntoKeyring() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Import into keyring.
		$fingerprint = $gpg->importKeyIntoKeyring($publicKey);

		// Assert that the key fingerprint returned is the right one.
		$this->assertEquals($fingerprint, '03F60E958F4CB29723ACDF761353B5B15D9B054F');
	}

	/**
	 * Test encrypt().
	 */
	public function testEncrypt() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');

		// Encrypt message.
		$gpg->setEncryptKey($publicKey);
		$encrypted = $gpg->encrypt("this is a clear message");

		// Assert that the message has been encrypted.
		$this->assertTrue(preg_match('/BEGIN PGP MESSAGE/', $encrypted) == true);
	}

	/**
	 * Test decryption without a passphrase.
	 */
	public function testDecryptNoPassphrase() {
		// Define pre encrypted message.
		$message = '-----BEGIN PGP MESSAGE-----
Version: GnuPG v2

hQEMAxze6z9p8rK1AQgApWbX3sTlR/yNpI5I04AftbojBe8ULg9+r7OLYxlbg/lQ
HvQGWT1a8iH4Zw1DcjB+oYxHdaTFwsG0QMeKGRl+tghyKKmlWGSeGWkeHOZPbsXB
cE/FOyRkMG0ghUnlo0PBWKqL9KFmlcNvc/F5VH4H4GiRR0x3+qYhpiLRpFfe49t2
oc4/iqws8GcQ2jBWzb5qlhEYE8YqQxtz/HlPbOtlURwoB7dmPNcmP+5XxfYar68y
L3fyHyHSRrtW8Lvb8pJlzScUIOUNwjzc8BGelG64In+4HEOVia3jcBUn0JGdPAfu
nOm1PMuTKj5bj0RHFnZBscIIS2lo/SlxcrvL0uZ8oNJQAThXPKsbnC6GVqYzLI3P
Ek/ZD5H6fGVm7t9GKDgwRrNQNFsqerx7HvyOj+C0bRnii87MzFykc9dOOVHywudM
LUbRh6UyogY9wzlgcqgPFKk=
=HMm1
-----END PGP MESSAGE-----';


		// Get key for ada.
		$privatePublicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');

		// Instatiate Gpg.
		$gpg = new \Passbolt\Gpg();

		// Set decrypt key.
		$gpg->setDecryptKey($privatePublicKey);

		//Decrypt message.
		$decrypted = $gpg->decrypt($message, '');

		// Assert that the message decrypted is the same as original.
		$this->assertEquals($decrypted, 'this is a clear message');
	}

	/**
	 * Test encrypt with signature.
	 */
	public function testEncryptSign() {
		$gpg = new \Passbolt\Gpg();
		// Get ada keys.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public.key');
		$signKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');

		// Set keys for encryption and signature.
		$gpg->setEncryptKey($publicKey);
		$gpg->setSignKey($signKey);

		// Encrypt and sign.
		$encrypted = $gpg->encrypt("this is a clear message", true);

		// Assert that the encryption happened.
		$this->assertTrue(preg_match('/BEGIN PGP MESSAGE/', $encrypted) == true);
	}

	/**
	 * Test verification of encrypted text with signature.
	 */
	public function testEncryptDecryptSign() {
		$gpg = new \Passbolt\Gpg();

		//Get keys.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');
		$signKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');
		$privateKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');

		// Original text to be encrypted.
		$originalText = "this is a clear message";

		// Encrypt and sign messages.
		$gpg->setEncryptKey($publicKey);
		$gpg->setSignKey($signKey);
		$encrypted = $gpg->encrypt($originalText, true);

		// Decrypt and verify key.
		$signatureInfo = [];
		$gpg->setDecryptKey($privateKey);
		$decrypted = $gpg->decrypt($encrypted, '', true, $signatureInfo);
		$privateKeyInfo = $gpg->signKeyInfo;

		// Assert that the signature fingerprint is the same as the private key fingerprint.
		$this->assertEquals($signatureInfo[0]['fingerprint'], $privateKeyInfo['fingerprint']);

		// Assert that the decrypted text is same as original.
		$this->assertEquals($decrypted, $originalText);
	}

	/**
	 * Test verification of encrypted text with signature, using openpgpjs keys.
	 */
	public function testEncryptDecryptSignWithOpenpgpjsKey() {
		$gpg = new \Passbolt\Gpg();

		//Get keys.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_public_openpgpjs_nopassphrase.key');
		$signKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_openpgpjs_nopassphrase.key');
		$privateKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_openpgpjs_nopassphrase.key');

		// Original text to be encrypted.
		$originalText = "this is a clear message";

		// Encrypt and sign messages.
		$gpg->setEncryptKey($publicKey);
		$gpg->setSignKey($signKey);
		$encrypted = $gpg->encrypt($originalText, true);

		// Decrypt and verify key.
		$signatureInfo = [];
		$gpg->setDecryptKey($privateKey);
		$decrypted = $gpg->decrypt($encrypted, '', true, $signatureInfo);
		$privateKeyInfo = $gpg->signKeyInfo;

		// Assert that the signature fingerprint is the same as the private key fingerprint.
		$this->assertEquals($signatureInfo[0]['fingerprint'], $privateKeyInfo['fingerprint']);

		// Assert that the decrypted text is same as original.
		$this->assertEquals($decrypted, $originalText);
	}
}