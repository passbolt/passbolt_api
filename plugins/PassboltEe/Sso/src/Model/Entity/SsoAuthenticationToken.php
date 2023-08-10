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
namespace Passbolt\Sso\Model\Entity;

use App\Model\Entity\AuthenticationToken;
use Passbolt\Sso\Utility\AuthToken\SsoAuthTokenExpiry;

/**
 * SsoAuthenticationToken Entity
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
class SsoAuthenticationToken extends AuthenticationToken
{
    // AUTH TOKEN DATA PROPERTIES
    public const DATA_IP = 'ip';
    public const DATA_USER_AGENT = 'user_agent';
    public const DATA_SSO_SETTING_ID = 'sso_setting_id';

    /**
     * @return string
     */
    public function getExpiryDuration(): string
    {
        return (new SsoAuthTokenExpiry())->getExpiryForTokenType($this->type);
    }
}
