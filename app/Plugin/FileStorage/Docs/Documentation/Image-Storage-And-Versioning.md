Image Versioning
================

You can set up automatic image processing for the FileStorage.Image model. To make the magic happen you have to use the Image model (it extends the FileStorage model) for image file saving.

All you need to do is basically use the image model and configure versions on a per model basis. When you save an Image model record it is important to have the 'model' field filled so that the script can find the correct versions for that model.

```php
Configure::write('Media', array(
	'imageSizes' => array(
		'GalleryImage' => array(
			'c50' => array(
				'crop' => array(
					'width' => 50, 'height' => 50
				)
			),
			't120' => array(
				'thumbnail' => array(
					'width' => 120, 'height' => 120
				)
			),
			't800' => array(
				'thumbnail' => array(
					'width' => 800, 'height' => 600
				)
			)
		),
		'User' => array(
			'c50' => array(
				'crop' => array(
					'width' => 50, 'height' => 50
				)
			),
			't150' => array(
				'crop' => array(
					'width' => 150, 'height' => 150)
				)
			),
		)
	)
);

App::uses('ClassRegistry', 'Utility');
ClassRegistry::init('FileStorage.Image')->generateHashes();
```

Calling ```generateHashes()``` is important, it will create the hash values for each versioned image and store them in Media.imageHashes in the configuration.

If you don't want to have the script to generate the hashes each time it's executed, it is up to you to store it persistent. This plugin just provides you the tools.

Image files will end up wherever you have configured your base path.

```
/ModelName/51/21/63/4c0f128f91fc48749662761d407888cc/4c0f128f91fc48749662761d407888cc.jpg
```

The versioned image files will be in the same folder, which is the id of the record, as the original image and have the truncated hash of the version attached but before the extension.

```
/ModelName/51/21/63/4c0f128f91fc48749662761d407888cc/4c0f128f91fc48749662761d407888cc.f91fsc.jpg
```

You should smylink your image root folder to APP/webroot/images for example to avoid that images go through php and are send directly instead.

Extending and Changing Image Versioning
---------------------------------------

It is possible to totally change the way image versions are created. You'll just have to extend or create new listeners and attach them to the global EventManager.