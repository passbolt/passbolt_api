Gaufrette
=========

Gaufrette is a PHP5 library that provides a filesystem abstraction layer.

This project does not have any stable release yet but we do not want to break BC now.

[![Build Status](https://secure.travis-ci.org/KnpLabs/Gaufrette.png)](http://travis-ci.org/KnpLabs/Gaufrette)
[![Join the chat at https://gitter.im/KnpLabs/Gaufrette](https://badges.gitter.im/KnpLabs/Gaufrette.svg)](https://gitter.im/KnpLabs/Gaufrette)
[![Stories in Ready](https://badge.waffle.io/knplabs/gaufrette.png?label=ready&title=Ready)](https://waffle.io/knplabs/gaufrette)

Symfony integration is available here: [KnpLabs/KnpGaufretteBundle](https://github.com/KnpLabs/KnpGaufretteBundle).

Documentation is available [here](doc/index.md).

Why use Gaufrette?
------------------

Imagine you have to manage a lot of medias in a PHP project. Lets see how to
take this situation in your advantage using Gaufrette.

The filesystem abstraction layer permits you to develop your application without
the need to know were all those medias will be stored and how.

Another advantage of this is the possibility to update the files location
without any impact on the code apart from the definition of your filesystem.
In example, if your project grows up very fast and if your server reaches its
limits, you can easily move your medias in an Amazon S3 server or any other
solution.

Try it!
-------


```bash
php composer.phar require knplabs/gaufrette:~0.2 # Stable version
php composer.phar require knplabs/gaufrette:0.4.*@dev # Development version
```

Following an example with the local filesystem adapter. To setup other adapters, look up [their respective documentation](https://github.com/KnpLabs/Gaufrette/tree/master/doc/#adapters).

```php
<?php

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;

// First, you need a filesystem adapter
$adapter = new LocalAdapter('/var/media');
$filesystem = new Filesystem($adapter);

// Then, you can access your filesystem directly
var_dump($filesystem->read('myFile')); // bool(false)
$filesystem->write('myFile', 'Hello world!');

// Or use File objects
$file = $filesystem->get('myFile');
// Will print something like: "myFile (modified 17/01/2016 18:40:36): Hello world!"
echo sprintf('%s (modified %s): %s', $file->getKey(), date('d/m/Y, H:i:s', $file->getMtime()), $file->getContent());
```

Running the Tests
-----------------

The tests use phpspec2 and PHPUnit.

### Setup the vendor libraries

As some filesystem adapters use vendor libraries, you should install the vendors:

    $ cd gaufrette
    $ php composer.phar install
    $ sh bin/configure_test_env.sh

It will avoid skip a lot of tests.

### Launch the Test Suite

In the Gaufrette root directory:

To check if classes specification pass:

    $ php bin/phpspec run

To check basic functionality of the adapters (adapters should be configured you will see many skipped tests):

    $ bin/phpunit

Is it green?
