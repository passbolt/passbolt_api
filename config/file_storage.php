<?php

use App\Model\Table\AvatarsTable;
use Cake\Core\Configure;

// File storage and images
Configure::write('ImageStorage.adapter', 'Local');
Configure::write('ImageStorage.basePath', WWW_ROOT . 'img' . DS . 'public' . DS);
Configure::write('ImageStorage.publicPath', 'img' . DS . 'public' . DS);
Configure::write('FileStorage', [
    // Configure the `basePath` for the Local adapter, not needed when not using it
    'basePath' => APP . 'FileStorage' . DS,
    'imageDefaults' => [
        'Avatar' => [
            AvatarsTable::FORMAT_MEDIUM =>  'img' . DS . 'avatar' . DS . 'user_medium.png',
            AvatarsTable::FORMAT_SMALL =>  'img' . DS . 'avatar' . DS . 'user.png',
        ]
    ],
    // Configure image versions on a per model base
    'imageSizes' => [
        'Avatar' => [
            AvatarsTable::FORMAT_MEDIUM => [
                'thumbnail' => [
                    'mode' => 'outbound',
                    'width' => 200,
                    'height' => 200
                ],
            ],
            AvatarsTable::FORMAT_SMALL => [
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
]);
