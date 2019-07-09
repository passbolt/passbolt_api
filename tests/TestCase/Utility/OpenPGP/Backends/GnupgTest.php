<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.10.0
 */
namespace App\Test\TestCase\Utility\OpenPGP;

use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\OpenPGP\Backends\Gnupg;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\TestSuite\TestCase;

class GnupgTest extends TestCase
{
    use FormatValidationTrait;
    use GpgkeysModelTrait;

    public $originalErrorSettings;

    /** @var Gnupg */
    public $gnupg;

    public function setup()
    {
        $this->originalErrorSettings = ini_get('error_reporting');
        $this->gnupg = new Gnupg();
    }

    public function tearDown()
    {
        $settings = ini_get('error_reporting');
        if ($settings != $this->originalErrorSettings) {
            ini_set('error_reporting', $this->originalErrorSettings);
        }
    }

    public function testGnupgEncryptDecryptSuccess()
    {
        $keys = $this->getDummyGpgkey();
        $this->gnupg->setEncryptKey($keys['public_key_armored']);
        $this->gnupg->setSignKey($keys['private_key_armored'], '');

        $messageToEncrypt = 'This is a test message.';
        $encryptedMessage = $this->gnupg->encrypt($messageToEncrypt, true);
        $this->gnupg->setVerifyKeyFromFingerprint($keys['fingerprint']);
        $this->gnupg->setDecryptKey($keys['private_key_armored'], '');
        $decryptedMessage = $this->gnupg->decrypt($encryptedMessage, true);

        $this->assertEquals($messageToEncrypt, $decryptedMessage);
    }

    public function testGnupgSetEncryptKeySuccess()
    {
        $secKeyPath = Configure::read('passbolt.gpg.serverKey.private');
        $armoredKey = file_get_contents($secKeyPath);
        $result = $this->gnupg->setEncryptKey($armoredKey);
        $this->assertTrue($result);
    }

    public function testGnupgSetEncryptKeyError_NotAnArmoredKey()
    {
        $this->expectException(Exception::class);
        $this->gnupg->setEncryptKey('wrong');
    }

    public function testGnupgSetEncryptKeyError_InvalidArmoredKey()
    {
        ini_set('error_reporting', 0);
        $this->expectException(Exception::class);
        $invalidKey = '-----BEGIN PGP PRIVATE KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

lQcYBFYuIFQBEACpYmcjzX1XC0LPJCMOY/LwxIB3lGfL5+X5kJSfLpWDYKa0XFXv
KuSa6H6LSZGd0nqlLFs1CJoTVQCNVhOBHZWs06Ihs1/+U/t8z1DRhj85Zao9J6tT
HNaK+8oDzWmumaOqseVs+3NDLotjqmiUPWpm6WH1iigL8DzotHSu7x75MZGDM9U1
EMVR38SmJPzcYtQQQBOsg1+HK92TMdSHUc/ILAVUQmH0mlr2EJH7meQtrae3qR4h
YfYTXh1xtFhS1JSCmbR/mCtUJxo12kid6mrU8d8X1xqZ/Q/Yvs8hit8YJgHAVWZZ
W+07sygUonXx4QNwWxIKVznMOM0+k9iNRleT17P2oF0xWjZcc5YTY0h65PU8XcZ0
eB1AjyxGgxODKHEeW4lKqdp14m/QvV33WQhjCO6UisZw0EMP7CeNXNatZ/WKyuOQ
/1oQSb9jxZctoIGaIr4HRj5h7imFzIRvLFmj925TIIS3TRON8LTfFgQ/wo4XvQWY
rsFpmrZwrfpk7tPD3ZmN/lnvwE/TiLg0JsJrUsdS8NmquN+RbHSmHHKJssNRAOqN
KvaKsU/n1+SVcUQhfjbDIrJVkE/QJFNxgWOjnEaoJ5zT86LaOhuLlXw2QbuCpdiq
x08yqLJYP7U2NwZa7h6LdJ5eJ63TId/I599ZfCdPZ0k/8BcJotr/CRo8V/tGxk/D
r4npjtOIiv9Y/9NT+qqvfo7yig3LMvq2v2rUFJjx3hvBybKPW9fyxmBd6pljFL9C
vIqfVMC7+lG7G0AlLWjGEvxEv0hxdoR1pSsVlV8vOskju9ibFHL3jLiDJ9bft+XS
SCzGd0n9Ww6hktJJOR5G+GMfHimWGnYncxchoHMEfZplYZoLxhluXKHG7mPiDARq
9BqUeJjBS1tokc1wWVaeBiwyOcAjbWhDAUNuYpiXY6Oy5MXT38H+IEW8GNC0N9xr
U6k+3bpFJaxExLvPvra5TFLmYUCKeJQbpikZyikGNs0B7jSgox4z3OgDNgqjofN4
E/PU7yYukPKfc75bqWdfCbt6bT6dfl+FFqaKikhEdLO/XQ++iqTWI9I5+QssBMrq
CnZ41Hx3BYLSAhHOxlcReMbbCrJDkylGBMtOutoRyTV0MIEzZ8JajIPP3qX/zxqx
zaZXtuDzZmnTOjWJm895TA==
=DYFc
-----END PGP PRIVATE KEY BLOCK-----
';
        $this->gnupg->setEncryptKey($invalidKey);
    }

    public function testGnupgSetEncryptKeyFromFingerprintError_InvalidFingerprint()
    {
        $this->expectException(Exception::class);
        $this->gnupg->setEncryptKeyFromFingerprint('not a fingerprint');
    }

    public function testGnupgSetEncryptKeyFromFingerprintError_NotFoundFingerprint()
    {
        $this->expectException(Exception::class);
        $this->gnupg->setEncryptKeyFromFingerprint('2FC8945833C51946E937F9FED47B0811573EE67F');
    }

    public function testGnupgAssertGpgMarkerError_NoMarker()
    {
        $this->expectException(Exception::class);
        $this->gnupg->assertGpgMarker('not a marker', Gnupg::MESSAGE_MARKER);
    }

    public function testGnupgAssertGpgMarkerError_NotSameMarker()
    {
        $this->expectException(Exception::class);
        $this->gnupg->assertGpgMarker('-----BEGIN PGP PRIVATE KEY BLOCK-----', Gnupg::MESSAGE_MARKER);
    }

    public function testGnupgAssertGpgMarkerSuccess()
    {
        $result = $this->gnupg->assertGpgMarker('-----BEGIN PGP PRIVATE KEY BLOCK-----', Gnupg::PRIVATE_KEY_MARKER);
        $this->assertTrue($result);
    }

    public function testGnupgAssertDecryptKeyError()
    {
        $this->expectException(Exception::class);
        $this->gnupg->assertDecryptKey();
    }

    public function testGnupgAssertDecryptKeySuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGnupgAssertEncryptKeyError()
    {
        $this->expectException(Exception::class);
        $this->gnupg->assertEncryptKey();
    }

    public function testGnupgAssertEncryptKeySuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGnupgAssertSignKeyError()
    {
        $this->expectException(Exception::class);
        $this->gnupg->assertSignKey();
    }

    public function testGnupgAssertSignKeySuccess()
    {
        $this->markTestIncomplete();
    }

    public function testGnupgIsValidMessageError()
    {
        $tests = self::getGpgMessageTestCases();
        foreach ($tests['test_cases'] as $value => $expect) {
            $result = $this->gnupg->isValidMessage($value);
            $this->assertEquals($expect, $result, __('Armored message test failed: {0}', $value));
        }
    }

    public function testGnupgVerifySuccess()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $fingerprint = $this->gnupg->importKeyIntoKeyring($armoredKey);
        $message = null;
        $this->gnupg->verify($armoredSignedMessage, $fingerprint, $message);
        $this->assertRegExp('/^Signed message/', $message);
    }

    public function testGnupgVerifyError()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key');
        $fingerprint = $this->gnupg->importKeyIntoKeyring($armoredKey);
        $this->expectException(Exception::class);
        $this->gnupg->verify($armoredSignedMessage, $fingerprint);
    }

    public function testIsParsableArmoredSignedMessageSuccess()
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $result = $this->gnupg->isParsableArmoredSignedMessage($armoredSignedMessage);
        $this->assertTrue($result);
    }

    public function testIsParsableArmoredSignedMessageError()
    {
        $armoredSignedMessages = [
            'empty message' => '',
            'no gpg signed mark' => '---- invalid format ----',
        ];

        foreach ($armoredSignedMessages as $message) {
            $result = $this->gnupg->isParsableArmoredSignedMessage($message);
            $this->assertFalse($result);
        }
    }

    protected function getDummySignedMessage($userAlias = '')
    {
        return '-----BEGIN PGP SIGNED MESSAGE-----
Hash: SHA512

Signed message
-----BEGIN PGP SIGNATURE-----

iQJFBAEBCgAvFiEEA/YOlY9MspcjrN92E1O1sV2bBU8FAlqzfJARHGFkYUBwYXNz
Ym9sdC5jb20ACgkQE1O1sV2bBU+yIQ/9FYgjgvbwag9Cxyv4y0wQMOFGC21v8raE
LqT2mH8g7mYt/4n2qQKslMZCKjwraUwPMPyRiAEyt52aWfjh9fIfwvczV3TerqoN
0vtDCv65UY7SNItIuGYFDBrYl9d1a92I1LO3p6bD1mS0FXT1Zg7VPKBtmZHY3Iqr
pRlhtkssgWYtvOsnnO9qnuyH8xXeYbzRO2oDuQrsHnHqQXs+J6Aha7b2W1VqsdQm
jnUwl9Unxb7d2eEOO8Y2w9jV86V88u6qDGpGeDCOXu4M/FZqVbOZyH0PQztQyOH8
SCqW/Q5wGxey42dKOmxHEmroly8ljkd1pMOdAsYU4+8Zjog6h7BmiVQUYKQj+V63
/RnXGH5bCExKmsA7VMEbEruI+6lVIw19iuXikr6s+nwr4m2tmZYro2RMqxBqw+ZH
1wLexpnJ5y5qhKB7b5Nhg6UCIJeUNiFz1yE4C3B9qiO8lmhoNoa2+bPATI/PbKZq
fXMCQ9cC88YoVX6SLv9uV+oErfZ/vp2d59JiUz3/PHNKKr4wG/BDQsa37WLrcAs3
gsv1OnsWRlfCzm417Nvg0mZ+uqTM3lC8B1T9zd6vTaVHyX0xs6qjDNhVuGncFUGW
19OfL7XtvDaK4aR/fMaAM6Vz+cxeFOJEGBGFNJkeU18jIE1EwsmcLt5q7+n+j9Mq
0wIBq1JnEVs=
=l/7T
-----END PGP SIGNATURE-----';
    }

    public function testGnupgSignSuccess()
    {
        $keys = $this->getDummyGpgkey();
        $this->gnupg->setSignKey($keys['private_key_armored'], '');
        $keyInfo = $this->gnupg->getKeyInfo($keys['private_key_armored']);

        $messageToSign = 'This is a test message.';
        $signedMessage = $this->gnupg->sign($messageToSign);

        $messageUnsigned = null;
        $this->gnupg->verify($signedMessage, $keyInfo['fingerprint'], $messageUnsigned);

        $this->assertEquals($messageToSign . "\n", $messageUnsigned);
    }

    public function testGnupgSignError()
    {
        $messageToSign = 'This is a test message.';
        $this->expectException(Exception::class);
        $this->gnupg->sign($messageToSign);
    }
}
