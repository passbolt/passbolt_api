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
 * @since         3.11.0
 */

namespace Passbolt\MultiFactorAuthentication\Service\Duo;

use App\Utility\UserAccessControl;
use Cake\Core\Configure;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\UnauthorizedException;
use Cake\Utility\Hash;
use Duo\DuoUniversal\Client;
use Duo\DuoUniversal\DuoException;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;

/**
 * Class MfaDuoVerifyService
 */
class MfaDuoVerifyDuoCodeService
{
    public const PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER = 'passbolt.security.mfa.duoVerifySubscriber';

    /**
     * @var \Duo\DuoUniversal\Client
     */
    protected $duoClient;

    /**
     * MfaDuoVerifyService constructor.
     *
     * @param string $authTokenType The authentication token type, which determines which flow this is for
     * @param \Duo\DuoUniversal\Client|null $client Duo SDK Client
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo Sdk Client
     */
    public function __construct(string $authTokenType, ?Client $client = null)
    {
        try {
            $this->duoClient = $client ?? (new MfaDuoGetSdkClientService())->getOrFail(
                new MfaOrgSettingsDuoService(MfaOrgSettings::get()->getSettings()),
                $authTokenType
            );
        } catch (\Throwable $th) {
            throw new InternalErrorException(__('Could not enable Duo MFA provider.'), null, $th);
        }
    }

    /**
     * Verify the duo code and retrieve the associated authorization details from Duo.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @param string $duoCode The duo code
     * @return bool
     * @throws \Cake\Http\Exception\UnauthorizedException If an error occurred while retrieving the Duo authentication details
     * @throws \Cake\Http\Exception\UnauthorizedException If the duo authentication origin endpoint (iss) does not match the duo hostname
     * @throws \Cake\Http\Exception\UnauthorizedException if the duo authentication subscriber does not match the operator username
     * @throws \Cake\Http\Exception\InternalErrorException If Duo doesn't return the authentication details as an array.
     */
    public function verify(UserAccessControl $uac, string $duoCode): bool
    {
        $operatorUsername = $uac->getUsername();
        $duoAuthenticationData = $this->requestDuoAuthenticationDetails($duoCode, $operatorUsername);
        $this->assertDuoAuthenticationEndpoint(Hash::get($duoAuthenticationData, 'iss'));
        $this->assertDuoAuthenticationSubscriber(Hash::get($duoAuthenticationData, 'sub'), $operatorUsername);

        return true;
    }

    /**
     * Request Duo authentication details against Duo services.
     *
     * @param string $duoCode The duo code
     * @param string $operatorUsername The operator username
     * @return array
     * @throws \Cake\Http\Exception\UnauthorizedException If an error occurred while retrieving the Duo authentication details
     * @throws \Cake\Http\Exception\InternalErrorException If Duo doesn't return the authentication details as an array.
     */
    private function requestDuoAuthenticationDetails(string $duoCode, string $operatorUsername): array
    {
        try {
            /**
             * @var array $duoAuthenticationData
             * @psalm-suppress UndefinedDocblockClass
             */
            $duoAuthenticationData = $this->duoClient->exchangeAuthorizationCodeFor2FAResult(
                $duoCode,
                $operatorUsername
            );
        } catch (DuoException $e) {
            throw new UnauthorizedException(__('Unable to verify Duo code against Duo service.'), null, $e);
        }

        return $duoAuthenticationData;
    }

    /**
     * Assert that the origin response endpoint is a known Duo response endpoint.
     *
     * @see https://duo.com/docs/oauthapi
     * @param string $duoAuthenticationDetailIss Duo endpoint from callback
     * @return void
     * @throws \Cake\Http\Exception\UnauthorizedException If the duo authentication origin endpoint (iss) does not match the duo hostname
     * defined in the organization settings.
     */
    private function assertDuoAuthenticationEndpoint(string $duoAuthenticationDetailIss): void
    {
        $duoApiHostname = MfaOrgSettings::get()->getDuoOrgSettings()->getDuoApiHostname();
        $expectedIss = "https://$duoApiHostname/oauth/v1/token";
        if ($duoAuthenticationDetailIss !== $expectedIss) {
            $msg = __('The duo authentication origin endpoint does not match the organization setting duo hostname.');
            throw new UnauthorizedException($msg);
        }
    }

    /**
     * Assert that the Duo subscriber who authenticated matches the user's username.
     *
     * @see https://duo.com/docs/oauthapi
     * @param string $duoSubscriber Duo subscriber from callback
     * @param string $operatorUsername Operator username
     * @return void
     * @throws \Cake\Http\Exception\UnauthorizedException if the duo authentication subscriber does not match the operator username
     */
    private function assertDuoAuthenticationSubscriber(string $duoSubscriber, string $operatorUsername): void
    {
        $verifySubscriber = Configure::read(self::PASSBOLT_SECURITY_MFA_DUO_VERIFY_SUBSCRIBER);
        if ($verifySubscriber === true && mb_strtolower($duoSubscriber) !== mb_strtolower($operatorUsername)) {
            $msg = __('The duo authentication subscriber does not match the operator username.');
            throw new UnauthorizedException($msg);
        }
    }
}
