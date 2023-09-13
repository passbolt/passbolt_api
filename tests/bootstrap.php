<?php
declare(strict_types=1);

/**
 * Test runner bootstrap.
 *
 * Add additional configuration/setup your application needs when running
 * unit tests in this file.
 */

use Cake\TestSuite\ConnectionHelper;
use Migrations\TestSuite\Migrator;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/config/bootstrap.php';

$_SERVER['PHP_SELF'] = '/';

(new ConnectionHelper())->addTestAliases();
(new Migrator())->run();
