<?php
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
 * @since         2.5.0
 */

use Cake\Core\Configure;
use Cake\Utility\Hash;

// Merge config
$mainConfig = Configure::read('passbolt.plugins.multiFactorAuthentication');
Configure::load('Passbolt/MultiFactorAuthentication.config', 'default', true);
if (isset($mainConfig)) {
    $pluginConfig = Configure::read('passbolt.plugins.multiFactorAuthentication');
    $newConfig = Hash::merge($pluginConfig, $mainConfig);
    Configure::write('passbolt.plugins.multiFactorAuthentication', $newConfig);
}
