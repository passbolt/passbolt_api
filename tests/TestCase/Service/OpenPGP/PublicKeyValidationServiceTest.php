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
 * @since         3.6.0
 */
namespace App\Test\TestCase\Service\OpenPGP;

use App\Error\Exception\CustomValidationException;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Test\Lib\AppTestCase;

class PublicKeyValidationServiceTest extends AppTestCase
{
    public function testPublicKeyValidationService_GetDefaultRules_Success()
    {
        $this->assertNotEmpty(PublicKeyValidationService::getDefaultRules());
    }

    public function testPublicKeyValidationService_IsDateInFuture()
    {
        $this->assertFalse(PublicKeyValidationService::isDateInFuture(time() - 10));
        $this->assertFalse(PublicKeyValidationService::isDateInFuture('1599026400'));
        $this->assertTrue(PublicKeyValidationService::isDateInFuture(time() + 10000));
    }

    public function testPublicKeyValidationService_IsRecommendedSize()
    {
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 2048));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 2048, false));
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 2048, true));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 3072));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 3072, true));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 3072, false));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 4096, true));
        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('RSA', 4096, false));
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 30, false));
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 10000, false));
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 30, true));
        $this->assertFalse(PublicKeyValidationService::isRecommendedSize('RSA', 10000, true));

        $this->assertTrue(PublicKeyValidationService::isRecommendedSize('ECC', 256));
    }

    public function testPublicKeyValidationService_IsValidShortKeyId()
    {
        $this->assertTrue(PublicKeyValidationService::isValidShortKeyId('01234567'));
        $this->assertTrue(PublicKeyValidationService::isValidShortKeyId('ABCDEF01'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('ABCDEF0'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('ABCDEF000'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('!1234567'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('N1234567'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('🔥'));
    }

    public function testPublicKeyValidationService_IsValidLongKeyId()
    {
        $this->assertTrue(PublicKeyValidationService::isValidLongKeyId('0123456701234567'));
        $this->assertTrue(PublicKeyValidationService::isValidLongKeyId('ABCDEF0101234567'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId('ABCDEF001234567'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId('ABCDEF00001234567'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId('!123456701234567'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId('N123456701234567'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId('🔥'));
        $this->assertFalse(PublicKeyValidationService::isValidLongKeyId(''));
    }

    public function testPublicKeyValidationService_IsValidKeyId()
    {
        $this->assertTrue(PublicKeyValidationService::isValidKeyId('01234567'));
        $this->assertTrue(PublicKeyValidationService::isValidKeyId('0123456701234567'));
        $this->assertTrue(PublicKeyValidationService::isValidKeyId('01234567', false));
        $this->assertFalse(PublicKeyValidationService::isValidKeyId('01234567', true));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId('🔥'));
        $this->assertFalse(PublicKeyValidationService::isValidShortKeyId(''));
    }

    public function testPublicKeyValidationService_IsValidFingerprint()
    {
        $this->assertTrue(PublicKeyValidationService::isValidFingerprint('03F60E958F4CB29723ACDF761353B5B15D9B054A'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint('03F60E958F4CB29723'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint('03F60E958F4CB29723ACDF761353B5B15D9B054AA'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint('03F60E958F4CB29723ACDF761353B5B15D9B054Z'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint('03F60E958F4CB29723ACDF761353B5B15D9B054a'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint('🔥'));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint(''));
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint());
        $this->assertFalse(PublicKeyValidationService::isValidFingerprint(null));
    }

    public function testPublicKeyValidationService_uidContainValidEmail()
    {
        $this->assertTrue(PublicKeyValidationService::uidContainValidEmail('Ada Lovelace <ada@passbolt.dev>'));
        $this->assertFalse(PublicKeyValidationService::uidContainValidEmail('Ada Lovelace <🔥>'));
        $this->assertFalse(PublicKeyValidationService::uidContainValidEmail('Ada'));
        $this->assertFalse(PublicKeyValidationService::uidContainValidEmail(''));
        $this->assertFalse(PublicKeyValidationService::uidContainValidEmail());
    }

    public function testPublicKeyValidationService_isValidAlgorithm()
    {
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('RSA'));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECC'));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECDSA'));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ELGAMAL'));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('DH'));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('DSA'));

        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('RSA', true));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECC', true));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECDSA', true));
        $this->assertFalse(PublicKeyValidationService::isValidAlgorithm('ELGAMAL', true));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('DH', true));
        $this->assertFalse(PublicKeyValidationService::isValidAlgorithm('DSA', true));

        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('RSA', false));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECC', false));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ECDSA', false));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('ELGAMAL', false));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('DH', false));
        $this->assertTrue(PublicKeyValidationService::isValidAlgorithm('DSA', false));
    }

    public function testPublicKeyValidationService_hasExtraBreakLine()
    {
        $valid = "-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFYuIFQBEACpYmcjzX1XC0LPJCMOY/LwxIB3lGfL5+X5kJSfLpWDYKa0XFXv
6aOrtGWjTQi3BheJL+aiANxh/r8WIA4P0A5Zm9EH4vhXCaNxf73K7WjhFuwKIY+h
5cjCaMlTVRiSzGK0
=uEYs
-----END PGP PUBLIC KEY BLOCK-----";
        $this->assertFalse(PublicKeyValidationService::hasExtraBreakline($valid));

        $invalid = "-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFYuIFQBEACpYmcjzX1XC0LPJCMOY/LwxIB3lGfL5+X5kJSfLpWDYKa0XFXv
6aOrtGWjTQi3BheJL+aiANxh/r8WIA4P0A5Zm9EH4vhXCaNxf73K7WjhFuwKIY+h
5cjCaMlTVRiSzGK0
=uEYs

-----END PGP PUBLIC KEY BLOCK-----";
        $this->assertTrue(PublicKeyValidationService::hasExtraBreakline($invalid));
    }

    public function testPublicKeyValidationService_Success()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertTrue(count($keyInfo) > 0);
    }

    public function testPublicKeyValidationService_Community4793_NoError_Historical()
    {
        // Ref. https://community.passbolt.com/t/ios-mobile-app-sign-in-fails/4793/7
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'community4793_public.key');
        try {
            $rules = PublicKeyValidationService::getHistoricalRules();
            PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, $rules);
            $this->assertTrue(true);
        } catch (CustomValidationException $e) {
            $this->fail();
        }
    }

    public function testPublicKeyValidationService_Community4793_Error_Strict()
    {
        // Ref. https://community.passbolt.com/t/ios-mobile-app-sign-in-fails/4793/7
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'community4793_public.key');
        try {
            PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
            $this->fail();
        } catch (CustomValidationException $e) {
            $errors = $e->getErrors();
            $this->assertNotEmpty($errors['armored_key']['hasMultipleMainPacketsRule']);
        }
    }

    public function testPublicKeyValidationService_parse()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'revoked_sig_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertTextEquals('170B1EF744092F6EBB0CDD517D1699F049F3E21B', $keyInfo['fingerprint']);
        $this->assertEquals(1644501506, $keyInfo['key_created']);
        $this->assertEmpty($keyInfo['expires']);
        $this->assertEquals(4096, $keyInfo['bits']);
        $this->assertEquals('RSA', $keyInfo['type']);
        $this->assertEquals('revoked_sig <revoked_sig@passbolt.com>', $keyInfo['uid']);
        $this->assertEquals(false, $keyInfo['revoked']);
    }

    public function testPublicKeyValidationService_parseRevoked_Historical()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(true, $keyInfo['revoked']);
    }

    public function testPublicKeyValidationService_parseRevoked_Default()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        try {
            PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
            $this->assertTrue(false);
        } catch (CustomValidationException $e) {
            $this->assertTrue(true);
        }
    }

    public function testPublicKeyValidationService_parseRsa2048_Historical()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(2048, $keyInfo['bits']);
        $this->assertEquals('RSA', $keyInfo['type']);
    }

    public function testPublicKeyValidationService_parseRsa2048_Default()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key');
        PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertTrue(true);
    }

    public function testPublicKeyValidationService_parseRsa2048_Strict()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key');
        try {
            PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getStrictRules());
            $this->fail();
        } catch (CustomValidationException $e) {
            $this->assertTrue(true);
        }
    }

    public function testPublicKeyValidationService_parseElgamal_Historical()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'elgamal_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(3072, $keyInfo['bits']);
        $this->assertEquals('DSA', $keyInfo['type']);

        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'elgamal_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey);
        $this->assertEquals(3072, $keyInfo['bits']);
        $this->assertEquals('DSA', $keyInfo['type']);
    }

    public function testPublicKeyValidationService_parseEccKeyCurve25519()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_curve25519_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(256, $keyInfo['bits']);
        $this->assertEquals('EdDSA', $keyInfo['type']);
        $this->assertEquals('21DB3781A35DFDA802A9B17557800F30009B7B46', $keyInfo['fingerprint']);
    }

    public function testPublicKeyValidationService_parseEccKeyNistP521()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_nistp521_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(521, $keyInfo['bits']);
        $this->assertEquals('ECDSA', $keyInfo['type']);
        $this->assertEquals('AEE8E22ACFBF70527C1BD918F571FEB3B15105EE', $keyInfo['fingerprint']);
    }

    public function testPublicKeyValidationService_parseEccKeyBrainpool()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'ecc_brainpoolp384_public.key');
        $keyInfo = PublicKeyValidationService::parseAndValidatePublicKey($armoredKey, PublicKeyValidationService::getHistoricalRules());
        $this->assertEquals(384, $keyInfo['bits']);
        $this->assertEquals('ECDSA', $keyInfo['type']);
    }

    public function testPublicKeyValidationService_unamorFails()
    {
        $armoredKey = file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public_broken_base64.key');
        $this->assertFalse(PublicKeyValidationService::isParsableArmoredPublicKey($armoredKey));
    }
}
