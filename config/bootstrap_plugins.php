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
// Add passbolt pro main plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'Pro')) {
    Plugin::load('Passbolt/Pro', ['bootstrap' => true, 'routes' => false]);
}

// Add webinstaller plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'WebInstaller')) {
    Plugin::load('Passbolt/WebInstaller', ['bootstrap' => true, 'routes' => true]);
}

// Add tags plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'Tags')) {
    Plugin::load('Passbolt/Tags', ['bootstrap' => true, 'routes' => true]);
}

// Add import plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'Import')) {
    Plugin::load('Passbolt/Import', ['bootstrap' => true, 'routes' => true]);
}

// Add export plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'Export')) {
    Plugin::load('Passbolt/Export', ['bootstrap' => true, 'routes' => false]);
}

// Add remember me plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'RememberMe')) {
    Plugin::load('Passbolt/RememberMe', ['bootstrap' => true, 'routes' => false]);
}

// Add license plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'License')) {
    Plugin::load('Passbolt/License', ['bootstrap' => true, 'routes' => false]);
}

// Add AccountSettings plugin if present
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'AccountSettings')) {
    Plugin::load('Passbolt/AccountSettings', ['bootstrap' => true, 'routes' => true]);
}