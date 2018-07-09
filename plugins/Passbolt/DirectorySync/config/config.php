<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
                'version' => '1.0.0',
                'backend' => 'ldap',
                'jobs' => [
                    'users' => [
                        'create' => true,
                        'update' => true,
                        'delete' => true,
                    ],
                    'groups' => [
                        'create' => true,
                        'update' => true,
                        'delete' => true,
                    ],
                ]
            ]
        ]
    ]
];
