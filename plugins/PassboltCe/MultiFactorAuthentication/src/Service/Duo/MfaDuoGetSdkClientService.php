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
use Cake\Http\Exception\InternalErrorException;
use Cake\Routing\Router;
use Cake\Validation\Validation;
use Duo\DuoUniversal\Client;
use Duo\DuoUniversal\DuoException;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService;

/**
 * Class MfaDuoGetSdkClientService
 */
class MfaDuoGetSdkClientService
{
    /**
     * Get the Duo Sdk Client object or fail.
     *
     * @param \Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsDuoService $settings Duo org settings
     * @param string $tokenType Authentication token type -- used to know whether the callback is for the setup or verify flow
     * @return \Duo\DuoUniversal\Client
     * @throws \Cake\Http\Exception\InternalErrorException If it cannot instantiate the Duo Sdk client.
     */
    public function getOrFail(MfaOrgSettingsDuoService $settings, string $tokenType): Client
    {
        try {
            return new Client(
                $settings->getDuoClientId(),
                $settings->getDuoClientSecret(),
                $settings->getDuoApiHostname(),
                $this->getCallbackRedirectUrl($tokenType),
                true,
            );
        } catch (DuoException $e) {
            throw new InternalErrorException(__('Could not validate the Duo settings.'), null, $e);
        }
    }

    /**
     * Get the callback redirect URL to redirect the user from Duo back to Passbolt
     *
     * @param string $tokenType Authentication token type, which determines which endpoint to redirect users to
     * @return string
     */
    public function getCallbackRedirectUrl(string $tokenType): string
    {
        if (!Validation::inList($tokenType, MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES)) {
            $readableAllowedTokenTypes = implode(', ', MfaDuoCallbackAuthenticationTokenService::$ALLOWED_TOKEN_TYPES);
            $msg = 'The authentication token type should be one of the following: ' . $readableAllowedTokenTypes . '.';
            throw new \InvalidArgumentException($msg);
        }
        $path = $tokenType === AuthenticationToken::TYPE_MFA_SETUP ? 'setup' : 'verify';
        $url = '/mfa/' . $path . '/duo/callback';

        return Router::url($url, true);
    }
}
