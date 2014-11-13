<?php

require __DIR__ . '/../vendor/autoload.php';

use Sami\Sami;
use Symfony\Component\Finder\Finder;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->in('lib')
;

return new Sami($iterator, array(
    'title'                => 'Imagine API',
    'theme'                => 'enhanced',
    'build_dir'            => __DIR__.'/API/API',
    'cache_dir'            => __DIR__.'/_build/cache',
    'default_opened_level' => 2,
));
