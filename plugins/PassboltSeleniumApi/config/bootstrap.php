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
use Cake\Core\Configure\Engine\PhpConfig;

/*
 * Load Selenium testing extra config if any
 * This allow redefining server config on the fly when running integration tests
 */
if (Configure::read('debug') && Configure::read('passbolt.selenium.active')) {
    if (file_exists(TMP . 'selenium' . DS . 'core_extra_config.php')) {
        Configure::config('extra_config', new PhpConfig(TMP . 'selenium' . DS));
        Configure::load('core_extra_config', 'extra_config', true);
    }
}
