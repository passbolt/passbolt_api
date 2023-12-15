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

namespace Passbolt\Sso\Utility\Adfs\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Passbolt\Sso\Error\Exception\AdfsException;
use Passbolt\Sso\Error\Exception\OAuth2Exception;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Utility\Adfs\ResourceOwner\AdfsResourceOwner;
use Passbolt\Sso\Utility\OAuth2\Provider\OAuth2Provider;
use Psr\Http\Message\ResponseInterface;

class AdfsProvider extends OAuth2Provider
{
    use BearerAuthorizationTrait;

    /**
     * Email claim field.
     *
     * @var string
     */
    public string $emailClaim = SsoSetting::ADFS_EMAIL_CLAIM_UPN;

    /**
     * @inheritDoc
     */
    public function __construct(array $options = [], array $collaborators = [])
    {
        $options['emailClaim'] = $options['emailClaim'] ?? $this->emailClaim;

        parent::__construct($options, $collaborators);
    }

    /**
     * @inheritDoc
     */
    protected function checkResponse(ResponseInterface $response, $data): void
    {
        try {
            parent::checkResponse($response, $data);
        } catch (OAuth2Exception $e) {
            // Map OAuth2 exception with ADFS exception
            throw new AdfsException($data['error'], $data['error_description']);
        }
    }

    /**
     * @inheritDoc
     */
    protected function createResourceOwner(array $response, AccessToken $token): ResourceOwnerInterface
    {
        return new AdfsResourceOwner($response, $this->emailClaim);
    }
}
