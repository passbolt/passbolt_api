[![Stories in Ready](https://badge.waffle.io/knplabs/gaufrette.png?label=ready&title=Ready)](https://waffle.io/knplabs/gaufrette)
Gaufrette
=========

Gaufrette is a PHP5 library that provides a filesystem abstraction layer.

This project is under intensive development but we do not want to break BC.

[![Build Status](https://secure.travis-ci.org/KnpLabs/Gaufrette.png)](http://travis-ci.org/KnpLabs/Gaufrette)

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

### Installation

Development version:

```bash
php composer.phar require knplabs/gaufrette:0.2.*@dev
```
Stable version:

```bash
php composer.phar require knplabs/gaufrette:0.1.*
```

### Setup your filesystem

Following an example with the local filesystem adapter. To setup other adapters, look up the [testcases](https://github.com/KnpLabs/Gaufrette/tree/master/tests/Gaufrette/Functional/adapters).

```php
<?php

use Gaufrette\Filesystem;
use Gaufrette\Adapter\Local as LocalAdapter;

$adapter = new LocalAdapter('/var/media');
$filesystem = new Filesystem($adapter);
```

### Use the filesystem

```php
<?php

// ... setup your filesystem

$content = $filesystem->read('myFile');

$content = 'Hello I am the new content';

$filesystem->write('myFile', $content);
```

### Use file objects

Gaufrette also provide a File class that is a representation of files in a filesystem

```php
<?php

$file = new File('newFile', $filesystem);
$file->setContent('Hello World');

echo $file->getContent(); // Hello World
```

### Cache a slow filesystem

If you have to deal with a slow filesystem, it is out of question to use it directly.
So, you need a cache! Happily, Gaufrette offers a cache system ready for use.
It consist of an adapter itself composed of two adapters:

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

Using Amazon S3
---------------
When using the legacy Amazon S3 adapters, you will need to specify a CA
certificate to be able to talk to Amazon servers in https. You can use
the one which is shipped with the SDK by defining before creating the
``\AmazonS3`` object:

```php
define("AWS_CERTIFICATE_AUTHORITY", true);
```

Specifying a custom CA certificate is not required when using the
`Gaufrette\Adapter\AmazonS3` adapter because it uses the newest version of the
AWS SDK for PHP.

If you use the newer adapter ``\AwsS3`` you will need to use the S3Client factory method, and the plug that into the Adapter.

```php
$service = S3Client::factory(array('key' => 'your_key_here', 'secret' => 'your_secret' ));
$client  = new AwsS3($service,'your-bucket-name');
```


Using OpenCloud
---------------
To use the OpenCloud adapter you will need to create a connection using the [OpenCloud SDK](https://github.com/rackspace/php-opencloud).
You can then fetch the ObjectStore which is required for the OpenCloud adapter.

### OpenCloud

```php
$connection = new OpenCloud\OpenStack(
    'https://example.com/v2/identity',
    array(
        'username' => 'your username',
        'password' => 'your Keystone password',
        'tenantName' => 'your tenant (project) name'
    ));

$objectStore = $connection->objectStoreService('cloudFiles', 'LON', 'publicURL');

$adapter = new Gaufrette\Adapter\OpenCloud(
    $objectStore,
    'container-name'
);

$filesystem = new Filesystem($adapter);

```

### Rackspace

Rackspace uses a difference connection class

```php
$connection = new OpenCloud\Rackspace(
     'https://identity.api.rackspacecloud.com/v2.0/',
     array(
         'username' => 'rackspace-user',
         'apiKey' => '0900af093093788912388fc09dde090ffee09'
     ));

```

### LazyOpenCloud

Instantiating the OpenCloud object store service has some overhead because it issues an authentication request,
even if you end up not using the filesystem. For better performance you can use a lazy-loading adapter which only authenticates when needed.

```php
// ... $connection from previous step, either OpenCloud\OpenStack or OpenCloud\Rackspace instance

$factory = new Gaufrette\Adapter\OpenStackCloudFiles\ObjectStoreFactory($connection);
$adapter = new Gaufrette\Adapter\LazyOpenCloud($factory, 'container-name');

$filesystem = new Filesystem($adapter);

```

Using AzureBlobStorage
----------------------
Azure Blob Storage is the storage service provided by Microsoft Windows Azure cloud environment. To use this adapter
you need to install the [Azure SDK for php](http://www.windowsazure.com/en-us/develop/php/common-tasks/download-php-sdk/)
into your project.

To instantiate the `AzureBlobStorage` adapter you need a `BlobProxyFactoryInterface` instance (you can use the default
`BlobProxyFactory` class) and a connection string. The connection string should follow this prototype:

    BlobEndpoint=https://XXXXXXXXXX.blob.core.windows.net/;AccountName=XXXXXXXX;AccountKey=XXXXXXXXXXXXXXXXXXXX

You should be able to find your **endpoint**, **account name** and **account key** in your
[Windows Azure management console](https://manage.windowsazure.com).

Thanks to the blob proxy factory, the adapter lazy loads the connection to the endpoint, so it will not create any
connection until it's really needed (eg. when a read or write operation is issued).

Follows a simple example on how to build the adapter:

```php
$connectionString = '...';
$factory = new Gaufrette\Adapter\AzureBlobStorage\BlobProxyFactory($connectionString);
$adapter = new Gaufrette\Adapter\AzureBlobStorage($factory, 'my-container');
$filesystem = new Gaufrette\Filesystem($adapter);
```

Using GoogleCloudStorage
------------------------

To use the GoogleCloudStorage adapter you will need to create a connection using the [Google APIs Client Library for PHP]
(https://github.com/google/google-api-php-client) and create a Client ID/Service Account in your [Developers Console]
(https://console.developers.google.com/). You can then create the `\Google_Service_Storage` which is required for the
GoogleCloudStorage adapter.

```php
<?php

use Gaufrette\Filesystem;
use Gaufrette\Adapter\GoogleCloudStorage;

$client_id = 'xxxxxxxxxxxxxxx.apps.googleusercontent.com';
$service_account_name = 'xxxxxxxxxxxxxxx@developer.gserviceaccount.com';
$key_file_location = 'key.p12';
$bucket_name = 'mybucket';

$client = new \Google_Client();
$client->setApplicationName('Gaufrette');

$key = file_get_contents($key_file_location);
$cred = new \Google_Auth_AssertionCredentials(
    $service_account_name,
    array(\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL),
    $key
);
$client->setAssertionCredentials($cred);
if ($client->getAuth()->isAccessTokenExpired()) {
    $client->getAuth()->refreshTokenWithAssertion($cred);
}

$service = new \Google_Service_Storage($client);
$adapter = new GoogleCloudStorage($service, $bucket_name, array(), true);

$filesystem = new Filesystem($adapter);
```

Using FTP adapters
---------------

Some FTP servers need valid configuration so Gaufrette can work with them as expected.

### Pure Ftpd

To handle hidden files we need to configure it by:

```bash
echo "yes" > /etc/pure-ftpd/conf/DisplayDotFiles
```

### Proftpd

To handle hidden files we need to change `ListOptions` in proftpd configuration (at debian system `/etc/proftpd/proftpd.conf` probably) to:

```bash
ListOptions  "-la"
```

Using Gaufrette in a Symfony2 project
-------------------------------------

As you can see, Gaufrette provides an elegant way to declare your filesystems.

In your Symfony2 project, add to ``deps``:

```ini
[gaufrette]
    git=https://github.com/KnpLabs/Gaufrette.git

# if you want to use Amazon S3
[aws-sdk]
    git=https://github.com/aws/aws-sdk-php.git
```

And then, you can simply add them as services of your dependency injection container.
As an example, here is services declaration to use Amazon S3:

```xml
<service id="acme.s3"
         class="Aws\\S3\\S3Client"
         factory-class="Aws\\S3\\S3Client"
         factory-method="factory">
    <argument type="collection">
        <argument key="key">%acme.aws_key%</argument>
        <argument key="secret">%acme.aws_secret_key%</argument>
    </argument>
</service>

<service id="acme.s3.adapter" class="Gaufrette\Adapter\AmazonS3">
    <argument type="service" id="acme.s3"></argument>
    <argument>%acme.s3.bucket_name%</argument>
</service>

<service id="acme.fs" class="Gaufrette\Filesystem">
    <argument type="service" id="acme.s3.adapter"></argument>
</service>
```

Streaming Files
---------------

Sometimes, you don't have the choice, you must get a streamable file URL (i.e
to transform an image). Let's take a look at the following example:

```php
$adapter = new InMemoryAdapter(array('hello.txt' => 'Hello World!'));
$filesystem = new Filesystem($adapter);

$map = StreamWrapper::getFilesystemMap();
$map->set('foo', $filesystem);

StreamWrapper::register();

echo file_get_contents('gaufrette://foo/hello.txt'); // Says "Hello World!"
```

Running the Tests
-----------------

The tests use phpspec2 and PHPUnit.

### Setup the vendor libraries

As some filesystem adapters use vendor libraries, you should install the vendors:

    $ cd gaufrette
    $ php composer.phar install --dev
    $ sh bin/configure_test_env.sh

It will avoid skip a lot of tests.

### Launch the Test Suite

In the Gaufrette root directory:

To check if classes specification pass:

    $ php bin/phpspec run

To check basic functionality of the adapters (adapters should be configured you will see many skipped tests):

    $ phpunit

Is it green?
