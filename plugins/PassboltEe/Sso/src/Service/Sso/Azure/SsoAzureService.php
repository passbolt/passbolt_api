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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Service\Sso\Azure;

use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use League\OAuth2\Client\Provider\AbstractProvider;
use Passbolt\Sso\Form\SsoSettingsAzureDataForm;
use Passbolt\Sso\Model\Dto\SsoSettingsAzureDataDto;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\SsoSettings\SsoSettingsGetService;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;

class SsoAzureService extends AbstractSsoService
{
    // ABSTRACT CLASS PUBLIC FUNCTIONS DEFINITION

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
        $prompt = $this->getSettings()->getData()->toArray()['prompt'];

        $options = [
            'response_type' => 'code',
            'nonce' => $this->generateNonce(),
        ];

        /**
         * Only set prompt if its "login".
         *
         * Setting prompt to "none" will try a silent sign-in request, but it will throw error if user is not already signed-in.
         * To fix we don't set prompt option if "none" so this will:
         * 1. show login screen to enter credentials if user is not signed-in
         * 2. won't ask for credentials if user already signed-in into Azure AD
         */
        if ($prompt === SsoSettingsAzureDataForm::PROMPT_LOGIN) {
            $options['prompt'] = $prompt;
        }

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
        /** @var \Passbolt\Sso\Model\Dto\SsoSettingsAzureDataDto $data */
        $data = $settings->data;

        return new AzureProvider([
            'clientId' => $data->client_id,
            'clientSecret' => $data->client_secret,
            'redirectUri' => Router::url('/sso/azure/redirect', true),
            'tenant' => $data->tenant_id,
            'urlLogin' => $data->url ?? null,
            'emailClaim' => $data->email_claim ?? null,
        ]);
    }

    /**
     * @return \Passbolt\Sso\Model\Dto\SsoSettingsDto
     */
    protected function assertAndGetSsoSettings(): SsoSettingsDto
    {
        try {
            $ssoSettings = (new SsoSettingsGetService())->getActiveOrFail(true);
            if ($ssoSettings->provider !== SsoSetting::PROVIDER_AZURE) {
                throw new BadRequestException('Invalid provider. Expected Azure as provider.');
            }
            if (!($ssoSettings->data instanceof SsoSettingsAzureDataDto)) {
                throw new BadRequestException('Invalid provider data. Expected Azure settings.');
            }
        } catch (\Exception $exception) {
            throw new BadRequestException(__('No valid SSO settings found.'), 400, $exception);
        }

        return $ssoSettings;
    }

    // OVERRIDDEN METHODS

    /**
     * @inheritDoc
     */
    public function assertResourceOwnerAgainstSsoState(
        SsoResourceOwnerInterface $resourceOwner,
        SsoState $ssoState
    ): void {
        parent::assertResourceOwnerAgainstSsoState($resourceOwner, $ssoState);

        /** @var \Passbolt\Sso\Utility\Azure\ResourceOwner\AzureResourceOwner $resourceOwner */
        $this->assertAuthTime($resourceOwner->getAuthTime(), $ssoState->created->getTimestamp());
    }

    // HELPERS

    /**
     * @param int|null $authTime `auth_time` received from Azure. The value can be `null` when the claim is not added in
     *                           the Azure AD admin console since it's an optional claim.
     * @param int $ssoStateCreatedAt SSO state created timestamp.
     * @return void
     */
    private function assertAuthTime(?int $authTime, int $ssoStateCreatedAt): void
    {
        $ssoSettingsData = $this->getSettings()->getData()->toArray();

        if ($ssoSettingsData['prompt'] === SsoSettingsAzureDataForm::PROMPT_NONE) {
            return;
        }

        if ($authTime !== null && $authTime < $ssoStateCreatedAt) {
            $msg = __('Single sign-on failed.') . ' ' . __('You must authenticate with Azure again.');
            throw new BadRequestException($msg);
        }
    }
}
