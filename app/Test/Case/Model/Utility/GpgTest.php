<?php
/**
 * Gpg Utility Test
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

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

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}

	/**
	 * Test setDecryptKey().
	 */
	public function testSetDecryptKey() {
		$gpg = new \Passbolt\Gpg();

		// Get key for ada.
		$privateKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private.key');

		// Set decrypt key.
		$setKey = $gpg->setDecryptKey($privateKey);

		// Assert that operation was successful.
		$this->assertTrue( $setKey );
		$this->assertTrue( !empty($gpg->decryptKeyInfo) );

		// Cleanup.
		$privateKeyInfo = $gpg->getKeyInfo($privateKey);
		$gpg->removeKeyFromKeyring($privateKeyInfo['fingerprint']);
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
	 * Test getKeyInfo() with a key without self signature. (found one while testing manually).
	 * It should be possible to read this key, but for some reasons, our libs can't.
	 *
	 * @throws Exception
	 */
	public function testGetKeyInfoWithoutSelfSignature() {
		$key = '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

xsBNBFY6IcsBCACMAsB+QcV0K1m5A4UpjnLF/fGAWGXzTJitcnrA/iILPOK9
879gmlIP7ZwExTJfsyvDx51b5Qx3LmU7w1neA2LyNJZ3/f/EbLt8v4ej7l3n
FWdL1iH9yoA6+h0RXwzE+eIsE4ulEkKuGAMTVtUshiRKqUlzhnLFK1YrC8EX
UPt5gQ92W2+yhKvnBwohlpi4yyIoFR6IGBDD9tD2fCT9v3vE9X40f+qtoo8+
KcBnoGjxgPvKdZTGmiuo4phEqm3fbckzsxxJuqr2WizaOY3at7Mm7263brQT
CnTkiXPJXnuyBp21HWzyRkOsm3q30oDwfZaeGMGJGEb0oMKeOwJfB8dpABEB
AAHNHmtldmluIG11bGxlciA8a2V2aW5AdGVzdDcuY29tPsLAcgQQAQgAJgUC
VjohzQYLCQgHAwIJEB9NiTAZf8QpBBUIAgoDFgIBAhsDAh4BAABPdQf/Y6N4
UgsbjdU35EoGK592Nv5yGBCVlDqlypfdfNfHzlx3WECXTdyJnut1JbjF44JO
MwOohGwXczSJUPNfoAvCydh4K2z9D2x9YoKW9C0NFmzCN3gM6p/z87F4nIqe
OdhGJM6e9qQiTB9tc++UxaRGyCQ3KbX0CMVox86JHEkTm2T0pWwv3nGk0GUq
kaJ4GMLvDFLBZ+vCNodVa7D+phKlQzVDAZ2+78ppySRp++zc1VgettmHt4sm
V83dtEa5XhSFfefuvySQI/AV9w53Xu7t5e2Dx/bhxXvPu7w19H/jmI7xpLe9
trx8QbRJNGWtANTYxfsqa+3iiPRJBR5ddjMDRM7ATQRWOiHNAQgAg2AGo1uR
i8TxXdmW7jR2rTsTQvSUXRI0wImjBfvW+Pm6CmUg7Hv+3OdWwaddEK0i1aGz
BviS9KR35iJvsX9GsjF3pnVZSdhSHVY1R/iJkEDq+SJMd1canll/gF/WLzYt
s8M5VwsAO88phgPwo4W+jnDkJYxMKMBOtyVNriDvVwi8GNvI62kuVwHtm9YJ
PpV9XS6RnTMbszxNTnFjS3y1JzwlblecX8LsJmEwb0PVHTvhvqyn8Y0u/d+1
Diu/GcbN4SUlqtOU5qYjRTo4WzjWQKYb+kCEf/yhddv0orHT1KFEW/D20fKn
N4x1rRQEh2WDPmkwoHTBInr/S0h2n1055QARAQABwsBfBBgBCAATBQJWOiHV
CRAfTYkwGX/EKQIbDAAAC9QH/joSDkQtDBJv2L/PzTkIhvCypUPzrTpW0Qja
V3hrLV4cFlR1LeYhQVS/Br2jfUU9Ui3udNBl8qGXK9teQzDFGNOpwgxGbZll
2yBGIexb6ZgV2N6Dxj4UUPHx9hSNUPqJ+WoHaJhYZEeJs7kW068XvPTo5ojI
+FcGv60jFbAU4cl37jcdnPnYTLR7/PZjRleM5qrebOUnDOWUzuRf4NBb/hqy
Qxuk8eQ4dXbx2lhQC30TkeENOXiRId/Iiaw/07C8j0p8nkjtVM13C/OU2BcE
wySkFyWzuvYnPEIabBTk7PGwhbzoeIHG31o6ZtuiWGCg4AWIYpchPhuunPwE
wJffl4U=
=mK9R
-----END PGP PUBLIC KEY BLOCK-----';

		$gpg = new \Passbolt\Gpg();

		// Get key info.
		$keyinfo = $gpg->getKeyInfo($key);
		$this->assertEquals($keyinfo['bits'], '');
		$this->assertEquals($keyinfo['type'], '');
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

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}

	/**
	 * Test encrypt().
	 */
	public function testEncrypt() {
		$gpg = new \Passbolt\Gpg();

		// Get key for jean.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_public.key');

		// Encrypt message.
		$gpg->setEncryptKey($publicKey);
		$encrypted = $gpg->encrypt("this is a clear message");

		// Assert that the message has been encrypted.
		$this->assertTrue(preg_match('/BEGIN PGP MESSAGE/', $encrypted) == true);

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}

	/**
	 * Test decryption without a passphrase.
	 */
	public function testDecryptNoPassphrase() {
		// Define pre encrypted message.
		$message = '-----BEGIN PGP MESSAGE-----
Comment: GPGTools - https://gpgtools.org

hQIMA1P90Qk1JHA+ARAAq2Ms52xS8kYngLXX2kx4BnAzHky1gGx4EKq2k0cTNUB2
BtE+bg7nKE7KF0oFMYrw1LKchncyHvuIGpH5ZyDvP9pdjvXyeTZQJDt/IPeQLIGh
CAqoPihIS4Aul0v0wSswDHF0A6hPgwA/9mizSumZGYh+YcGDO+fhNHqZZPjZcTO0
yssOnkOJYb4W19yPs/5n6QWW350QL4vND0LhOJ5vGJm08UwASB8xtwitXv6JGvm3
ciuoTjdZ3ZCn/dgsQ1IKqdpqTD00yjK57WKePeUE5DCbxIprO4WPb1aLeoy4Zy1D
krhV+Z3jodqhpAU49ZK3QdEYaJGpy51LyqhNFy/7ZdF7qI8h8nMFBIdBXwKoG0/+
aHFAQGWRUqpcTZKlQ9WpVZczYcpovG4FdUDA120Q/mHLk+9N4y+SKR/vmeK0/zk1
iDKGW5tZP04V82sw7wQb6OTIcKr0sClBA7Sevc2YsX7/WG/6yR60xz5z4f0q9n8d
Dxv19RpRPQgdO/3xkfv7SU9ycsS7iInGNqpJ/WhlBvukn6cXPvDsHaAH025fi4F9
qiSm+gDz29CZwbKleo8BeHaY4h2sYc2ZVopfbEp5chMrI0sb/ykEYGNKncOE5UHr
JeQSeMVQrEVjbyLK1v0et6rdxcWL10RFYVobbZER7kMHuGFI5VTKlykHcESgND3S
WQF8dlKcsjgcqdod4XZGg5Or4rH2O98LJhOsBARPhPNME0/DmBbC8Zb4Kbgv9S+U
mygzWA1RoY7LFa9BN0vndsut8Y33Hf3tjJXWb2u1dlmlB/NSWRzTjG09
=bsQE
-----END PGP MESSAGE-----';

		// Get key for ada.
		$privateKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key');

		// Instantiate Gpg.
		$gpg = new \Passbolt\Gpg();

		// Set decrypt key.
		$gpg->setDecryptKey($privateKey);

		//Decrypt message.
		$decrypted = $gpg->decrypt($message, '');

		// Assert that the message decrypted is the same as original.
		$this->assertEquals($decrypted, 'this is a clear message');

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($privateKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}

	/**
	 * Test encrypt with signature.
	 */
	public function testEncryptSign() {
		$gpg = new \Passbolt\Gpg();
		// Get jean keys.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_public.key');
		$signKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_private_nopassphrase.key');

		// Set keys for encryption and signature.
		$gpg->setEncryptKey($publicKey);
		$gpg->setSignKey($signKey);

		// Encrypt and sign.
		$encrypted = $gpg->encrypt("this is a clear message", true);

		// Assert that the encryption happened.
		$this->assertTrue(preg_match('/BEGIN PGP MESSAGE/', $encrypted) == true);

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}

	/**
	 * Test verification of encrypted text with signature.
	 */
	public function testEncryptDecryptSign() {
		$gpg = new \Passbolt\Gpg();

		//Get keys.
		$publicKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_private_nopassphrase.key');
		$signKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_private_nopassphrase.key');
		$privateKey = file_get_contents(Configure::read('GPG.testKeys.path') . 'jean_private_nopassphrase.key');

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

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
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

		// Cleanup.
		$publicKeyInfo = $gpg->getKeyInfo($publicKey);
		$gpg->removeKeyFromKeyring($publicKeyInfo['fingerprint']);
	}
}