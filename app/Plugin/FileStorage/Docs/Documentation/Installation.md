Installation
============

Make sure you've checked the [requirements](Requirements.md) first!

Using Git
---------

Go into your project folders root and add the submodule.

	git submodule add git://github.com/burzum/cakephp-file-storage-plugin.git Plugin/FileStorage

This plugin depends on the Gaufrette library (https://github.com/KnpLabs/Gaufrette), init the submodule, the plugin depends on it.

	cd app/Plugin/FileStorage
	git submodule update --init

If you want to use S3 upload Gaufrette has also submodules to initialize. Here is the whole story to get everything initialized:

	cd YOUR-APP-FOLDER
	git submodule add git://github.com/burzum/FileStorage.git Plugin/FileStorage
	git submodule update --init --recursive

If you do not want to add it as submodule just clone it instead of doing submodule add

	cd app/Plugin/FileStorage
	git clone git://github.com/burzum/cakephp-file-storage-plugin.git

It is **not** recommended to just clone it but instead setting it up as submodule.

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
		"burzum/FileStorage": "master",
		"knplabs/gaufrette": "*"
	},
	"extra": {
		"installer-paths": {
			"app/Plugin/FileStorage": ["burzum/FileStorage"],
		}
	}
}
```

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

