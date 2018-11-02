<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Utility;

use Cake\Core\Configure;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Utility\MfaOtpFactory;

class MfaOtpFactoryTest extends MfaIntegrationTestCase
{
    /**
     * @group mfa
     * @group mfaOtpFactory
     */
    public function testMfaOtpFactoryGenerateTOTP()
    {
        $otp = MfaOtpFactory::generateTOTP($this->mockUserAccessControl('ada'));
        $this->assertTrue(true);
        $this->assertContains('otpauth://totp/', $otp);
        $this->assertContains('issuer=' . Configure::read('passbolt.meta.title'), $otp);
        $this->assertContains('secret=', $otp);
        $this->assertContains('ada%40passbolt.com', $otp);
    }

    /**
     * @group mfa
     * @group mfaOtpFactory
     */
    public function testMfaOtpQrCodeInline()
    {
        $otp = MfaOtpFactory::generateTOTP($this->mockUserAccessControl('ada'));
        $qrcode = MfaOtpFactory::getQrCodeInline($otp);
        $this->assertContains('data:image/png;base64,', $qrcode);
    }
}
