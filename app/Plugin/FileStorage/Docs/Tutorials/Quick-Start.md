Quick-Start
===========

```js
{
	"config": {
		"vendor-dir": "app/Vendor/",
		"preferred-install": "source"
	},
	"require": {
		"burzum/cakephp-imagine-plugin": "dev-master",
		"cakedc/migrations": "dev-master",
		"knplabs/gaufrette": "dev-master",
		"imagine/imagine": "dev-master"
	},
	"extra": {
		"installer-paths": {
			"app/Plugin/FileStorage": ["burzum/FileStorage"],
			"app/Plugin/Imagine": ["burzum/cakephp-imagine-plugin"]
		}
	}
}
```

app/Config/file_storage.php
---------------------------

There is a good amount of code to be added to prepare everything. In theory you can put all of this in bootstrap as well but to keep things clean it is high recommended to put all of this in a separate file.

```php
use Aws\S3;
App::uses('S3StorageListener', 'FileStorage.Event');
App::uses('FileStorageUtils', 'FileStorage.Lib/Utility');
App::uses('StorageManager', 'FileStorage.Lib');
App::uses('ImageProcessingListener', 'FileStorage.Event');
App::uses('CakeEventManager', 'Event');
App::uses('ClassRegistry', 'Utility');

// Attach the S3 Listener to the global CakeEventManager
$listener = new S3StorageListener();
CakeEventManager::instance()->attach($listener);

// Attach the Image Processing Listener to the global CakeEventManager
$listener = new ImageProcessingListener();
CakeEventManager::instance()->attach($listener);

Configure::write('Media', array(
	// Configure the `basePath` for the Local adapter, not needed when not using it
	'basePath' => APP . 'FileStorage' . DS,
	// Configure image versions on a per model base
	'imageSizes' => array(
		'ProductImage' => array(
			'large' => array(
				'thumbnail' => array(
					'mode' => 'inbound',
					'width' => 800,
					'height' => 800)),
			'medium' => array(
				'thumbnail' => array(
					'mode' => 'inbound',
					'width' => 200,
					'height' => 200
				)
			),
			'small' => array(
				'thumbnail' => array(
					'mode' => 'inbound',
					'width' => 80,
					'height' => 80
				)
			)
		)
	)
));

// This is very important! The hashes are needed to calculate the image versions!
ClassRegistry::init('FileStorage.ImageStorage')->generateHashes();

// Optional, lets use the AwsS3 adapter here instead of local here
$S3Client = \Aws\S3\S3Client::factory(array(
	'key' => 'YOUR-KEY',
	'secret' => 'YOUR-SECRET'
));

// Configure the Gaufrette adapter through the StorageManager
StorageManager::config('S3Image', array(
	'adapterOptions' => array(
		$S3Client,
		'YOUR-BUCKET-NAME',
		array(),
		true
	),
	'adapterClass' => '\Gaufrette\Adapter\AwsS3',
	'class' => '\Gaufrette\FileSystem')
);
```

app/Config/bootstrap.php
------------------------

Now include the file_storage.php setup in your ```app/Config/bootstrap.php```

```php
include('file_storage.php');
```

Theoretical model setup
-----------------------

```php
class Product extends AppModel {
	public $hasMany = array(
		'Image' => array(
			'className' => 'ProductImage',
		),
		'Document' => array(
			'className' => 'FileStorage.FileStorage',
		),
	);
}
```

```php
App::uses('ImageStorage', 'FileStorage.Model');
class ProductImage extends ImageStorage {
	public $actsAs = array(
		'FileStorage.UploadValidator' => array(
			'allowedExtensions' => array(
				'jpg',
				'png'
			),
		),
	);
	public function upload($productId, $data) {
		$data[$this->alias]['adapter'] = 'Local';
		$data[$this->alias]['model'] = $this->name;
		$data[$this->alias]['foreign_key'] = $productId;
		$this->create();
		return $this->save($data);
	}
}
```

Products Controller
-------------------

```php
class ProductsController extends AppModel {
	public function upload($productId = null) {
		if (!$this->request->is('get')) {
			if ($this->Product->Image->upload($productId, $this->request->data)) {
				$this->Session->set(__('Upload successful!');
			}
		}
	}
}
```

Products View
-------------

```php
echo $this->Form->create('ProductImage', array(
	'type' => 'file'
));
echo $this->Form->file('file');
echo $this->Form->error('file');
echo $this->Form->submit(__('Upload'));
echo $this->Form->end();
```