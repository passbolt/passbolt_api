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

namespace Passbolt\Sso\Utility\PingOne\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Error\Exception\OAuth2Exception;
use Passbolt\Sso\Error\Exception\PingOneException;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Utility\OAuth2\Provider\OAuth2Provider;
use Passbolt\Sso\Utility\PingOne\ResourceOwner\PingOneResourceOwner;
use Psr\Http\Message\ResponseInterface;

class PingOneProvider extends OAuth2Provider
{
    use BearerAuthorizationTrait;

    /**
     * PingOne environment ID.
     *
     * @var string
     */
    protected string $environmentId = '';

    /**
     * Email claim field.
     *
     * @var string
     */
    public string $emailClaim = SsoSetting::PINGONE_EMAIL_CLAIM_EMAIL;

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $options['emailClaim'] = $options['emailClaim'] ?? $this->emailClaim;
        $options['environmentId'] = $options['environmentId'] ?? $this->environmentId;

        parent::__construct($options, $collaborators);
    }

    /**
     * Returns Open ID base URL with environment ID and authorization server path appended.
     *
     * PingOne requires the environment ID and `/as` segment in the URL path:
     * {base_url}/{environment_id}/as
     *
     * @return string
     */
    public function getOpenIdBaseUri(): string
    {
        return parent::getOpenIdBaseUri() . '/' . $this->environmentId . '/as';
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Passbolt\Sso\Error\Exception\PingOneException When error and error description is present
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException When unknown error faced
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        try {
            parent::checkResponse($response, $data);
        } catch (OAuth2Exception $e) {
            // Map OAuth2 exception with PingOne exception
            throw new PingOneException($data['error'], $data['error_description']);
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new PingOneResourceOwner($response, $this->emailClaim);
    }
}
