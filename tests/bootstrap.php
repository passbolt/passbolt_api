<?php
declare(strict_types=1);

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\ConnectionHelper;
use Migrations\TestSuite\Migrator;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Service\EditionManager;
use Passbolt\Edition\Test\Lib\TestingEditionManager;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/bootstrap.php';
EditionManager::setInstance(new TestingEditionManager());
Configure::write('passbolt.edition', EditionDto::EDITION_PRO);

// Disabling secrets.validateRecipient configuration here so it doesn't fail all existing tests
// Once all tests starts using valid secret for their recipient (SecretFactory default data, withValidGpgKey()) then this can be removed
Configure::write('passbolt.security.secrets.validateRecipient', false);

$_SERVER['PHP_SELF'] = '/';

// Fixate sessionid early on, as php7.2+
// does not allow the sessionid to be set after stdout
// has been written to.
session_id('cli');

ConnectionHelper::addTestAliases();

/** @var \Cake\Database\Connection $connection */
$connection = ConnectionManager::get('default');
$connection->cacheMetadata(false);

(new Migrator())->run();
