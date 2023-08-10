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
 * @since         4.0.0
 */

namespace Passbolt\Sso\Utility\Provider;

use Cake\Http\Exception\InternalErrorException;
use Cake\Validation\Validation;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;

abstract class BaseOauth2Provider extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * @var array|null
     */
    protected $openIdConfiguration = null;

    /**
     * Returns Open ID configuration URL.
     *
     * @return string
     */
    abstract public function getOpenIdConfigurationUri(): string;

    /**
     * Get JWT verification keys from provider.
     *
     * @return array
     */
    abstract public function getJwtVerificationKeys();

    /**
     * Returns Open ID base URL.
     *
     * @return string
     */
    abstract public function getOpenIdBaseUri(): string;

    /**
     * ABSTRACT METHODS
     *
     * @see \League\OAuth2\Client\Provider\AbstractProvider
     */

    /**
     * @inheritDoc
     */
    public function getBaseAuthorizationUrl(): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();

        return $openIdConfiguration['authorization_endpoint'];
    }

    /**
     * @inheritDoc
     */
    public function getBaseAccessTokenUrl(array $params): string
    {
        $openIdConfiguration = $this->getOpenIdConfiguration();

        return $openIdConfiguration['token_endpoint'];
    }

    /**
     * @return array
     */
    protected function getOpenIdConfiguration(): array
    {
        if (isset($this->openIdConfiguration)) {
            return $this->openIdConfiguration;
        }

        $factory = $this->getRequestFactory();
        $request = $factory->getRequestWithOptions(
            'get',
            $this->getOpenIdConfigurationUri(),
            []
        );

        try {
            $response = $this->getParsedResponse($request);
        } catch (\Exception $exception) {
            throw new InternalErrorException($exception->getMessage(), 500, $exception);
        }

        $this->validateOpenIdConfiguration($response);
        $this->openIdConfiguration = $response;

        return $this->openIdConfiguration;
    }

    /**
     * Check the endpoints info we expect to use later are present
     *
     * @param mixed $response from .well-known
     * @return void
     */
    public function validateOpenIdConfiguration($response): void
    {
        if (!is_array($response)) {
            throw new InternalErrorException('Invalid response.');
        }
        if (!isset($response['jwks_uri'])) {
            throw new InternalErrorException('Invalid response. Missing JWKS URI');
        }
        if (!isset($response['authorization_endpoint'])) {
            throw new InternalErrorException('Invalid response. Missing authorization endpoint.');
        }
        if (!isset($response['token_endpoint'])) {
            throw new InternalErrorException('Invalid response. Missing token endpoint.');
        }
        if (!Validation::url($response['jwks_uri'])) {
            throw new InternalErrorException('Invalid response. Invalid JWKS URI');
        }
        if (!Validation::url($response['authorization_endpoint'])) {
            throw new InternalErrorException('Invalid response. Invalid authorization endpoint.');
        }
        if (!Validation::url($response['token_endpoint'])) {
            throw new InternalErrorException('Invalid response. Invalid token endpoint.');
        }
    }

    /**
     * @inheritDoc
     */
    protected function getAuthorizationParameters(array $options)
    {
        $options = parent::getAuthorizationParameters($options);

        /**
         * The "approval_prompt" MUST be removed as it is not supported by Google, use "prompt" instead:
         *
         * @link https://developers.google.com/identity/protocols/oauth2/openid-connect#prompt
         */
        unset($options['approval_prompt']);

        return $options;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }
}
