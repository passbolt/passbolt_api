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
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Base32;

class MfaOtpFactory
{
    public const PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH = 'passbolt.plugins.multiFactorAuthentication.totp.secretLength'; //phpcs:ignore
    public const PASSBOLT_PLUGINS_MFA_TOTP_DEFAULT_SECRET_LENGTH = 32;
    public const PASSBOLT_PLUGINS_MFA_TOTP_MINIMUM_SECRET_LENGTH = 16;

    /**
     * Return Issuer
     * The domain name without http(s):// and trailing /
     *
     * @param string|null $url Router::url will be used if empty
     * @return string
     */
    public static function getIssuer(?string $url = null): string
    {
        if ($url === null) {
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
        $secretLength = self::getAndSanitizeSecretLengthFromConfig();
        try {
            $secret = trim(Base32::encode(random_bytes($secretLength)), '='); // some random bytes Base32 without padding
        } catch (\TypeError $exception) {
            throw new InternalErrorException(
                'Could not generate TOTP secret, please try again later.',
                500,
                $exception
            );
        } catch (\Exception $exception) {
            throw new InternalErrorException(
                'Could not generate enough random bytes, please try again later.',
                500,
                $exception
            );
        }

        $totp = TOTP::create($secret);
        $totp->setLabel($uac->getUsername()); // label: string shown below the code digits
        $totp->setIssuer(self::getIssuer()); // issuer: string shown above the code digits

        return $totp->getProvisioningUri();
    }

    /**
     * @return int
     */
    public static function getAndSanitizeSecretLengthFromConfig(): int
    {
        $default = self::PASSBOLT_PLUGINS_MFA_TOTP_DEFAULT_SECRET_LENGTH;
        $secretLength = Configure::read(self::PASSBOLT_PLUGINS_MFA_TOTP_SECRET_LENGTH, $default);
        if (is_object($secretLength) || is_array($secretLength)) {
            return $default;
        }
        $min = self::PASSBOLT_PLUGINS_MFA_TOTP_MINIMUM_SECRET_LENGTH;
        // catches wrong types like booleans, float, etc...
        $secretLength = intval($secretLength);
        if ($secretLength === 0) {
            return $default;
        }
        if ($secretLength < $min) {
            return $min;
        }

        return $secretLength;
    }

    /**
     * Build QR code inline image
     *
     * @param string $provisioningUri provisioning uri
     * @param int|null $width width default 256
     * @param string|null $encoding encoding default ISO-8859-1
     * @return string
     */
    public static function getQrCodeInlineSvg(
        string $provisioningUri,
        ?int $width = 256,
        ?string $encoding = Encoder::DEFAULT_BYTE_MODE_ECODING
    ): string {
        $renderer = new ImageRenderer(
            new RendererStyle($width),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $inlineSvg = $writer->writeString($provisioningUri, $encoding);

        return str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $inlineSvg);
    }
}
