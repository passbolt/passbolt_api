#!/usr/bin/php -q
<?php
/**
 * Command-line code generation utility to automate programmer chores.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$minVersion = '5.6.0';
if (file_exists('composer.json')) {
    $composer = json_decode(file_get_contents('composer.json'));
    if (isset($composer->require->php)) {
        $minVersion = preg_replace('/([^0-9\.])/', '', $composer->require->php);
    }
}
if (version_compare(phpversion(), $minVersion, '<')) {
    fwrite(STDERR, sprintf("Minimum PHP version: %s. You are using: %s.\n", $minVersion, phpversion()));
    exit(-1);
}

require dirname(__DIR__) . '/vendor/autoload.php';
include dirname(__DIR__) . '/config/bootstrap.php';

exit(Cake\Console\ShellDispatcher::run($argv));
