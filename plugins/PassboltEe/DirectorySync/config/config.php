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
                ],
                'fieldsMapping' => [
                    'ad' => [
                        'user' => [
                            'id' => 'objectGuid',
                            'firstname' => 'givenName',
                            'lastname' => 'sn',
                            'username' => 'mail',
                            'created' => 'whenCreated',
                            'modified' => 'whenChanged',
                            'groups' => 'memberOf',
                            'enabled' => 'userAccountControl',
                        ],
                        'group' => [
                            'id' => 'objectGuid',
                            'name' => 'cn',
                            'created' => 'whenCreated',
                            'modified' => 'whenChanged',
                            'users' => 'member',
                        ],
                    ],
                    'openldap' => [
                        'user' => [
                            'id' => 'entryUuid',
                            'firstname' => 'givenname',
                            'lastname' => 'sn',
                            'username' => 'mail',
                            'created' => 'createtimestamp',
                            'modified' => 'modifytimestamp',
                        ],
                        'group' => [
                            'id' => 'entryUuid',
                            'name' => 'cn',
                            'created' => 'createtimestamp',
                            'modified' => 'modifytimestamp',
                            'users' => 'member',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
