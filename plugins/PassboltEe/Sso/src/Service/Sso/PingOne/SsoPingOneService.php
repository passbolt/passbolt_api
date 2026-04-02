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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Service\Sso\PingOne;

use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Exception;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\AbstractProvider;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Dto\SsoSettingsPingOneDataDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Utility\PingOne\Provider\PingOneProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;

class SsoPingOneService extends SsoOAuth2Service
{
    /**
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto $settings setting
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    protected function getOAuthProvider(SsoSettingsDto $settings): AbstractProvider
    {
        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsPingOneDataDto $data */
        $data = $settings->data;

        $collaborators = [];
        $httpClient = $this->getCustomHttpClient();
        // Set custom HTTP client when using self-signed SSL certificate
        if ($httpClient instanceof Client) {
            $collaborators['httpClient'] = $httpClient;
        }

        return SsoProviderFactory::create(
            PingOneProvider::class,
            [
                'clientId' => $data->client_id,
                'clientSecret' => $data->client_secret,
                'redirectUri' => Router::url('/sso/pingone/redirect', true),
                'openIdBaseUri' => $data->url,
                'openIdConfigurationPath' => $data->openid_configuration_path,
                'environmentId' => $data->environment_id,
                'emailClaim' => $data->email_claim,
            ],
            $collaborators
        );
    }

    /**
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    protected function assertAndGetSsoSettings(): SsoSettingsDto
    {
        try {
            $ssoSettings = (new SsoSettingsGetService())->getActiveOrFail(true);
            if ($ssoSettings->provider !== SsoSetting::PROVIDER_PINGONE) {
                throw new BadRequestException(__('Invalid provider. Expected PingOne.'));
            }
            if (!($ssoSettings->data instanceof SsoSettingsPingOneDataDto)) {
                throw new BadRequestException(__('Invalid provider data. Expected PingOne settings.'));
            }
        } catch (Exception $exception) {
            throw new BadRequestException(__('No valid SSO settings found.'), 400, $exception);
        }

        return $ssoSettings;
    }
}
