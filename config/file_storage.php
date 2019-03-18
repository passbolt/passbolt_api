<?php
use Cake\Core\Configure;
use Burzum\FileStorage\Storage\StorageUtils;
use Burzum\FileStorage\Storage\StorageManager;

// File storage and images
Configure::write('ImageStorage.adapter', 'Local');
Configure::write('ImageStorage.basePath', WWW_ROOT . 'img' . DS . 'public' . DS);
Configure::write('ImageStorage.publicPath', 'img' . DS . 'public' . DS);
Configure::write('FileStorage', array(
    // Configure the `basePath` for the Local adapter, not needed when not using it
    'basePath' => APP . 'FileStorage' . DS,
    'imageDefaults' => [
        'Avatar' => [
            'medium' =>  'img' . DS . 'avatar' . DS . 'user_medium.png',
            'small' =>  'img' . DS . 'avatar' . DS . 'user.png',
        ]
    ],
    // Configure image versions on a per model base
    'imageSizes' => [
        'Avatar' => [
            'medium' => [
                'thumbnail' => [
                    'mode' => 'outbound',
                    'width' => 200,
                    'height' => 200
                ],
            ],
            'small' => [
                'thumbnail' => [
                    'mode' => 'outbound',
                    'width' => 80,
                    'height' => 80
                ],
                'crop' => [
                    'width' => 80,
                    'height' => 80
                ],
            ],
        ]
    ]
));

StorageUtils::generateHashes();
StorageManager::config(Configure::read('ImageStorage.adapter'), [
    'adapterOptions' => [Configure::read('ImageStorage.basePath'), true],
    'adapterClass' => '\Gaufrette\Adapter\Local',
    'class' => '\Gaufrette\Filesystem'
]);