<?php

return [
    'passbolt' => [
        'plugins' => [
            'passwordGenerator' => [
                'version' => '3.3.0',
                'enabled' => env('PASSBOLT_PLUGINS_PASSWORD_GENERATOR_ENABLED', true),
                'defaultPasswordGenerator' => getenv('PASSBOLT_PLUGINS_PASSWORD_GENERATOR_DEFAULT_GENERATOR'),
            ],
        ],
    ],
];
