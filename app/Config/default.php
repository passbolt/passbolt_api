<?php
/**
 * Main application configuration file
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
$config = [
	// General App Details
	'App' => [
		'name' => 'Passbolt',
		'punchline' => 'Open source password manager for teams',
		'title' => '%s | Passbolt', // %s = title_for_layout

		// Do you want search engine robots to index your site
		// Default is set to false
		'meta' => [
			'robots' => [
				'index' => false,
			]
		],
		// Should the app be SSL / HTTPS only
		// false will render your installation insecure
		'ssl' => [
			'force' => true,
		],
		// Is public registration allowed
		'registration' => [
			'public' => true,
		],
		// Activate specific entry points for selenium testing.
		// true will render your installation insecure
		'selenium' => [
			'active' => false,
            'token' => false
		],
		// build | options : development or production.
		// development will load the non compiled version,
		// production will load the compiled passbolt.js file.
		'js' => [
			'build' => 'production'
		]
	],
	// Analytics configuration
	'Analytics' => [
		'piwik' => [
			// Provide this url to activate tracking.
			// 'url' => ''
		],
	],
	// GPG Configuration
	'GPG' => [
		// Tell GPG where to find the keyring
		// Needs to be available by the user the webserver is running as
		'env' => [
			// You can set this to true if you want to customize the location of the keyring.
			// If false, it will use by default the keyring of the webserver user ~/.gnupg.
			'setenv' => true,
			// otherwise you can set the location here
			// typically on Centos it would be in '/usr/share/httpd/.gnupg'
			'home' => '/home/www-data/.gnupg',
		],
		// Main server key
		'serverKey' => [
			// Server private key location and fingerprint
			'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
			'public' => APP . 'Config' . DS . 'gpg' . DS . 'unsecure.key',
			'private' => APP . 'Config' . DS . 'gpg' . DS . 'unsecure_private.key',

			// PHP Gnupg module currently does not support passphrase, please leave blank
			'passphrase' => ''
		],
		// Test keys location
		// Only used for running unit or selenium tests
		'testKeys' => [
			'path' => APP . 'Plugin' . DS . 'DataTests' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'gpg' . DS,
		]
	],
	// Define when logs entry are stored
	// By default we store everything, but you can limit to errors
	// See. Model/ControllerLog::write
	'Log' => [
		'error' => true,
		'success' => true,
		'notice' => true,
		'debug' => true,
		'warning' => true,
		// Log request data on errors.
		// possible values:
		// - 'all' will log request data for all statuses.
		// - true | 'error' will log request data for errors only.
		'request_data' => true
	],
	// Authentication & Authorisation
	// See. http://book.cakephp.org/2.0/en/core-libraries/components/authentication.html
	'Auth' => [
		// The number of minutes the authentication token lives for.
		'tokenExpiracy' => 4320,

		// Cakephp Auth component config
		'logoutRedirect' => [
			'controller' => 'users',
			'action' => 'login',
		],
		'loginRedirect' => [
			'controller' => 'pages',
			'action' => 'display',
			'home',
		],
		// Which Authenticate components we want to enable
		// We will allow more authentication method in the future
		// For now we only support GPG Auth
		'authenticate' => [
			'Gpg'
		],
	],
	// Enable or disable generic model associations
	// For example which models comments is available for
	// usefull in case we want to enable commenting on more objects in the future
	'Permission' => [
		'acoModels' => ['Resource'],
		'aroModels' => ['User', 'Group'],
	],
	'Comment' => [
		'foreignModels' => ['Resource'],
	],
	'Favorite' => [
		'foreignModels' => ['Resource'],
	],
	'ItemTag' => [
		'foreignModels' => ['Resource'],
	],
	// Define which validation rules are exposed by the API
	// See ValidationRuleController.php
	'Validation' => [
		'shared' => [
			'Comment',
			'Group',
			'Profile',
			'Resource',
			'Secret',
			'User',
		],
	],
	// Anonymous statistics configuration.
	'AnonymousStatistics' => [
		// Url where data will be sent.
		'url' => 'https://www.passbolt.com/statistics/install.json',
		// Help url.
		'help' => 'https://www.passbolt.com/privacy#statistics',
		// Whether to send the anonymous statistics or not.
		'send' => false,
		// Instance ID. (will be populated at installation).
		'instanceId' => '',
	],
	// Email notification settings
	'EmailNotification' => [
		// Allow to disable displaying the armored secret in the email
		// WARNING: make sure you have backups in place if you disable these
		// see. https://www.passbolt.com/help/tech/backup
		'show' => [
			'secret' => true,
			'username' => true
		],
		// Choose which emails are sent system wide
		'send' => [
			'password' => [
				'share' => true,
				'comment' => true,
				'create' => true,
				'update' => true,
				'delete' => true,
			],
			'user' => [
				// WARNING: disabling these will prevent user from signing up
				'create' => true,
				'recover' => true,
			],
			'group' => [
				'user' => [
					'add' => true,
					'delete' => true,
					'update' => true,
				],
				'manager' => [
					// notify manager when group user is updated / deleted
					'update' => true,
					'delete' => true,
				]
			]
		]
	]
];
