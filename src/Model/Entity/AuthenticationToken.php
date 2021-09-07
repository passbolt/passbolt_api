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
}
