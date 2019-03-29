<?php
use Cake\Core\Configure;

$exportPluginEnabled = Configure::read('passbolt.plugins.export.enabled');
if (!isset($exportPluginEnabled) || $exportPluginEnabled === true) {
    Configure::load('Passbolt/Export.config', 'default', true);
}
