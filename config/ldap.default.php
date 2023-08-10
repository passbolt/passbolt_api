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
                //
                //'enabled' => true
                // The admin user that will perform operations for the directory.
                'defaultUser' => 'email@domain.com',

                // The default admin is the group manager that will be assigned to a newly created group.
                // If not specified, the first admin user found will be used.
                'defaultGroupAdminUser' => 'email@domain.com',

                // Will list only users that are part of the given parent group (recursively).
                // 'usersParentGroup' => 'groupName',

                // Will list only groups that are part of the given parent group (recursively).
                // 'groupsParentGroup' => 'groupName',

                // Will return enabled users only. (only available in case of active directory).
                'enabledUsersOnly' => false,

                // Define whether the email should be built from a prefix / suffix (to activate only if the email is not provided by default by the directory).
                'useEmailPrefixSuffix' => false,

                // Email prefix. Enter the directory attribute that should be used as a prefix. (final email will be concat(prefix, suffix)
                //'emailPrefix' => 'fieldName',

                // Email suffix. It should be the domain name of your organization (including @).
                //'emailSuffix' => '@domain.com',

                // 'fieldsMapping' => [
                //      // Override the mapping here.
                //      // Needed mainly if using openldap.
                //      // Keep commented or empty if default rules work fine.
                // ],

                // Group Object Class. Only used if the server type is openldap. Default: groupOfUniqueNames
                // 'groupObjectClass' => 'groupOfUniqueNames',

                // User Object Class. Only used if the server type is openldap. Default: inetOrgPerson
                // 'userObjectClass' => 'inetOrgPerson',

                // Group path is used in addition to base_dn while searching groups.
                // 'groupPath' => '',

                // User path is used in addition to base_dn while searching users.
                // 'userPath' => '',

                // User custom filters. Build advanced query filters to retrieve users using complex LDAP search filters.
                // Previously: Received a callback
                // Ex: (|(surname=Smith)(surname=Doe))
                //'userCustomFilters' => '',

                // Group custom filters. Build advanced query filters to retrieve groups.
                // Previously: Received a callback
                // Ex: (cn=accounting)
                //'groupCustomFilters' => '',

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
                        // At least one domain is required.
                        'example' => [
                            // Required: The full domain name.
                            'domain_name' => 'example.com',

                            // Required: The user to use for binding to LDAP and subsequent operations for the connection.
                            'username' => 'user',

                            // Required: The password for the user binding to LDAP.
                            'password' => '12345',

                            // Recommended: The base DN (default naming context) for the domain.
                            // If this is empty then it will be queried from the RootDSE.
                            'base_dn' => 'dc=example,dc=com',

                            // Recommended: One or more LDAP servers to connect to.
                            // If this is empty then it will query DNS for a list of LDAP servers for the domain.
                            // Previously: servers (DEPRECATED)
                            'hosts' => ['example1'],

                            // Optional: Whether or not to talk to LDAP over TLS. Default: false
                            // If this is set to false, certain operations will not work. Such as password changes.
                            'use_tls' => false,

                            // Optional: Whether or not to talk to LDAP over SSL. Default: false
                            //'use_ssl' => false,

                            // Optional: Whether or not to use SASL to connect to LDAP. Default: false
                            //'use_sasl' => false,

                            // Optional: SASL Options to be passed to Connection
                            // SASL options:
                            //  - mech: Mechanism (Defaults: GSSAPI)
                            //  - realm: Realm (Defaults: null)
                            //  - authc_id: Verification Identity (Defaults: null)
                            //  - authz_id: Authorization Identity (Defaults: null)
                            //  - props: Options for Authorization Identity (Defaults: null)
                            //'sasl_options' => [
                            //    'mech' => null,
                            //    'realm' => null,
                            //    'authc_id' => null,
                            //    'authz_id' => null,
                            //    'props' => null
                            //],

                            // Optional: The port to communicate to the LDAP servers on. If not set, default is 389
                            // If this is not set and 'use_ssl' is specified, the the port will be set to 636.
                            'port' => 389,

                            // Optional: The LDAP type for this domain: ad, openldap. Default: ad
                            //'ldap_type' => 'openldap',

                            // Optional: Whether the connection should wait to bind until necessary (true) or bind
                            // immediately on construction (false). Default: false
                            //'lazy_bind' => false,

                            // Optional: When more than one server is listed for a domain, choose which one is
                            // selected for the connection. The possible choices are: order (tried in the order they
                            // appear), random. Default: order
                            //'server_selection' => 'order',

                            // Optional: The format that the username should be in when binding. This allows for
                            // two possible placeholders: %username% and %domainname%. The domain name parameter
                            // is the FQDN. Default: For AD the default is "%username%@%domainname%", for OpenLDAP it
                            // is simply "%username%". But you could easily make it something
                            // like "CN=%username%,OU=Users,DC=example,DC=local".
                            //'bind_format' => '%username%',

                            // Optional: The LDAP_OPT_* constants to use when connecting to LDAP.
                            // Default: No custom options
                            // Previously: ldap_options (REMOVED)
                            // 'options' => [
                            //     LDAP_OPT_RESTART => 1,
                            //     LDAP_OPT_REFERRALS => 0,
                            // ],

                            // Optional: The elapsed time (in seconds) to attempt the initial connection to the LDAP server.
                            // If it cannot establish a connection within this time it will consider the server
                            // unreachable/down.
                            // Default: 5.
                            // Previously: connect_timeout (DEPRECATED)
                            //'timeout' => 5,
                        ]
                    ],
                ]
            ]
        ]
    ]
];
