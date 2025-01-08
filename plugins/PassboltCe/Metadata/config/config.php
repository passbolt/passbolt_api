<?php
return [
    'passbolt' => [
        'plugins' => [
            'metadata' => [
                'version' => '1.0.0',
                'defaultPaginationLimit' => filter_var(env('PASSBOLT_PLUGINS_METADATA_DEFAULT_PAGINATION_LIMIT', '20'), FILTER_VALIDATE_INT), // phpcs:ignore
            ],
        ],
    ],
];
