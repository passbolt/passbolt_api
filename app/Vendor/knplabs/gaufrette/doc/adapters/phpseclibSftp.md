### Documentation for Gaufrette phpseclib adapter

*N.B.* It is recommended to use this adapter over [SFTP](doc/adapters/sftp.md).

#### Prerequisites

* [phpseclib](https://github.com/phpseclib/phpseclib)

You can install it via:

```bash
composer require phpseclib/phpseclib:^2.0
```

#### Configuration

```php

$sftp = new phpseclib\Net\SFTP($host = 'localhost', $port = 22);

$adapter = new Gaufrette\Adapter\PhpseclibSftp($sftp, $distantDirectory = null, $createDirectoryIfDoesntExist = false, $username = 'gaufrette', $password = 'gaufrette');
$filesystem = new Gaufrette\Filesystem($adapter);
```
