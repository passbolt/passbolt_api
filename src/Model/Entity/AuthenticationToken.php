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
 * @since         2.0.0
 */
namespace App\Model\Entity;

use App\Service\AuthenticationTokens\AuthenticationTokensSessionService;
use App\Utility\AuthToken\AuthTokenExpiry;
use Cake\ORM\Entity;

/**
 * AuthenticationToken Entity
 *
 * @property string $id
 * @property string $token
 * @property string $user_id
 * @property string $type
 * @property string|null $data
 * @property bool $active
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\User $user
 */
class AuthenticationToken extends Entity
{
    public const TYPE_RECOVER = 'recover';
    public const TYPE_REGISTER = 'register';
    public const TYPE_MFA = 'mfa';
    public const TYPE_LOGIN = 'login';
    public const TYPE_MOBILE_TRANSFER = 'mobile_transfer';
    public const TYPE_REFRESH_TOKEN = 'refresh_token';
    public const TYPE_VERIFY_TOKEN = 'verify_token';

    public const SESSION_ID_KEY = 'session_id';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'id' => false,
        'user_id' => false,
        'token' => false,
        'active' => false,
        'type' => false,
        'data' => false,
    ];

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isNotActive(): bool
    {
        return !$this->active;
    }

    /**
     * @param string|null $expiry Expiry in word format.
     * @return bool
     */
    public function isExpired(?string $expiry = null): bool
    {
        if (empty($expiry)) {
            $expiry = (new AuthTokenExpiry())->getExpiryForTokenType($this->type);
        }
        $isNotExpired = $this->created->wasWithinLast($expiry);

        return !$isNotExpired;
    }

    /**
     * Json decode the token data.
     * Will return an empty array if the data is not unserializable.
     *
     * @return array
     */
    public function getJsonDecodedData(): array
    {
        return json_decode($this->data ?? '', true) ?? [];
    }

    /**
     * Reads the session ID in the token data.
     * The session ID is stored hashed.
     *
     * @see SessionIdentificationServiceInterface
     * @return string|null
     */
    public function getHashedSessionId(): ?string
    {
        $data = $this->getJsonDecodedData();

        return $data[self::SESSION_ID_KEY] ?? null;
    }

    /**
     * Writes the session ID in the token data
     *
     * The password hasher has a limit of 71 characters.
     * Therefore two sessions starting with the same 71 characters will
     * be tested as identical, although they are not.
     * Nullable bytes should also not be placed in the password
     *
     * Therefore we hash with sha256 the session ID before hashing it with password_hash.
     *
     * @see SessionIdentificationServiceInterface
     * @param string $sessionId Session ID
     * @return void
     */
    public function hashAndSetSessionId(string $sessionId): void
    {
        $hashedSessionId = (new AuthenticationTokensSessionService())->hash($sessionId);
        $data = array_merge(
            $this->getJsonDecodedData(),
            [self::SESSION_ID_KEY => $hashedSessionId]
        );

        $this->set('data', json_encode($data));
    }

    /**
     * Checks that the session ID provided
     * matches the hashed session ID in data->session_id.
     *
     * The session ID can be a string or another authentication token.
     *
     * @param \App\Model\Entity\AuthenticationToken|string|null $sessionIdentifier Session ID to check
     * @return bool
     */
    public function checkSessionId($sessionIdentifier): bool
    {
        return (new AuthenticationTokensSessionService())->checkSession($this, $sessionIdentifier);
    }
}
