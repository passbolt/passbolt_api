<?php

use Cake\Core\Configure;

Configure::load('Passbolt/TestData.config', 'default', true);

// Test gpg keys folder path.
if (!defined('PASSBOLT_TEST_DATA_GPGKEY_PATH')) {
    define('PASSBOLT_TEST_DATA_GPGKEY_PATH', __DIR__ . DS . 'gpg');
}
// Test avatars folder path.
if (!defined('PASSBOLT_TEST_DATA_AVATAR_PATH')) {
    define('PASSBOLT_TEST_DATA_AVATAR_PATH', __DIR__ . DS . 'img' . DS . 'avatar');
}

//Configure::load('PassboltTestData.version', 'default', true);
