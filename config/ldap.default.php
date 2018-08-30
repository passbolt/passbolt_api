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
 * For more information: https://www.passbolt.com/configure/ldap
 */
return [
    'passbolt' => [
        'plugins' => [
            'directorySync' => [
                // The admin user that will perform operations for the directory.
                'defaultUser' => 'email@domain.com',

                // The default admin is the group manager that will be assigned to a newly created group.
                // If not specified, the first admin user found will be used.
                'defaultGroupAdminUser' => 'email@domain.com',

                // 'fieldsMapping' => [
                //      // Override the mapping here.
                //      // Needed mainly if using openldap.
                //      // Keep commented or empty if default rules work fine.
                // ],
                //
                // Group Object Class. Only used if the server type is openldap.
                // 'groupObjectClass' => 'posixGroup',
                //
                // User Object Class. Only used if the server type is openldap.
                // 'userObjectClass' => 'inetOrgPerson',
                //
                // Group path is used in addition to base_dn while searching groups.
                // 'groupPath' => '',
                //
                // User path is used in addition to base_dn while searching users.
                // 'userPath' => '',
                //
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
                    //'general' => [
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
                            'servers' => ['example1'],

                            // Optional: Whether or not to talk to LDAP over TLS. Default: false
                            // If this is set to false, certain operations will not work. Such as password changes.
                            'use_tls' => false,

                            // Optional: Whether or not to talk to LDAP over SSL. Default: false
                            //'use_ssl' => false,

                            // Optional: The port to communicate to the LDAP servers on. If not set, default is 389
                            // If this is not set and 'use_ssl' is specified, the the port will be set to 636.
                            'port' => 389,

                            // Optional: Whether or not paging should be used for query operations. Default: true
                            //'use_paging' => true,

                            // Optional: The page size to use for paging operations, such as searches. Default: 1000
                            //'page_size' => 1000,

                            // Optional: The LDAP type for this domain: ad, openldap. Default: ad
                            //'ldap_type' => 'openldap',

                            // Optional: Whether the connection should wait to bind until necessary (true) or bind
                            // immediately on construction (false). Default: false
                            //'lazy_bind' => false,

                            // Optional: When more than one server is listed for a domain, choose which one is
                            // selected for the connection. The possible choices are: order (tried in the order they
                            // appear), random. Default: order
                            //'server_selection' => 'order',

                            // Optional: The encoding type to use. Default: UTF-8
                            //'encoding' => 'UTF-8',

                            // Optional: The format that the username should be in when binding. This allows for
                            // two possible placeholders: %username% and %domainname%. The domain name parameter
                            // is the FQDN. Default: For AD the default is "%username%@%domainname%", for OpenLDAP it
                            // is simply "%username%". But you could easily make it something
                            // like "CN=%username%,OU=Users,DC=example,DC=local".
                            //'bind_format' => '%username%',

                            // Optional: The LDAP_OPT_* constants to use when connecting to LDAP.
                            // Default: Sets the protocol version to 3 and disables referrals.
                            //'ldap_options' => [
                            //    'ldap_opt_protocol_version' => 3,
                            //    'ldap_opt_referrals' => 0,
                            //],

                            // Optional: The elapsed time a connection can be idle before it is closed and reconnected.
                            // Default: 600. To disable this altogether set it to 0.
                            //'idle_reconnect' => 600,

                            // Optional: The elapsed time (in seconds) to attempt the initial connection to the LDAP server.
                            // If it cannot establish a connection within this time it will consider the server
                            // unreachable/down. Default: 1
                            //'connect_timeout' => 5,
                        ]
                    ],
                ]
            ]
        ]
    ]
];
