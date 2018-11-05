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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Utility\UserAccessControl;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Cake\Core\Configure;
use Cake\Network\Exception\InternalErrorException;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

class MfaOtpFactory
{
    /**
     * Generate a random TOTP
     *
     * @param UserAccessControl $uac user access control
     * @return string provisioning uri
     */
    public static function generateTOTP(UserAccessControl $uac)
    {
        try {
            $secret = trim(Base32::encode(random_bytes(256)), '='); // 256 random bytes Base32 without padding
        } catch (\TypeError $exception) {
            throw new InternalErrorException(__('Could not generate TOTP secret, please try again later.'));
        } catch (\Exception $exception) {
            throw new InternalErrorException(__('Could not generate enough random bytes, please try again later.'));
        }
        $totp = new TOTP(
            $uac->getUsername(), //label: string shown bellow the code digits
            $secret
        );
        $totp->setIssuer(Configure::read('passbolt.meta.title')); //issuer: string shown above the code digits

        return $totp->getProvisioningUri();
    }

    /**
     * Build QR code inline image
     *
     * @param string $provisioningUri provisioning uri
     * @param int $width width
     * @param int $height height
     * @param string $encoding enconding
     * @return string
     */
    public static function getQrCodeInline(string $provisioningUri, $width = 256, $height = 256, $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING)
    {
        $renderer = new Png();
        $renderer->setHeight($width);
        $renderer->setWidth($height);
        $writer = new Writer($renderer);
        $ecLevel = ErrorCorrectionLevel::L;
        $inline = base64_encode($writer->writeString($provisioningUri, $encoding, $ecLevel));

        return "data:image/png;base64,{$inline}";
    }
}
