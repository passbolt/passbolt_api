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
 * @since         5.2.0
 */
namespace Passbolt\UserKeyPolicies;

use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\BasePlugin;

class UserKeyPoliciesPlugin extends BasePlugin
{
    use FeaturePluginAwareTrait;

    public const PREFERRED_KEY_TYPE_CONFIG_KEY = 'passbolt.plugins.userKeyPolicies';

    public const PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_TYPE = 'PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_TYPE'; // phpcs:ignore
    public const PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_SIZE = 'PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_SIZE'; // phpcs:ignore
    public const PREFERRED_KEY_TYPE_ENV_KEY_PREFERRED_KEY_CURVE = 'PASSBOLT_PLUGINS_USER_KEY_POLICIES_PREFERRED_KEY_CURVE'; // phpcs:ignore
}
