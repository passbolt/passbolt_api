Amazon S3
=========

First, you will need to install AWS SDK for PHP:
```bash
composer require aws/aws-sdk-php
```

Example
-------

```php
<?php

use Aws\S3\S3Client;
use Gaufrette\Adapter\AwsS3 as AwsS3Adapter;
use Gaufrette\Filesystem;

$s3client = S3Client::factory(array(
    'key'     => 'your_key_here',
    'secret'  => 'your_secret',
    'version' => 'latest',
    'region'  => 'eu-west-1',
));
$adapter = new AwsS3Adapter($s3client,'your-bucket-name');
$filesystem = new Filesystem($adapter);
```
