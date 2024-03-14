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
 * @since         4.6.0
 */

namespace Passbolt\Sso\Service\Sso\Adfs;

use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use GuzzleHttp\Client;
use League\OAuth2\Client\Provider\AbstractProvider;
use Passbolt\Sso\Model\Dto\SsoSettingsAdfsDataDto;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Sso\OAuth2\SsoOAuth2Service;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Utility\Adfs\Provider\AdfsProvider;

class SsoAdfsService extends SsoOAuth2Service
{
    /**
     * @param \Passbolt\Sso\Model\Dto\SsoSettingsDto $settings setting
     * @return \League\OAuth2\Client\Provider\AbstractProvider
     */
    protected function getOAuthProvider(SsoSettingsDto $settings): AbstractProvider
    {
        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsAdfsDataDto $data */
        $data = $settings->data;

        $collaborators = [];
        $httpClient = $this->getCustomHttpClient();
        // Set custom HTTP client when using self-signed SSL certificate
        if ($httpClient instanceof Client) {
            $collaborators['httpClient'] = $httpClient;
        }

        return new AdfsProvider(
            [
                'clientId' => $data->client_id,
                'clientSecret' => $data->client_secret,
                'redirectUri' => Router::url('/sso/adfs/redirect', true),
                'openIdBaseUri' => $data->url,
                'openIdConfigurationPath' => $data->openid_configuration_path,
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
            if ($ssoSettings->provider !== SsoSetting::PROVIDER_ADFS) {
                throw new BadRequestException(__('Invalid provider. Expected AD FS.'));
            }
            if (!($ssoSettings->data instanceof SsoSettingsAdfsDataDto)) {
                throw new BadRequestException(__('Invalid provider data. Expected AD FS settings.'));
            }
        } catch (\Exception $exception) {
            throw new BadRequestException(__('No valid SSO settings found.'), 400, $exception);
        }

        return $ssoSettings;
    }
}
