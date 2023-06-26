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
namespace Passbolt\Sso\Utility\Azure\ResourceOwner;

use Cake\Http\Exception\BadRequestException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Passbolt\Sso\Utility\OpenId\SsoResourceOwnerInterface;

class AzureResourceOwner implements ResourceOwnerInterface, SsoResourceOwnerInterface
{
    /**
     * Response payload
     *
     * @var array
     */
    private $data;

    /**
     * @var string
     */
    private $emailAliasField;

    /**
     * Creates new azure resource owner.
     *
     * @param array $data user data
     * @param string $emailAliasField Field to use as an email.
     */
    public function __construct($data, $emailAliasField)
    {
        $this->data = $data;
        $this->emailAliasField = $emailAliasField;
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
     * @return string
     * @throws \Cake\Http\Exception\BadRequestException When email alias field is not present in the data.
     */
    public function getEmail(): string
    {
        if (!isset($this->data[$this->emailAliasField]) || is_null($this->data[$this->emailAliasField])) {
            $msg = __('Single sign-on failed.') . ' ';
            $msg .= __('The {0} claim is not present, please contact your administrator.', $this->emailAliasField);
            throw new BadRequestException($msg);
        }

        return $this->data[$this->emailAliasField];
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

    /**
     * Returns `auth_time` if present in data, `null` if not present.
     *
     * @return int|null
     */
    public function getAuthTime(): ?int
    {
        return $this->data['auth_time'] ?? null;
    }
}
