<?php

use Cake\Core\Configure;

$pluginEnabled = Configure::read('passbolt.plugins.resourceTypes.enabled');
if (!isset($pluginEnabled) || $pluginEnabled === true) {
    Configure::load('Passbolt/ResourceTypes.config', 'default', true);
}
