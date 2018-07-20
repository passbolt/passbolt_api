<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
//                'test' => 'Default',

                // The user that will perform operations for the directory.
                'defaultUser' => 'admin@passbolt.com',
                // The default admin is the group manager that will be assigned to a newly created group.
                'defaultGroupAdminUser' => 'ada@passbolt.com',
                'ldap' => [
                    'domains' => [
                        'passbolt.local' => [
                            'domain_name' => 'passbolt.local',
                            'username' => 'remy',
                            'password' => ';faso19347rlbfidsu!#$@#%{PO',
                            'base_dn' => 'dc=passbolt,dc=local',
                            'servers' => ['35.205.131.240'],
//                            'port' => 636,
//                            'use_ssl' => true,
                        ]
                    ]
                ]
            ]
        ]
    ]
];
