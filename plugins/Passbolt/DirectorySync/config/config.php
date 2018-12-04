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
                        'delete' => true,
                    ],
                    'groups' => [
                        'create' => true,
                        'update' => true,
                        'delete' => true,
                    ],
                ],
                'fieldsMapping' => [
                    'ad' => [
                        'user' => [
                            'id' => 'guid',
                            'firstname' => 'firstName',
                            'lastname' => 'lastName',
                            'username' => 'emailAddress',
                            'created' => 'created',
                            'modified' => 'modified',
                            'groups' => 'groups',
                            'enabled' => 'enabled',
                        ],
                        'group' => [
                            'id' => 'guid',
                            'name' => 'name',
                            'created' => 'created',
                            'modified' => 'modified',
                            'users' => 'members'
                        ],
                    ],
                    'openldap' => [
                        'user' => [
                            'id' => 'entryUUID',
                            'firstname' => 'firstName',
                            'lastname' => 'lastName',
                            'username' => 'mail',
                            'created' => 'created',
                            'modified' => 'modified',
                        ],
                        'group' => [
                            'id' => 'entryUUID',
                            'name' => 'cn',
                            'created' => 'created',
                            'modified' => 'modified',
                            'users' => 'members',
                        ],
                    ],
                ],
            ]
        ]
    ]
];
