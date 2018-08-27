<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
                'test' => false,
                'fieldsMapping' => [
                    // Override the mapping here.
                    // Needed mainly if using openldap.
                    // Keep empty if default rules work fine.
                ],
                // only used if the server type is openldap.
                'groupObjectClass' => 'posixGroup',
                'userObjectClass' => 'inetOrgPerson',

                // The admin user that will perform operations for the directory.
                'defaultUser' => 'admin@passbolt.com',
                // The default admin is the group manager that will be assigned to a newly created group.
                'defaultGroupAdminUser' => 'ada@passbolt.com',
                'ldap' => [
                    'domains' => [
                        // Active directory configuration.
//                       'passbolt.local' => [
//                            'domain_name' => 'passbolt.local',
//                            'username' => 'remy',
//                            'password' => ';faso19347rlbfidsu!#$@#%{PO',
//                            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
//                            'servers' => ['35.205.131.240'],
////                            'port' => 636,
//                            'use_ssl' => false,
//                           'ldap_type' => 'ad',
//                        ],
                        // OpenLDAP configuration.
                        'passbolt.local' => [
                            'domain_name' => 'passbolt.local',
                            'username' => 'cn=admin,dc=passbolt,dc=local',
                            'password' => '4dm1n',
                            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
                            'servers' => ['openldap'],
                            'port' => 389,
                            'use_ssl' => false,
                            'ldap_type' => 'openldap',
                        ]
                    ]
                ]
            ]
        ]
    ]
];
