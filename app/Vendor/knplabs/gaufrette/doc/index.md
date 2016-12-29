Gaufrette Documentation
=============

Adapters
--------

  * [AmazonS3 & AclAwareAmazonS3](adapters/amazonS3.md)
  * [Apc](adapters/apc.md)
  * [AwsS3](adapters/awsS3.md)
  * [AzureBlobStorage](adapters/azureBlobStorage.md)
  * [Cache](#cache)
  * [Doctrine DBAL](adapters/doctrineDbal.md): TBW
  * [Dropbox](adapters/dropbox.md): TBW
  * [Ftp](adapters/ftp.md)
  * [Google Cloud Storage](adapters/googleCloudStorage.md)
  * [GridFS](adapters/gridFs.md)
  * [InMemory](adapters/inMemory.md)
  * [Local & SafeLocal](adapters/local.md)
  * [MogileFS](adapters/mogileFS.md): TBW
  * [OpenCloud & LazyOpenCloud](adapters/openCloud.md)
  * [PhpseclibSftp](adapters/phpseclibSftp.md)
  * [Sftp](adapters/sftp.md)
  * [Zip](adapters/zip.md)

Cache
-----

If you have to deal with a slow filesystem, it is out of question to use it directly.
So, you need a cache! Happily, Gaufrette offers a cache system ready for use.
It consist of an adapter decorator itself composed of two adapters:

    * The *source* adapter that should be cached
    * The *cache* adapter that is used to cache

Here is an example of how to cache an ftp filesystem:

```php
<?php

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Ftp as FtpAdapter;
use Gaufrette\Adapter\Local as LocalAdapter;
use Gaufrette\Adapter\Cache as CacheAdapter;

// Locale Cache-Directory (e.g. '%kernel.root_dir%/cache/%kernel.environment%/filesystem') with create = true
$local = new LocalAdapter($cacheDirectory, true);
// FTP Adapter with a defined root-path
$ftp = new FtpAdapter($path, $host, $username, $password, $port);

// Cached Adapter with 3600 seconds time to live
$cachedFtp = new CacheAdapter($ftp, $local, 3600);

$filesystem = new Filesystem($cachedFtp);
```

The third parameter of the cache adapter is the time to live of the cache.

Streaming Files
---------------

Sometimes, you don't have the choice, you must get a streamable file URL (i.e to use native file functions). 
Let's take a look at the following example:

```php
$adapter = new InMemoryAdapter(array('hello.txt' => 'Hello World!'));
$filesystem = new Filesystem($adapter);

$map = StreamWrapper::getFilesystemMap();
$map->set('foo', $filesystem);

StreamWrapper::register();

copy('gaufrette://foo/hello.txt', 'gaufrette://foo/world.txt');
unlink('gaufrette://foo/hello.txt');

echo file_get_contents('gaufrette://foo/world.txt'); // Says "Hello World!"
```
