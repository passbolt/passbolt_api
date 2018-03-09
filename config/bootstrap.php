<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
use Cake\Core\Configure;

// Load the plugin configuration.
$config = require(__DIR__ . DS . 'config.php');
Configure::write($config);

// Test gpg keys folder path.
if (!defined('PASSBOLT_TEST_DATA_GPGKEY_PATH')) {
	define('PASSBOLT_TEST_DATA_GPGKEY_PATH', __DIR__ . DS . 'gpg');
}
// Test avatars folder path.
if (!defined('PASSBOLT_TEST_DATA_AVATAR_PATH')) {
	define('PASSBOLT_TEST_DATA_AVATAR_PATH', __DIR__ . DS . 'img' . DS . 'avatar');
}
