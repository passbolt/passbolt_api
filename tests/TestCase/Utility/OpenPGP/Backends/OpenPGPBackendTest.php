<?php
declare(strict_types=1);

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
namespace App\Test\TestCase\Utility\OpenPGP\Backends;

use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use App\Utility\OpenPGP\Backends\Gnupg;
use App\Utility\OpenPGP\OpenPGPBackend;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\TestSuite\TestCase;
use Exception;

/**
 * @covers \App\Utility\OpenPGP\OpenPGPBackend
 */
class OpenPGPBackendTest extends TestCase
{
    use FormatValidationTrait;
    use GpgkeysModelTrait;

    public string|false $originalErrorSettings;

    public function setUp(): void
    {
        parent::setUp();
        $this->originalErrorSettings = ini_get('error_reporting');
    }

    public function tearDown(): void
    {
        $settings = ini_get('error_reporting');
        if ($settings != $this->originalErrorSettings) {
            ini_set('error_reporting', $this->originalErrorSettings);
        }
        parent::tearDown();
    }

    public static function openPGPBackendProvider(): array
    {
        return [
            [new Gnupg()],
        ];
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendEncryptDecryptSuccess(OpenPGPBackend $gnupg): void
    {
        $keys = $this->getDummyGpgkey();
        $gnupg->setEncryptKey($keys['public_key_armored']);
        $gnupg->setSignKey($keys['private_key_armored'], '');

        $messageToEncrypt = 'This is a test message.';
        $encryptedMessage = $gnupg->encrypt($messageToEncrypt, true);
        $gnupg->setVerifyKeyFromFingerprint($keys['fingerprint']);
        $gnupg->setDecryptKey($keys['private_key_armored'], '');
        $decryptedMessage = $gnupg->decrypt($encryptedMessage, true);

        $this->assertEquals($messageToEncrypt, $decryptedMessage);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSetEncryptKeySuccess(OpenPGPBackend $gnupg): void
    {
        $secKeyPath = Configure::read('passbolt.gpg.serverKey.private');
        $armoredKey = file_get_contents($secKeyPath);
        $result = $gnupg->setEncryptKey($armoredKey);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSetEncryptKeyError_NotAnArmoredKey(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->setEncryptKey('wrong');
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSetEncryptKeyError_InvalidArmoredKey(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
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
        $gnupg->setEncryptKey($invalidKey);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSetEncryptKeyFromFingerprintError_InvalidFingerprint(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->setEncryptKeyFromFingerprint('not a fingerprint');
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSetEncryptKeyFromFingerprintError_NotFoundFingerprint(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->setEncryptKeyFromFingerprint('2FC8945833C51946E937F9FED47B0811573EE67F');
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertGpgMarkerError_NoMarker(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->assertGpgMarker('not a marker', Gnupg::MESSAGE_MARKER);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertGpgMarkerError_NotSameMarker(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->assertGpgMarker('-----BEGIN PGP PRIVATE KEY BLOCK-----', Gnupg::MESSAGE_MARKER);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertGpgMarkerSuccess(OpenPGPBackend $gnupg): void
    {
        $result = $gnupg->assertGpgMarker('-----BEGIN PGP PRIVATE KEY BLOCK-----', Gnupg::PRIVATE_KEY_MARKER);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertDecryptKeyError(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->assertDecryptKey();
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertDecryptKeySuccess(OpenPGPBackend $gnupg): void
    {
        $keys = $this->getDummyGpgkey();
        $gnupg->setDecryptKey($keys['private_key_armored'], '');
        $encrypted = '-----BEGIN PGP MESSAGE-----

hQIMAxWrZ+ffF0kbAQ/7Bbn3FDqVhUygbt2GuT/zZYJWbHLpxzHKS0Thn5sZeusp
W46co6ehOTTUOelK/8ODSAZo/7VHjqEhYdtonwBxTVqAfk9as3ffNlr2CTyUdlRD
1Rr7zj8zHKDGFaeA6M8oKR+gnIIweiCL9xhpSXZJdad+lC9862Ws0XekhqdMmckn
PZTJFyEOG6KUSlOgsHWDr4iDcGLSf6/6+R+/apTEFV8m6eAQLZW1pmFPQfMwdjdI
52I9aNoW7Eafn581ER/WeJkyX6VGUQBkEgph13tB3JB4V9NNxxllqBzdY5cH2xO/
6kRnIz722NZ7lgGjJz5zIUmV6aFwH5jgZWhLN6gwKRJuGdqb7ncMxxMqNTvv4Hkk
HFrl7m9XjAR9I4+mXTEqbD1w9JjBws4lXdschLHOKZjUrziAmSChBsegxPTm8mpI
YXhFPsLCkC3jaPj9TeTIgSemuxmmQMzjeHj+RpPVciNcFFv3tfMF9WM6JiEsuVeR
0io2rqHzEMBLhbmDIPQ4nsTWyVxWswqbzleMGcUUfgUwXyRJDD56M6kpXY7BI5lh
Z1pKGSbzO72RX9jSynanDhhv/BeIPklmSLKfqBZtG/y7x8b/HQJ1ugA2F7vymW3k
zi/cSx3JwgsAplFPGUTdGxX9Ht+EtP6GXfb2rCAmMNDUTc4kqP6LbObk3ib1PLrS
PwGOkDyjWQT0cvmL+P9lWaGwNwtqtxYtTiEoYS4fYK0sRjkFSrKsserkND3Ad/ol
p7hokpGnpTQXl9C5Oi/+uQ==
=lMs6
-----END PGP MESSAGE-----';
        $this->assertEquals('test', $gnupg->decrypt($encrypted));
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertEncryptKeyError(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->assertEncryptKey();
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertEncryptKeySuccess(OpenPGPBackend $gnupg): void
    {
        $keys = $this->getDummyGpgkey();
        $gnupg->setEncryptKey($keys['public_key_armored']);
        $encrypted = $gnupg->encrypt('test');

        $this->assertStringNotContainsString('test', $encrypted);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertSignKeyError(OpenPGPBackend $gnupg): void
    {
        $this->expectException(CakeException::class);
        $gnupg->assertSignKey();
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendAssertSignKeySuccess(OpenPGPBackend $gnupg): void
    {
        $keys = $this->getDummyGpgkey();
        $gnupg->setSignKey($keys['private_key_armored'], '');
        $signed = $gnupg->sign('test');
        $this->assertNotEquals('test', $signed);
        $this->assertStringContainsString('test', $signed);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendIsValidMessageError(OpenPGPBackend $gnupg): void
    {
        $tests = self::getGpgMessageTestCases();
        foreach ($tests['test_cases'] as $value => $expect) {
            $result = $gnupg->isValidMessage($value);
            $this->assertEquals($expect, $result, __('Armored message test failed: {0}', $value));
        }
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendVerifySuccess(OpenPGPBackend $gnupg): void
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $fingerprint = $gnupg->importKeyIntoKeyring($armoredKey);
        $message = '';
        $gnupg->setVerifyKeyFromFingerprint($fingerprint);
        $gnupg->verify($armoredSignedMessage, $message);
        $this->assertMatchesRegularExpression('/^Signed message/', $message);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendVerifyError(OpenPGPBackend $gnupg): void
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key');
        $fingerprint = $gnupg->importKeyIntoKeyring($armoredKey);
        $this->expectException(CakeException::class);
        $gnupg->setVerifyKeyFromFingerprint($fingerprint);
        $gnupg->verify($armoredSignedMessage);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testIsParsableArmoredSignedMessageSuccess(OpenPGPBackend $gnupg): void
    {
        $armoredSignedMessage = $this->getDummySignedMessage('betty');
        $result = $gnupg->isParsableArmoredSignedMessage($armoredSignedMessage);
        $this->assertTrue($result);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testIsParsableArmoredSignedMessageError(OpenPGPBackend $gnupg): void
    {
        $armoredSignedMessages = [
            'empty message' => '',
            'no gpg signed mark' => '---- invalid format ----',
        ];

        foreach ($armoredSignedMessages as $message) {
            $result = $gnupg->isParsableArmoredSignedMessage($message);
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

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSignSuccess(OpenPGPBackend $gnupg): void
    {
        $keys = $this->getDummyGpgkey();
        $gnupg->setSignKey($keys['private_key_armored'], '');
        $keyInfo = $gnupg->getKeyInfo($keys['private_key_armored']);

        $messageToSign = 'This is a test message.';
        $signedMessage = $gnupg->sign($messageToSign);

        $messageUnsigned = null;
        $gnupg->setVerifyKeyFromFingerprint($keyInfo['fingerprint']);
        $gnupg->verify($signedMessage, $messageUnsigned);

        $this->assertEquals($messageToSign . "\n", $messageUnsigned);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendSignError(OpenPGPBackend $gnupg): void
    {
        $messageToSign = 'This is a test message.';
        $this->expectException(CakeException::class);
        $gnupg->sign($messageToSign);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendParsePublicECCKey(OpenPGPBackend $gnupg): void
    {
        // ECDH (Encrypt only) - curve25519
        // Algorithm 22?
        $pkey = '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQENBFv/eJgBCADDkW8IYwHmaQXWw5Dce9OzPH4N5NMuhdgli286ADBH3/Tkfi96
2SBtcf3sOfw0yNXlFU9F2yv9c+pAsjL+TUveTotCcKp3GflT4qCKbTTj2Vt09m8z
8nrZUwe05szcWqnCKCh7sBGQlFG3GkiH42QQ7kqE0vuEa08eSYUhBWYsK28hBtUJ
sXC2iP4sNymC+0DmzpdJ6DjZJUpoHnk77B1IvPAPTDo/jFH4/PwAMoi4khPvFjMJ
gKq40exIL/a60osYZN1D2KrawEjPRo3jJslrr6F2OwBJ77bTLCScHLxRmE3LOULp
YhkHx1A6GmVzZoF0BIBTKfWk21lM9BhI7wXxABEBAAG0I1Bhc3Nib2x0IGR1bW15
IDxkdW1teUBwYXNzYm9sdC5jb20+iQFUBBMBCAA+FiEENlfUAuY5Y5ZX4xTR7Hu+
/5sJExsFAlv/eJgCGwMFCQeGH4AFCwkIBwIGFQoJCAsCBBYCAwECHgECF4AACgkQ
7Hu+/5sJExsefQgAkW+m4AAE1skaUol2StivuwDaO5ncpo25YC9+jg8TTRlUq7qq
cM1Dfys+7G5leOLNrIA98e+Rv3gtlLy3UevGVRN4R3iRhV7A9bgb3o/rQR2dVI3P
XEkB2iKGY/YsmayebzaMwY2rWhYrqJC4VDkAiLP7nC1xFDkBvzGvIxg/fJWi0eiv
NbQ/ztZla1ZctxttNRejDyLWzDgvR0aruv2+rRbO++QzrLEXv/NThD4Iy8diHM48
QoVWKwKOgHNorNYi4zeBOycP6KJ3No0oOOvnQ1dMa8EUee7FEgDp9pZ7TKpcC2P0
FEkjd4HDiLYZ0ppci0VAd4eLKddUbtEoseEYKrkBDQRb/3iYAQgA1SxFmNm4Byys
7MFXebJsh9TfYDcS0wnAXKy6frABFS1O/e35djH5Emr9xKTFVQn9VouJ6jd5WDCg
oplssKLC1izItQePe2p6JLP4p+Zv23MfsluyEEjlHviT/VOwGCYXuYjKgqrHd/Uj
XPKijsrLKH2BIXWB1Zt8gHxS8StL+632SXT3ZONETf7nKKnHosIxa8ATBm9Ncr1Z
aqahQmuOFqqyVw1U34vznBz8Xx009h39oKkJTymUXEzb/rYCdo6iKLSO6NqpG2Gz
0H8wl2q6KiG84hcSEFiJ6t9m8U9iq08PxSyV8AUaY950Pa0yI/8AkX+yxLEXkHNF
tbptB0fKPQARAQABiQE8BBgBCAAmFiEENlfUAuY5Y5ZX4xTR7Hu+/5sJExsFAlv/
eJgCGwwFCQeGH4AACgkQ7Hu+/5sJExvluggApQcvGqkfyD4Eb115LUmi549IKKWq
8FFf85MWoZJ0BLNpIiWLBZFIs8dC4GJYSc1TaBlqlPtaHVh4kxlMvmAWGvDJ0AiE
GVhwE8B5T7pMkFZBIzKPpOPMxBSIue//K2XzxN0yXz+Rae7wpdQlgbcHByZZPnp3
/9E46AOwf5WDWu9J3081jIspeoAl4XOOncVi4azCNX8iwPcJVERQnInnpqBEV9qf
H7sFPO+a9XpBJWjB8mMJzoA3ICWzb0u5YyUpBU6LmHHCGWY+gBDaNKMbRoRUUYyK
eZOICKSe4NoPeN03QbqyJsSV1vynpafS+G+AFfbCGnj0dy6DvWldiSR6kA==
=OtIW
-----END PGP PUBLIC KEY BLOCK-----
';
        $parsable = $gnupg->isParsableArmoredPublicKey($pkey);
        $this->assertTrue($parsable);
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendReImportRevokedKeyDoesNotChange(OpenPGPBackend $gnupg): void
    {
        $gnupg->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');

        // Encrypt first with non revoked key
        $messageToEncrypt = 'PublicKeyCanEncryptCheckService check';
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        $gnupg->encrypt($messageToEncrypt);

        // Import revoked key
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        try {
            $gnupg->encrypt($messageToEncrypt);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }

        // Import non revoked key
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        try {
            $gnupg->encrypt($messageToEncrypt);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }

        // Delete possible revoked key from keying
        $gnupg->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendReImportExpiredKeyDoesNotChange(OpenPGPBackend $gnupg): void
    {
        // Delete possible revoked key from keying
        $gnupg->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');

        // Encrypt first with non revoked key
        $messageToEncrypt = 'PublicKeyCanEncryptCheckService check';
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        $gnupg->encrypt($messageToEncrypt);

        // Import expired key
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        try {
            $gnupg->encrypt($messageToEncrypt);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }

        // Import non revoked key
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $gnupg->importKeyIntoKeyring($armoredKey);
        $gnupg->setEncryptKeyFromFingerprint('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        try {
            $gnupg->encrypt($messageToEncrypt);
            $this->assertTrue(false);
        } catch (Exception $exception) {
            $this->assertTrue(true);
        }

        // Delete revoked key from keying
        $gnupg->deleteKey('67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
    }

    /**
     * @dataProvider openPGPBackendProvider
     */
    public function testOpenPGPBackendCannotImportFutureKey(OpenPGPBackend $gnupg): void
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'fry_public.key');
        $this->expectException(CakeException::class);
        $gnupg->importKeyIntoKeyring($armoredKey);
    }
}
