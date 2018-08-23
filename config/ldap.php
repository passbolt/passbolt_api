<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
               'test' => false,

                'fieldsMappingDefaults' => [
                    'ad' => [
                        'user' => [
                            'id' => 'guid',
                            'firstname' => 'firstName',
                            'lastname' => 'lastName',
                            'username' => 'emailAddress',
                            'created' => 'created',
                            'modified' => 'modified',
                            'groups' => 'groups',
                        ],
                        'group' => [
                            'id' => 'guid',
                            'name' => 'name',
                            'created' => 'created',
                            'modified' => 'modified',
                            'users' => 'members'
                        ]

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
                        ]
                    ],
                ],
                'fieldsMapping' => [
                    // Override here
                ],
//                'groupObjectClass' => 'posixGroup',
//                'userObjectClass' => null,

                // The admin user that will perform operations for the directory.
                'defaultUser' => 'admin@passbolt.com',
                // The default admin is the group manager that will be assigned to a newly created group.
                'defaultGroupAdminUser' => 'ada@passbolt.com',
                'ldap' => [
                    'domains' => [
                        // Active directory configuration.
                       'passbolt.local' => [
                            'domain_name' => 'passbolt.local',
                            'username' => 'remy',
                            'password' => ';faso19347rlbfidsu!#$@#%{PO',
                            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                            'servers' => ['35.205.131.240'],
//                            'port' => 636,
                            'use_ssl' => false,
                           'ldap_type' => 'ad',
                        ],
                        // OpenLDAP configuration.
//                        'passbolt.local' => [
//                            'domain_name' => 'passbolt.local',
//                            'username' => 'cn=admin,dc=passbolt,dc=local',
//                            'password' => '4dm1n',
//                            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
//                            'servers' => ['openldap'],
//                            'port' => 389,
//                            'use_ssl' => false,
//                            'ldap_type' => 'openldap',
//                        ]
                    ]
                ]
            ]
        ]
    ]
];
