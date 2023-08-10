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
 * @since         3.11.0
 */

namespace Passbolt\Sso\Model\Entity;

use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * SsoState Entity
 *
 * @property string $id
 * @property string $nonce
 * @property string $type
 * @property string $state
 * @property string $sso_settings_id
 * @property string|null $user_id
 * @property string $user_agent
 * @property string $ip
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $deleted
 *
 * @property \Passbolt\Sso\Model\Entity\SsoSetting $sso_setting
 * @property \App\Model\Entity\User $user
 */
class SsoState extends Entity
{
    /**
     * Types.
     */
    // @deprecated
    public const TYPE_SSO_STATE = 'sso_state';
    public const TYPE_SSO_GET_KEY = 'sso_get_key';
    public const TYPE_SSO_SET_SETTINGS = 'sso_set_settings';
    public const TYPE_SSO_RECOVER = 'sso_recover';

    /**
     * Default length
     */
    public const DEFAULT_LENGTH_STATE = 32;
    public const DEFAULT_LENGTH_NONCE = 32;

    /**
     * Default expiry duration
     */
    public const DEFAULT_EXPIRY_DURATION_STATE = '10 minutes';

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'nonce' => false,
        'type' => false,
        'state' => false,
        'sso_settings_id' => false,
        'user_id' => false,
        'user_agent' => false,
        'ip' => false,
        'created' => false,
        'deleted' => false,
        'sso_setting' => false,
        'user' => false,
    ];

    /**
     * Returns random ASCII string containing the hexadecimal representation of string value to be used as state, nonce, etc.
     * It provides 16 bytes of entropy (128bits) by default (32 hex string length).
     *
     * @param int $length Length of the random string to be generated.
     * @return string
     * @throws \Exception
     */
    public static function generate(int $length = 32): string
    {
        return bin2hex(random_bytes($length / 2));
    }

    /**
     * Checks if given state/nonce is valid.
     *
     * @param string $value State/nonce value to check.
     * @return bool
     */
    public static function isValidState(string $value): bool
    {
        if (strlen($value) !== SsoState::DEFAULT_LENGTH_STATE) {
            return false;
        }
        if (!mb_check_encoding($value, 'ASCII') || !ctype_xdigit($value)) {
            return false;
        }

        return true;
    }

    /**
     * @return \Cake\I18n\FrozenTime
     */
    public function getExpiryTime(): FrozenTime
    {
        return $this->deleted;
    }

    /**
     * Returns state expiry duration.
     *
     * @return string
     */
    public static function getExpiryDuration(): string
    {
        $expiryDuration = Configure::read(
            sprintf('passbolt.auth.token.%s.expiry', SsoState::TYPE_SSO_STATE)
        );

        // Fallback to safe default if value is not present in config
        if ($expiryDuration === null) {
            $expiryDuration = self::DEFAULT_EXPIRY_DURATION_STATE;
        }

        return $expiryDuration;
    }

    /**
     * Checks if this SSO state is expired or not.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->deleted->isPast();
    }

    /**
     * Checks if user_id field should be mandatory or not.
     *
     * @return bool
     */
    public function isUserIdMandatory(): bool
    {
        return in_array(
            $this->type,
            [SsoState::TYPE_SSO_SET_SETTINGS, SsoState::TYPE_SSO_GET_KEY]
        );
    }
}
