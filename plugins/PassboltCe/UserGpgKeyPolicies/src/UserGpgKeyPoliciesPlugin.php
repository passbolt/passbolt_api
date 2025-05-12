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
 * @since         5.1.1
 */
namespace Passbolt\UserGpgKeyPolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;

class UserGpgKeyPoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    public const PREFERRED_KEY_TYPE_CONFIG_KEY = 'passbolt.plugins.userGpgKeyPolicies.preferred_key_type';

    public const PREFERRED_KEY_TYPE_ENV_KEY =
        'PASSBOLT_PLUGINS_USER_GPG_KEY_POLICIES_PREFERRED_KEY_TYPE';
}
