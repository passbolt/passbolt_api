Installation
============

Make sure you've checked the [requirements](Requirements.md) first!

Using Composer
--------------

Assuming your app folder is called app add this to your projects root folder in composer.js.

```js
{
	"config": {
		"vendor-dir": "app/Vendor/",
		"preferred-install": "source"
	},
	"require": {
		"burzum/file-storage": "master",
		"knplabs/gaufrette": "*"
	},
	"extra": {
		"installer-paths": {
			"app/Plugin/FileStorage": ["burzum/file-storage"],
		}
	}
}
```

Using Git
---------

Go into your project folders root and add the submodule.

	git submodule add git://github.com/burzum/cakephp-file-storage-plugin.git Plugin/FileStorage

This plugin depends on the Gaufrette library (https://github.com/KnpLabs/Gaufrette), init the submodule, the plugin depends on it.

	cd app/Plugin/FileStorage
	git submodule update --init

If you do not want to add it as submodule just clone it instead of doing submodule add

	cd app/Plugin/FileStorage
	git clone git://github.com/burzum/cakephp-file-storage-plugin.git

It is **not** recommended to just clone it but instead setting it up as submodule.

CakePHP Bootstrap
-----------------

Add the following part to your ```app/Config/bootstrap.php```.

```php
App::uses('CakeEventManager', 'Event');
App::uses('FileStorageUtils', 'FileStorage.Lib/Utility');
App::uses('StorageManager', 'FileStorage.Lib');
App::uses('LocalImageProcessingListener', 'FileStorage.Event');
App::uses('LocalFileStorageListener', 'FileStorage.Event');
App::uses('FileStorageListener', 'FileStorage.Event');

// Only required if you're *NOT* using composer or another autoloader!
spl_autoload_register(__NAMESPACE__ .'\FileStorageUtils::gaufretteLoader');

$listener = new LocalFileStorageListener();
CakeEventManager::instance()->attach($listener);

// For automated image processing you'll have to attach this listener as well
$listener = new ImageProcessingListener();
CakeEventManager::instance()->attach($listener);
```

Adapter Specific Configuration
------------------------------

Depending on the storage backend of your choice, for example Amazon S3 or Dropbox, you'll very likely need additional vendor libs and extended adapter configuration.

Please see the (Specific Adapter Configuration)[Specific-Adapter-Configurations.md] page of the documentation for more information about then. It is also worth checking the Gaufrette documentation for additonal adapters.