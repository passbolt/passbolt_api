GridFS
======

Prerequisites
-------------

In order to use GridFS adapter, you should have accesible MongoDB instance and [Mongo PHP driver](http://docs.php.net/manual/en/book.mongo.php) installed.

You can install the Mongo extension with

```bash
pecl install mongo
```

*N.B.* `mongo` php extension is deprecated in favor of [MongoDB PHP driver](http://mongodb.github.io/mongo-php-driver/#installation). We will switch to it right after [new API supports GridFS](http://mongodb.github.io/mongo-php-library/#mongodb-php-library]).

Usage
----

```php

$client = new \MongoClient('mongodb://localhost:27007'); 
$db = $client->selectDB('dbname');
$gridFs = new \MongoGridFs($db);

$adapter = new \Gaufrette\Adapter\GridFS($gridFs);

