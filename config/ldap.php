<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
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
