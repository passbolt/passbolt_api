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
namespace Passbolt\Sso\Utility\Google\ResourceOwner;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;

class GoogleResourceOwner implements ResourceOwnerInterface, SsoResourceOwnerInterface
{
    /**
     * Response payload
     *
     * @var array
     */
    protected $data;

    /**
     * Creates new google resource owner.
     *
     * @param array $data user data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retrieves id of resource owner.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->data['oid'] ?? null;
    }

    /**
     * Retrieves email of the resource owner.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->data['email'] ?? null;
    }

    /**
     * Returns all the data obtained about the user.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * @inheritDoc
     */
    public function getNonce(): ?string
    {
        return $this->data['nonce'] ?? null;
    }
}
