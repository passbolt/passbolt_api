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
 * @since         2.7.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Form;

use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\FormatValidationTrait;
use App\Test\Lib\Model\GpgkeysModelTrait;
use Passbolt\WebInstaller\Form\GpgKeyForm;

class GpgKeyFormTest extends AppTestCase
{
    use FormatValidationTrait;
    use GpgkeysModelTrait;

    public function testGpgKeyFormTestFieldPublicKeyArmored()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'isPublicKey' => $this->getServerKeyPublicArmoredTestCases(),
            'hasNoExpiry' => $this->getServerKeyPublicArmoredHasNoExpiryTestCases(),
            'canEncrypt' => $this->getServerKeyPublicArmoredCanEncryptTestCases(),
        ];
        $this->assertFormFieldFormatValidation(GpgKeyForm::class, 'public_key_armored', $this->getDummyGpgkey(), $testCases);
    }

    public function testGpgKeyFormTestFieldPrivateKeyArmored()
    {
        $this->markTestSkipped('Test produce test output in CLI on Travis');
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'isPrivateKey' => $this->getServerKeyPrivateArmoredTestCases(),
            'hasNoExpiry' => $this->getServerKeyPrivateArmoredHasNoExpiryTestCases(),
            'canDecrypt' => $this->getServerKeyPrivateArmoredCanDecryptTestCases(),
        ];
        $this->assertFormFieldFormatValidation(GpgKeyForm::class, 'private_key_armored', $this->getDummyGpgkey(), $testCases);
    }

    public function testGpgKeyFormTestFieldFingerprint()
    {
        $testCases = [
            'requirePresence' => self::getRequirePresenceTestCases(),
            'notEmpty' => self::getNotEmptyTestCases(),
            'match_public_private_fingerprint' => [
                'rule_name' => 'match_public_private_fingerprints',
                'test_cases' => [
                    '2FC8945833C51946E937F9FED47B0811573EE67E' => false,
                    $this->getDummyGpgkey()['fingerprint'] => true,
                ]
            ]
        ];
        $this->assertFormFieldFormatValidation(GpgKeyForm::class, 'fingerprint', $this->getDummyGpgkey(), $testCases);
    }

    /**
     * Test cases for server private ket armored validation rule.
     *
     * @return array
     */
    protected function getServerKeyPublicArmoredTestCases()
    {
        return [
            'rule_name' => 'is_public_key',
            'test_cases' => [
                'NOT VALID PUBLIC KEY' => false,
                // KEY without gpg markers shouldn't be valid
                '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
WXi2h/I=
=L/Vu' => false,
                $this->getDummyGpgkey()['private_key_armored'] => false,
                $this->getDummyGpgkey()['public_key_armored'] => true,
            ],
        ];
    }

    /**
     * Test cases for server private ket armored validation rule.
     *
     * @return array
     */
    protected function getServerKeyPrivateArmoredTestCases()
    {
        return [
            'rule_name' => 'is_private_key',
            'test_cases' => [
                'NOT VALID PRIVATE KEY' => false,
                // KEY without gpg markers shouldn't be valid
                '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
ybBiqwayCLB4UXGcUFHffj0mdjk8s/SvQyhZhA==
=D76x' => false,
                $this->getDummyGpgkey()['public_key_armored'] => false,
                $this->getDummyGpgkey()['private_key_armored'] => true,
            ],
        ];
    }

    /**
     * Test cases for server private ket armored validation rule.
     *
     * @return array
     */
    protected function getServerKeyPublicArmoredHasNoExpiryTestCases()
    {
        return [
            'rule_name' => 'has_no_expiry',
            'test_cases' => [
                // Key with expiry date are not accepted
                '-----BEGIN PGP PUBLIC KEY BLOCK-----
Version: GnuPG/MacGPG2 v2.0.22 (Darwin)
Comment: GPGTools - https://gpgtools.org

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
GkkO+pcgU1wQ
=Zopv
-----END PGP PUBLIC KEY BLOCK-----' => false,
                $this->getDummyGpgkey()['public_key_armored'] => true,
            ],
        ];
    }

    /**
     * Test cases for server private ket armored validation rule.
     *
     * @return array
     */
    protected function getServerKeyPrivateArmoredHasNoExpiryTestCases()
    {
        return [
            'rule_name' => 'has_no_expiry',
            'test_cases' => [
                // Key with expiry date are not accepted
                '-----BEGIN PGP PRIVATE KEY BLOCK-----
Comment: GPGTools - https://gpgtools.org

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
LWN2IVKPj2mia4lQHLub9OTlGkkO+pcgU1wQ
=hgWr
-----END PGP PRIVATE KEY BLOCK-----' => false,
                $this->getDummyGpgkey()['private_key_armored'] => true,
            ],
        ];
    }

    /**
     * Test cases for server public key armored can encrypt validation rule.
     *
     * @return array
     */
    protected function getServerKeyPublicArmoredCanEncryptTestCases()
    {
        return [
            'rule_name' => 'can_encrypt',
            'test_cases' => [
                $this->getDummyGpgkey()['public_key_armored'] => true,
            ],
        ];
    }

    /**
     * Test cases for server private ket armored validation rule.
     *
     * @return array
     */
    protected function getServerKeyPrivateArmoredCanDecryptTestCases()
    {
        return [
            'rule_name' => 'can_decrypt',
            'test_cases' => [
                // Private key protected with a passphrase
                '-----BEGIN PGP PRIVATE KEY BLOCK-----

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
Wg==
=Z91U
-----END PGP PRIVATE KEY BLOCK-----' => false,
                $this->getDummyGpgkey()['private_key_armored'] => true,
            ],
        ];
    }
}
