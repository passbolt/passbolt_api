<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

/**
 * This is the default configuration file to synchronize passbolt with your ldap server.
 * To activate LDAP sync, copy / paste this file into ldap.php and
 * modify the configuration options to match your config.
 *
 * DEPRECATED CONFIG: schema_format, schema_folder, cache_type, cache_options, cache_folder,
 * cache_auto_refresh, idle_reconnect, ldap_options, connect_timeout
 *
 * For more information: https://www.passbolt.com/configure/ldap
 */
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
               //  'usersParentGroup' => 'accounting',

                // Will list only groups that are part of the given parent group (recursively).
                // 'groupsParentGroup' => 'accounting',

                // Will return enabled users only. (only available in case of active directory).
                'enabledUsersOnly' => false,

                // Define whether the email should be built from a prefix / suffix (to activate only if the email is not provided by default by the directory).
                'useEmailPrefixSuffix' => false,

                // Email prefix. Enter the directory attribute that should be used as a prefix. (final email will be concat(prefix, suffix)
                //'emailPrefix' => 'fieldName',

                // Email suffix. It should be the domain name of your organization.
                //'emailSuffix' => '@domain.com',

                // 'fieldsMapping' => [
                //      // Override the mapping here.
                //      // Needed mainly if using openldap.
                //      // Keep commented or empty if default rules work fine.
                // ],

                // Group Object Class. Only used if the server type is openldap. Default: groupOfUniqueNames
                 'groupObjectClass' => 'groupOfNames',

                // User Object Class. Only used if the server type is openldap. Default: inetOrgPerson
                // 'userObjectClass' => 'inetOrgPerson',

                // Group path is used in addition to base_dn while searching groups.
                // 'groupPath' => 'ou=Groups2',

                // User path is used in addition to base_dn while searching users.
                 //'userPath' => 'ou=Users2',

                // User custom filters. Build advanced query filters to retrieve users using complex LDAP search filters.
                // Previously: Received a callback
                // Ex: (|(surname=Smith)(surname=Doe))
                //'userCustomFilters' => '',

                // Group custom filters. Build advanced query filters to retrieve groups.
                // Previously: Received a callback
                // Ex: (cn=accounting)
                //'groupCustomFilters' => '(|(cn=acc1)(cn=acc2))',

                // Optional: disable one or more sync tasks
                // 'jobs' => [
                //    'users' => [
                //        'create' => true,
                //        'delete' => true,
                //     ],
                //    'groups' => [
                //        'create' => true,
                //        // update is used for adding users as group members.
                //        'update' => true,
                //        'delete' => true,
                //     ],
                //],
                'ldap' => [
                    // Optional: When using the LdapManager and there are multiple domains configured,
                    // the following domain will be selected first by default for any operations.
                    //'default_domain' => 'example.com',
                    'domains' => [
                        'ldap.local' => [
                            'ldap_type' => 'openldap',
                            'domain_name' => 'ldap.local',
                            'username' => 'admin',
                            'password' => 'admin',
                            'base_dn' => 'dc=example,dc=org',
                            'servers' => ['openldap'],
                            'use_tls' => false,

                            'lazy_bind' => false,
                            'server_selection' => 'order',
                            'bind_format' => 'cn=%username%,dc=example,dc=org',
//                            'idle_reconnect' => 600,
//                            'ldap_options' => [
//                                'ldap_opt_protocol_version' => 3,
//                                'ldap_opt_referrals' => 0,
//                            ],

                            'port' => 389,
                        ]
                    ],
                ]
            ]
        ]
    ]
];
