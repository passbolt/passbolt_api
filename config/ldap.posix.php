<?php
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
                'enabled' => true,
                // The admin user that will perform operations for the directory.
                'defaultUser' => 'admin@passbolt.com',

                // The default admin is the group manager that will be assigned to a newly created group.
                // If not specified, the first admin user found will be used.
                'defaultGroupAdminUser' => 'admin@passbolt.com',

                // Will list only users that are part of the given parent group (recursively).
                // 'usersParentGroup' => 'groupName',

                // Will list only groups that are part of the given parent group (recursively).
                // 'groupsParentGroup' => 'groupName',

                // Will return enabled users only. (only available in case of active directory).
                'enabledUsersOnly' => false,

                'groupObjectClass' => 'posixGroup',
                'userObjectClass' => 'posixAccount',

                // Define whether the email should be built from a prefix / suffix (to activate only if the email is not provided by default by the directory).
                'useEmailPrefixSuffix' => true,
                'emailPrefix' => 'uid',
                'emailSuffix' => '@passbolt.local',
//
                'fieldsMapping' => [
                    'openldap' => [
                        'user' => [
                            'lastname' => 'description',
                        ],
                    ],
                ],
                'ldap' => [
                    // Optional: When using the LdapManager and there are multiple domains configured,
                    // the following domain will be selected first by default for any operations.
                    //'default_domain' => 'example.com',

                    // Optional: The format that the schema is in. Default: yml
                    //'schema_format' => 'yml',

                    // Optional: The location to use when loading schema files.
                    //'schema_folder' => '/var/www/project/resources/schema',

                    // The cache type to use. Either 'stash', 'doctrine', or 'none'. Default: none
                    //'cache_type' => 'none',

                    // Optional: These are variable settings for the cache type in use.
                    //'cache_options' => [
                    // Type: stash, doctrine
                    // Optional: The location to cache generated schema data. Default: The systems temporary directory.
                    //'cache_folder' => '/tmp/projectCache',
                    // Type: stash
                    // Optional: Whether the cache should auto-refresh based on mod times.
                    // This is enabled by default with stash. However, the doctrine type does not support it.
                    //'cache_auto_refresh' => false,
                    //],
                    //],
                    'domains' => [
                        // At least one domain is required.
//                        'passbolt.local' => [
//                            'domain_name' => 'passbolt.local',
//                            'username' => 'max',
//                            'password' => 'Z%*mk+c*ra${-MX',
//                            'servers' => ['104.155.67.148'],
//                            'use_tls' => false,
//                            'port' => 389,
//                            'base_dn' => 'OU=PassboltUsers,DC=passbolt,DC=local',
//                            'ldap_type' => 'ad',
//                        ],
                        'ldap.local' => [
                            'domain_name' => 'ldap.local',
                            'username' => 'admin',
                            'password' => 'admin',
                            'base_dn' => 'dc=example,dc=org',
                            'servers' => ['openldap', 'openldap2'],
                            'use_tls' => false,

                            'lazy_bind' => false,
                            'server_selection' => 'order',
                            'bind_format' => 'cn=%username%,dc=example,dc=org',
                            'idle_reconnect' => 600,
                            'ldap_options' => [
                                'ldap_opt_protocol_version' => 3,
                                'ldap_opt_referrals' => 0,
                            ],

                            'port' => 389,
                            'ldap_type' => 'openldap',
                        ]
                    ],
                ]
            ]
        ]
    ]
];
