OpenCloud & LazyOpenCloud
=========================

To use the OpenCloud adapter you will need to create a connection using the [OpenCloud SDK](https://github.com/rackspace/php-opencloud).
You can then fetch the ObjectStore which is required for the OpenCloud adapter.

OpenCloud Example
-----------------

```php
<?php

use OpenCloud\OpenStack;
use Gaufrette\Adapter\OpenCloud as OpenCloudAdapter;

$connection = new OpenCloud\OpenStack(
    'https://example.com/v2/identity',
    array(
        'username' => 'your username',
        'password' => 'your Keystone password',
        'tenantName' => 'your tenant (project) name'
    )
);
$objectStore = $connection->objectStoreService('cloudFiles', 'LON', 'publicURL');

$adapter = new OpenCloudAdapter(
    $objectStore,
    'container-name'
);
$filesystem = new Filesystem($adapter);
```

### Rackspace

Rackspace uses a difference connection class

```php
<?php

use OpenCloud\Rackspace;
use Gaufrette\Adapter\OpenCloud as OpenCloudAdapter;

$connection = new OpenCloud\Rackspace(
     'https://identity.api.rackspacecloud.com/v2.0/',
     array(
         'username' => 'rackspace-user',
         'apiKey' => '0900af093093788912388fc09dde090ffee09'
     )
);

$objectStore = $connection->objectStoreService('cloudFiles', 'LON', 'publicURL');

$adapter = new OpenCloudAdapter(
    $objectStore,
    'container-name'
);
$filesystem = new Filesystem($adapter);
```

LazyOpenCloud Example
---------------------

Instantiating the OpenCloud object store service has some overhead because it issues an authentication request,
even if you end up not using the filesystem. For better performance you can use a lazy-loading adapter which only authenticates when needed.

```php
<?php

// ... $connection from previous step, either OpenCloud\OpenStack or OpenCloud\Rackspace instance

$factory = new Gaufrette\Adapter\OpenStackCloudFiles\ObjectStoreFactory($connection);
$adapter = new Gaufrette\Adapter\LazyOpenCloud($factory, 'container-name');

$filesystem = new Filesystem($adapter);
```
