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

use App\Model\Entity\AuthenticationToken;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\ServiceUnavailableException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoAuthenticationRequestDto;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * Class MfaDuoStartDuoAuthenticationService
 */
class MfaDuoStartDuoAuthenticationService
{
    use LocatorAwareTrait;

    /**
     * @var \Duo\DuoUniversal\Client
     */
    protected $duoClient;

    /**
     * @var string
     */
    protected $authTokenType;

    /**
     * Constructor.
     *
     * @param string $authenticationTokenType Authentication token type
     * @param \Duo\DuoUniversal\Client $client Duo SDK Client
     * @return void
     * @throws \Cake\Http\Exception\ServiceUnavailableException If it cannot create the Duo Sdk Client
     */
    public function __construct(string $authenticationTokenType, ?Client $client = null)
    {
        $this->authTokenType = $authenticationTokenType;
        try {
            $this->duoClient = $client ?? (new MfaDuoGetSdkClientService())->getOrFail(
                new MfaOrgSettingsDuoService(MfaOrgSettings::get()->getSettings()),
                $authenticationTokenType
            );
        } catch (\Throwable $th) {
            throw new ServiceUnavailableException(__('Could not enable Duo MFA provider.'), null, $th);
        }
    }

    /**
     * Start a Duo authentication process:
     * - Generate the authentication url to redirect the user to authenticate against Duo.
     * - Create a callback authentication token that will be used to authenticate the user when Duo will respond with a callback.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control
     * @param string|null $redirect The redirect path to store along the token. The user will be redirected to this
     * path after authenticating to Duo with success.
     * @return \Passbolt\MultiFactorAuthentication\Model\Dto\MfaDuoAuthenticationRequestDto
     * @throws \Cake\Http\Exception\InternalErrorException If something went wrong while connecting to Duo.
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo callback token
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo authentication url
     */
    public function start(
        UserAccessControl $uac,
        ?string $redirect = '/'
    ): MfaDuoAuthenticationRequestDto {
        $this->assertDuoHealthcheck();
        $this->assertDuoCallbackAuthenticationTokenType();

        $duoCallbackAuthenticationToken = $this->createDuoCallbackAuthenticationToken(
            $uac,
            $redirect
        );
        $duoAuthenticationUrl = $this->createDuoAuthenticationUrl($uac, $duoCallbackAuthenticationToken);

        return new MfaDuoAuthenticationRequestDto($duoCallbackAuthenticationToken, $duoAuthenticationUrl);
    }

    /**
     * Assert that Duo services are reachable.
     *
     * @return void
     * @throws \Cake\Http\Exception\ServiceUnavailableException If it cannot connect to Duo services
     */
    private function assertDuoHealthcheck(): void
    {
        try {
            $this->duoClient->healthCheck();
        } catch (\Throwable $th) {
            throw new ServiceUnavailableException(__('Unable to connect to Duo services.'), null, $th);
        }
    }

    /**
     * Assert that the authentication token type is supported.
     *
     * @return void
     * @throws \InvalidArgumentException If the authentication token type is not supported
     */
    private function assertDuoCallbackAuthenticationTokenType(): void
    {
        $isValid = Validation::inList(
            $this->authTokenType,
            MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES
        );
        if (!$isValid) {
            $readableAllowedTokenTypes = implode(', ', MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES);
            $msg = 'The authentication token type should be one of the following: ' . $readableAllowedTokenTypes . '.';
            throw new \InvalidArgumentException($msg);
        }
    }

    /**
     * Create the authentication token to use when Duo will respond with a callback to passbolt. It will allow to authenticate the
     * user to passbolt.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control.
     * @param string|null $redirect The redirect path to store along the token. The user will be redirected to this
     * path after authenticating to Duo with success.
     * @return \App\Model\Entity\AuthenticationToken
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo callback token
     */
    private function createDuoCallbackAuthenticationToken(
        UserAccessControl $uac,
        ?string $redirect
    ): AuthenticationToken {
        /** @var \App\Model\Table\AuthenticationTokensTable $authenticationTable */
        $authenticationTable = $this->fetchTable('AuthenticationTokens');
        $data = [
            'provider' => MfaSettings::PROVIDER_DUO,
            'state' => $this->duoClient->generateState(),
            'redirect' => $redirect,
            'user_agent' => env('HTTP_USER_AGENT'),
        ];

        try {
            return $authenticationTable->generate($uac->getId(), $this->authTokenType, null, $data);
        } catch (\Throwable $th) {
            $msg = 'Unable to create the Duo callback authentication token.';
            throw new InternalErrorException($msg, null, $th);
        }
    }

    /**
     * Create the authentication token that will be used when the Duo will respond with a callback to passbolt to authenticate the user
     * on the callback controller.
     *
     * @param \App\Utility\UserAccessControl $uac The user access control.
     * @param \App\Model\Entity\AuthenticationToken $authenticationToken The callback authentication token.
     * path after authenticating to Duo with success.
     * @return string
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot create the Duo authentication url
     */
    private function createDuoAuthenticationUrl(
        UserAccessControl $uac,
        AuthenticationToken $authenticationToken
    ): string {
        try {
            return $this->duoClient->createAuthUrl(
                $uac->getUsername(),
                $authenticationToken->getDataValue('state')
            );
        } catch (\Throwable $th) {
            throw new InternalErrorException('Unable to create the Duo authentication URL.', null, $th);
        }
    }
}
