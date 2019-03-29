<?php
use Cake\Core\Configure;

$importPluginEnabled = Configure::read('passbolt.plugins.import.enabled');
if (!isset($importPluginEnabled) || $importPluginEnabled === true) {
    Configure::load('Passbolt/Import.config', 'default', true);
}
