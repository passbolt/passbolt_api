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
 * @since         4.1.0
 */

namespace Passbolt\Sso\Service\Sso\OAuth2;

use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use League\OAuth2\Client\Provider\AbstractProvider;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Dto\SsoSettingsOAuth2DataDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Utility\OAuth2\Provider\OAuth2Provider;

class SsoOAuth2Service extends AbstractSsoService
{
    /**
     * Get authorization URL from the provider; this returns the
     * urlAuthorize option and generates and applies any necessary parameters
     *
     * @param \App\Utility\ExtendedUserAccessControl $uac user access control
     * @return string
     * @throws \Exception Unable to generate nonce.
     */
    public function getAuthorizationUrl(ExtendedUserAccessControl $uac): string
    {
        $options = [
            'response_type' => 'code',
            'nonce' => $this->generateNonce(),
        ];

        if ($uac->getUsername() !== null) { // For some types(i.e. sso_recover) we don't have user details
            $options['login_hint'] = $uac->getUsername();
        }

        return $this->provider->getAuthorizationUrl($options);
    }

    // ABSTRACT CLASS PROTECTED FUNCTIONS DEFINITION

    /**
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto $settings setting
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    protected function getOAuthProvider(SsoSettingsDto $settings): AbstractProvider
    {
        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsOAuth2DataDto $data */
        $data = $settings->data;

        return new OAuth2Provider([
            'clientId' => $data->client_id,
            'clientSecret' => $data->client_secret,
            'redirectUri' => Router::url('/sso/oauth2/redirect', true),
            'openIdBaseUri' => $data->url,
            'openIdConfigurationPath' => $data->openid_configuration_path,
        ]);
    }

    /**
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    protected function assertAndGetSsoSettings(): SsoSettingsDto
    {
        try {
            $ssoSettings = (new SsoSettingsGetService())->getActiveOrFail(true);
            if ($ssoSettings->provider !== SsoSetting::PROVIDER_OAUTH2) {
                throw new BadRequestException('Invalid provider. Expected OAuth2.');
            }
            if (!($ssoSettings->data instanceof SsoSettingsOAuth2DataDto)) {
                throw new BadRequestException('Invalid provider data. Expected OAuth2 settings.');
            }
        } catch (\Exception $exception) {
            throw new BadRequestException(__('No valid SSO settings found.'), 400, $exception);
        }

        return $ssoSettings;
    }
}
