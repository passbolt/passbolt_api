AzureBlobStorage
==================

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

Example
-------

```php
<?php

$connectionString = '...';
$factory = new Gaufrette\Adapter\AzureBlobStorage\BlobProxyFactory($connectionString);
$adapter = new Gaufrette\Adapter\AzureBlobStorage($factory, 'my-container');
$filesystem = new Gaufrette\Filesystem($adapter);
```
