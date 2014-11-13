<?php

/*
 * This file is part of the Imagine package.
 *
 * (c) Bulat Shakirzyanov <mallluhuct@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (intval(ini_get('memory_limit')) < 64) {
    ini_set('memory_limit', '64M');
}

$loader = require __DIR__.'/../vendor/autoload.php';
$loader->add('Imagine\Test', __DIR__);
