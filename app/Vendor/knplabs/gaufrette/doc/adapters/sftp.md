SFTP
====

*N.B.* SFTP adapter is not recommended to use due to https://bugs.php.net/bug.php?id=64169. It is recommended to use
[phpseclib SFTP adapter](phpseclib_sftp.md) instead.

This adapter is based on the `ssh2` extension. If you don't have this extension available and you can't install it,
the [`PhpseclibSftp`](phpseclibSftp.md) adapter is based on a full-php ssh client.

Prerequisites
-------------

* [PHP-SSH](https://github.com/Herzult/php-ssh)
* [SSH2 extension](http://www.php.net/manual/en/book.ssh2.php)

You can install it via:

```bash
composer require herzult/php-ssh:^1.1
pecl install ssh2-beta
```

Example
-------

The first argument should be an instance of `\Ssh\Client`. Please refer to 
[`herzult/php-ssh`](https://github.com/Herzult/php-ssh) documentation to know how to build it.

The second argument is the base directory you want to use.

The third one indicates whether you want to automatically create directories if they does not exists 
(i.e. when you create a file in a directory that does not exist yet).

```php
<?php

use Gaufrette\Adapter\Sftp as SftpAdapter;
use Gaufrette\Filesystem;

$adapter = new SftpAdapter($sftpClient, '/media', true);
$filesystem = new Filesystem($adapter);
```
