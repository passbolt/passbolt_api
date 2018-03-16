<?php
use Cake\Core\Plugin;

// Add passbolt pro main plugin if present.
if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'Pro')) {
	Plugin::load('Passbolt/Pro', ['bootstrap' => true, 'routes' => false]);
	define('PASSBOLT_PRO', true);
}

// Add other pro plugins below.
