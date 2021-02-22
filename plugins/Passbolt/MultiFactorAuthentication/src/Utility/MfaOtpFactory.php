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
 * @since         2.4.0
 */
namespace Passbolt\MultiFactorAuthentication\Utility;

use App\Utility\UserAccessControl;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

class MfaOtpFactory
{
    /**
     * Return Issuer
     * The domain name without http(s):// and trailing /
     *
     * @param string|null $url Router::url will be used if empty
     * @return string
     */
    public static function getIssuer(?string $url = null): string
    {
        if (!isset($url) || !is_string($url)) {
            $url = Router::url('/', true);
        }
        $url = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
        $url = str_replace(':', '', $url);
        $url = rtrim($url, '/');

        return $url;
    }

    /**
     * Generate a random TOTP
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return string provisioning uri
     */
    public static function generateTOTP(UserAccessControl $uac): string
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
        $totp->setIssuer(self::getIssuer()); //issuer: string shown above the code digits

        return $totp->getProvisioningUri();
    }

    /**
     * Build QR code inline image
     *
     * @param string $provisioningUri provisioning uri
     * @param int|null $width width default 256
     * @param int|null $height height default 256
     * @param string|null $encoding encoding default ISO-8859-1
     * @return string
     */
    public static function getQrCodeInline(
        string $provisioningUri,
        ?int $width = 256,
        ?int $height = 256,
        ?string $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING
    ): string {
        $renderer = new Png();
        $renderer->setHeight($width);
        $renderer->setWidth($height);
        $writer = new Writer($renderer);
        $ecLevel = ErrorCorrectionLevel::L;
        $inline = base64_encode($writer->writeString($provisioningUri, $encoding, $ecLevel));

        return "data:image/png;base64,{$inline}";
    }
}
