GoogleCloudStorage
==================

To use the GoogleCloudStorage adapter you will need to create a connection using the [Google APIs Client Library for PHP]
(https://github.com/google/google-api-php-client) and create a Client ID/Service Account in your [Developers Console]
(https://console.developers.google.com/). You can then create the `\Google_Service_Storage` which is required for the
GoogleCloudStorage adapter.

Example
-------

```php
<?php

use Gaufrette\Filesystem;
use Gaufrette\Adapter\GoogleCloudStorage;

$client = new \Google_Client();
$client->setClientId('xxxxxxxxxxxxxxx.apps.googleusercontent.com');
$client->setApplicationName('Gaufrette');

$cred = new \Google_Auth_AssertionCredentials(
    'xxxxxxxxxxxxxxx@developer.gserviceaccount.com',
    array(\Google_Service_Storage::DEVSTORAGE_FULL_CONTROL),
    file_get_contents('key.p12')
);
$client->setAssertionCredentials($cred);
if ($client->getAuth()->isAccessTokenExpired()) {
    $client->getAuth()->refreshTokenWithAssertion($cred);
}

$service = new \Google_Service_Storage($client);
$adapter = new Gaufrette\Adapter\GoogleCloudStorage($service, $config['gcsBucket'], array(
    'acl' => 'public',
), true);
$filesystem = new Gaufrette\Filesystem($adapter);
```
