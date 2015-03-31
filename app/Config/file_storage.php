<?php
App::uses('ClassRegistry', 'Utility');
App::uses('CakeEventManager', 'Event');
App::uses('FileStorageUtils', 'FileStorage.Lib/Utility');
App::uses('StorageManager', 'FileStorage.Lib');
App::uses('LocalImageProcessingListener', 'FileStorage.Event');
App::uses('LocalFileStorageListener', 'FileStorage.Event');
App::uses('ImageProcessingListener', 'FileStorage.Event');
App::uses('FileStorageListener', 'FileStorage.Event');

// Image storage paths.
Configure::write('ImageStorage.basePath', ROOT . DS . 'app' . DS . 'webroot' . DS . 'img' . DS . 'public');
Configure::write('ImageStorage.publicPath', 'img' . DS . 'public');

// Image versions configuration.
Configure::write('Media', array(
	// Configure the `basePath` for the Local adapter, not needed when not using it
	'basePath' => APP . 'FileStorage' . DS,
	'imageDefaults' => array(
		'ProfileAvatar' => array(
			'small'     => IMAGES_URL . 'placeholder' . DS . 'user_small.png',
			'smallest'  => IMAGES_URL . 'placeholder' . DS . 'user_smallest.png',
		)
	),
	// Configure image versions on a per model base
	'imageSizes' => array(
		'ProfileAvatar' => array(
			'large' => array(
				'thumbnail' => array(
					'mode' => 'outbound',
					'width' => 800,
					'height' => 800
				)
			),
			'medium' => array(
				'thumbnail' => array(
					'mode' => 'outbound',
					'width' => 200,
					'height' => 200
				)
			),
			'small' => array(
				'thumbnail' => array(
					'mode' => 'outbound',
					'width' => 50,
					'height' => 50
				),
				'crop' => array(
					'width' => 50,
					'height' => 50
				)
			),
			'smallest' => array(
				'thumbnail' => array(
					'mode' => 'outbound',
					'width' => 30,
					'height' => 30
				),
				'crop' => array(
					'width' => 30,
					'height' => 30
				)
			)
		)
	)
));


$listener = new LocalFileStorageListener();
CakeEventManager::instance()->attach($listener);

// For automated image processing you'll have to attach this listener as well
$listener = new ImageProcessingListener();
CakeEventManager::instance()->attach($listener);

// This is very important! The hashes are needed to calculate the image versions!
ClassRegistry::init('FileStorage.ImageStorage')->generateHashes();

StorageManager::config('Local', array(
	'adapterOptions' => array(Configure::read('ImageStorage.basePath'), true),
	'adapterClass' => '\Gaufrette\Adapter\Local',
	'class' => '\Gaufrette\Filesystem'
));
