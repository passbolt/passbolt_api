<?php

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
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
use Cake\Utility\Hash;

$existingConfig = Configure::read('passbolt.plugins.tags');
Configure::load('Passbolt/Tags.config', 'default', true);

if (!isset($existingConfig)) {
    $newConfig = Hash::merge(Configure::read('passbolt.plugins.tags'), $existingConfig);
    Configure::write('passbolt.plugins.tags', $newConfig);
}
