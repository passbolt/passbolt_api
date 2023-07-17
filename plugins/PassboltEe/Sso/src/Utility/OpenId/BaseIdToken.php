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
namespace Passbolt\Sso\Utility\OpenId;

use App\Model\Validation\EmailValidationRule;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Firebase\JWT\JWT;
use League\OAuth2\Client\Token\AccessToken;
use Passbolt\Sso\Utility\Provider\BaseOauth2Provider;

/**
 * Extend BaseAccessToken to include id_token support
 * id_token is OIDC specific (e.g. on top of OAuth2)
 */
class BaseIdToken extends AccessToken
{
    /**
     * @var \Passbolt\Sso\Utility\Provider\BaseOauth2Provider $provider provider
     */
    protected $provider;

    /**
     * @var string
     */
    protected $idToken;

    /**
     * @var array
     */
    protected $idTokenClaims;

    /**
     * @param array $options such as access_token, refresh_token and id_token
     * @param \Passbolt\Sso\Utility\Provider\BaseOauth2Provider $provider provider
     * @throws \Cake\Http\Exception\InternalErrorException if keys to verify JWT cannot be fetched or validated
     * @throws \Cake\Http\Exception\BadRequestException if JWT doesn't validate
     */
    public function __construct(array $options, BaseOauth2Provider $provider)
    {
        parent::__construct($options);

        $this->provider = $provider;

        if (empty($options['id_token']) || !is_string($options['id_token'])) {
            throw new BadRequestException(__('JWT token is missing.'));
        }
        $this->idToken = $options['id_token'];
        unset($this->values['id_token']);

        $keys = $provider->getJwtVerificationKeys();
        try {
            /**
             * To fix "Firebase\JWT\BeforeValidException: Cannot handle token prior" error.
             *
             * @link https://github.com/googleapis/google-api-php-client/issues/1630
             * @link https://stackoverflow.com/questions/53658600/uncaught-exception-firebase-jwt-beforevalidexception-with-message-cannot-hand
             */
            JWT::$leeway = Configure::read('passbolt.plugins.sso.security.jwtLeeway');

            $tokenClaims = (array)JWT::decode($this->idToken, $keys);
        } catch (\Exception $exception) {
            throw new BadRequestException(__('Unable to decode JWT token.'), 400, $exception);
        }

        $this->assertTokenClaims($tokenClaims);

        $this->idTokenClaims = $tokenClaims;
    }

    /**
     * Validate the access token claims from an access token you received in your application.
     * Note: nbf and exp claims are validated in JWT::decode
     *
     * @param array $tokenClaims The token claims from an access token you received in the authorization header.
     * @throws \Cake\Http\Exception\BadRequestException if any of the claim is invalid
     * @return void
     */
    public function assertTokenClaims(array $tokenClaims): void
    {
        if (empty($tokenClaims)) {
            throw new BadRequestException('No claims');
        }

        if (
            !isset($tokenClaims['aud']) || !is_string($tokenClaims['aud']) ||
            $this->provider->getClientId() != $tokenClaims['aud']
        ) {
            throw new BadRequestException('The aud (client id) parameter is invalid');
        }

        if (
            !isset($tokenClaims['iss']) || !is_string($tokenClaims['iss']) ||
            $tokenClaims['iss'] != $this->provider->getOpenIdBaseUri()
        ) {
            throw new BadRequestException('The iss (issuer) parameter is invalid.');
        }

        if (!isset($tokenClaims['email']) || !EmailValidationRule::check($tokenClaims['email'])) {
            throw new BadRequestException('The email claim is not found or invalid.');
        }
    }

    /**
     * @return string id_token
     */
    public function getIdToken(): string
    {
        return $this->idToken;
    }

    /**
     * @return array claims from JWT::decode(id_token)
     */
    public function getIdTokenClaims(): array
    {
        return $this->idTokenClaims;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        $parameters = parent::jsonSerialize();

        if ($this->idToken) {
            $parameters['id_token'] = $this->idToken;
        }

        return $parameters;
    }
}
