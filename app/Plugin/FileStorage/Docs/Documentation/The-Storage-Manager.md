The Storage Manager
===================

The [Storage Manager](Lib/StorageManager.php) class is a singleton class that manages a collection of storage adapter instances.

To configure adapters use the ```StorageManager::config()``` method. First argument is the name of the config, second an array of options for that adapter. The options array keys can be different for each adapter, depending on the storage system it connects to.

```php
StorageManager::config('Local', array(
	'adapterOptions' => array(TMP, true),
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem')
);
````

To invoke a new instance using a before set configuration call.

```php
$Adapter = StorageManager::adapter('Local');
```

You can also call the adapter instances methods like this

```php
StorageManager::adapter('Local')->write($key, $data);
```

Alternatively you can pass a config array as first argument to get an instance using these settings that is not in the configuration.

To delete configs and by this the instance from the StorageManager call

```php
StorageManager::flush('Local');
```

If you want to flush *all* adapter configs and instances simply call it without the argument.

```php
StorageManager::flush();
```

There will be no adapter instance left after this, you must add a new config to use any adapter.