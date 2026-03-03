#!/usr/bin/php -q
<?php
// Check platform requirements
require dirname(__DIR__) . '/config/requirements.php';
require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use Cake\Console\CommandRunner;

// Ensure CWD is the project root to avoid proc_open permission errors (e.g. when invoked from /root)
chdir(dirname(__DIR__));

// Build the runner with an application and root executable name.
$runner = new CommandRunner(new Application(dirname(__DIR__) . '/config'), 'cake');
exit($runner->run($argv));
