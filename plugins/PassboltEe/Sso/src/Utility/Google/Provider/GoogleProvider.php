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

namespace Passbolt\Sso\Utility\Google\Provider;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Error\Exception\GoogleException;
use Passbolt\Sso\Utility\Google\ResourceOwner\GoogleResourceOwner;
use Passbolt\Sso\Utility\Grant\JwtBearer;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;
use Psr\Http\Message\ResponseInterface;

class GoogleProvider extends AbstractOauth2Provider
{
    use BearerAuthorizationTrait;

    /**
     * @var string Default base url for OpenID.
     */
    public const GOOGLE_OPENID_BASE_URI = 'https://accounts.google.com';

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $options['openIdBaseUri'] = self::GOOGLE_OPENID_BASE_URI;

        parent::__construct($options, $collaborators);

        $this->grantFactory->setGrant('jwt_bearer', new JwtBearer());
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new GoogleResourceOwner($response);
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        if (is_string($data['error']) && isset($data['error_description']) && is_string($data['error_description'])) {
            throw new GoogleException($data['error'], $data['error_description']);
        } else {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                (string)$response->getBody()
            );
        }
    }
}
