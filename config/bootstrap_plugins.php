<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         3.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Core\Plugin;

/**
 * Insert below all passbolt plugins.
 */

// Add remember me plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'RememberMe')) {
    Plugin::load('Passbolt/RememberMe', ['bootstrap' => true, 'routes' => false]);
}

// Add tags plugin if present.
if (defined('PASSBOLT_IS_CONFIGURED') && !PASSBOLT_IS_CONFIGURED) {
    if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'WebInstaller')) {
        Plugin::load('Passbolt/WebInstaller', ['bootstrap' => true, 'routes' => true]);
    }
}
