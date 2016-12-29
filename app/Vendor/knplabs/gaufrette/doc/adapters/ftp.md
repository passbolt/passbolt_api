FTP
===

`ftp` extension should be enabled in order to use this adapter.
Also, some FTP servers need valid configuration so Gaufrette can work with them as expected.

Server configuration
--------------------

Some FTP servers does not show hidden files by default. You will probably need to tweak your server configuration.

### Pure Ftpd

```bash
echo "yes" > /etc/pure-ftpd/conf/DisplayDotFiles
```

### Proftpd

We need to change `ListOptions` in proftpd configuration (at debian system `/etc/proftpd/proftpd.conf`) to:

```bash
ListOptions  "-la"
```

Example
-------

The third argument of the `Ftp` adapter is not mandatory, however you can use it to pass configuration options 
(port, username, password, etc.):

```php
<?php

use Gaufrette\Adapter\Ftp as FtpAdapter;
use Gaufrette\Filesystem;

$adapter = new FtpAdapter('/media', 'my.host.com', array(
    'port'     => 21,
    'username' => 'my_username',
    'password' => 'my_password',
    'passive'  => true,
    'create'   => true, // Whether to create the remote directory if it does not exist
    'mode'     => FTP_BINARY, // Or FTP_TEXT
    'ssl'      => true,
));
$filesystem = new Filesystem($adapter);
```
