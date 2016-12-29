ZIP
===

If you want to use this `Adapter`, first you should be sure `zip` extension is installed.

Example
-------

```php
<?php

use Gaufrette\Adapter\Zip as ZipAdapter;
use Gaufrette\Filesystem;

$adapter = new ZipAdapter('/path/to/my/zip/file');
$filesystem = new Filesystem($adapter);
```
