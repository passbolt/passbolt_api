<?php
use Cake\Core\Configure;

// File storage and images
Configure::write('ImageStorage.basePath', WWW_ROOT . 'img' . DS . 'public' . DS . 'images' . DS);
Configure::write('ImageStorage.publicPath', 'img' . DS . 'public' . DS . 'images' . DS);
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