<?php
App::uses('FileStorageUtils', 'FileStorage.Lib/Utility');
App::uses('StorageManager', 'FileStorage.Lib');
App::uses('LocalImageProcessingListener', 'FileStorage.Event');
//App::uses('LocalFileStorageListener', 'FileStorage.Event');
App::uses('CakeEventManager', 'Event');

spl_autoload_register(__NAMESPACE__ .'\FileStorageUtils::gaufretteLoader');

$listener = new LocalImageProcessingListener();
CakeEventManager::instance()->attach($listener);
