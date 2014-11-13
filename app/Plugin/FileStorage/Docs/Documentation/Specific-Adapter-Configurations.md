# Specific Addapter Configuration

Gaufrette does not come with a lot detail about what exactly some adapters expect so here is a list to help you with that.

But you should not blindly copy and paste that code, get an understanding of the storage service you want to use before!

## AmazonS3 - AwsS3 Adapter

Get the SDK from here https://github.com/aws/aws-sdk-php or get it via composer ```amazonwebservices/aws-sdk-for-php```. If you're not using composer you'll have to add it to your own autoloader or load it manually.

```php
use Aws\S3;

$S3Client = \Aws\S3\S3Client::factory(array(
	'key' => 'YOUR-KEY',
	'secret' => 'YOUR-SECRET'
));

StorageManager::config('S3Image', array(
	'adapterOptions' => array(
		$S3Client,
		'YOUR-BUCKET-HERE',
		array(),
		true
	),
	'adapterClass' => '\Gaufrette\Adapter\AwsS3',
	'class' => '\Gaufrette\FileSystem')
);
```

## AmazonS3 - AmazonS3 Adapter (legacy)

*This adapter is legacy code, you should use the AwsS3 adapter instead!*

Get the SDK from here http://github.com/amazonwebservices/aws-sdk-for-php and load the sdk.class.php file from where ever you cloned the SDK. Or get it via composer ```amazonwebservices/aws-sdk-for-php```.

```php
require_once(APP . 'Vendor' . DS . 'AwsSdk' . DS . 'sdk.class.php');

CFCredentials::set(array(
	'production' => array(
			'certificate_authority' => true,
			'key' => 'YOUR-KEY',
			'secret' => 'YOUR-SECRET'
		)
	)
);
$s3 = new AmazonS3();

StorageManager::config('S3', array(
	'adapterOptions' => array(
		$s3,
		'YOUR-BUCKET-HERE'
	),
	'adapterClass' => '\Gaufrette\Adapter\AmazonS3',
	'class' => '\Gaufrette\Filesystem')
);
```

## OpenCloud (Rackspace)

Get the SDK from here http://github.com/rackspace/php-opencloud and add it to your class autoloader

```php
define('RAXSDK_SSL_VERIFYHOST', 0);
define('RAXSDK_SSL_VERIFYPEER', 0);

$connection = new \OpenCloud\Rackspace(
	'https://lon.identity.api.rackspacecloud.com/v2.0/', // Rackspace Auth URL
	array(
		'username' => 'YOUR-USERNAME',
		'apiKey' => 'YOUR-API-KEY'
	)
);

// LON (London) or DFW (Dallas)
$objstore = $connection->ObjectStore('cloudFiles', 'LON');

StorageManager::config('OpenCloudTest', array(
	'adapterOptions' => array(
		$objstore,
		'test1',
	),
	'adapterClass' => '\Gaufrette\Adapter\OpenCloud',
	'class' => '\Gaufrette\Filesystem')
);
```