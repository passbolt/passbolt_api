<?php

use Cake\Core\Configure;

$pluginEnabled = Configure::read('passbolt.plugins.toptResourceType.enabled');
if (!isset($pluginEnabled) || $pluginEnabled === true) {
    Configure::load('Passbolt/TotpResourceType.config', 'default', true);
}
