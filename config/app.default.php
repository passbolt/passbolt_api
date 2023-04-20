<?php
use Cake\Cache\Engine\FileEngine;
use Cake\Database\Connection;
use Cake\Database\Driver\Mysql;
use Cake\Error\ExceptionRenderer;
use Cake\Log\Engine\FileLog;

return [
    /**
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', false), FILTER_VALIDATE_BOOLEAN),
    'debugKit' => false,

    /**
     * Configure basic information about the application.
     *
     * - namespace - The namespace to find app classes under.
     * - defaultLocale - The default locale for translation, formatting currencies and numbers, date and time.
     * - encoding - The encoding used for HTML + database connections.
     * - base - The base directory the app resides in. If false this
     *   will be auto detected.
     * - dir - Name of app directory.
     * - webroot - The webroot directory.
     * - wwwRoot - The file path to webroot.
     * - baseUrl - To configure CakePHP to *not* use mod_rewrite and to
     *   use CakePHP pretty URLs, remove these .htaccess
     *   files:
     *      /.htaccess
     *      /webroot/.htaccess
     *   And uncomment the baseUrl key below.
     * - fullBaseUrl - A base URL to use for absolute links.
     * - imageBaseUrl - Web path to the public images directory under webroot.
     * - cssBaseUrl - Web path to the public css directory under webroot.
     * - jsBaseUrl - Web path to the public js directory under webroot.
     * - paths - Configure paths for non class based resources. Supports the
     *   `plugins`, `templates`, `locales` subkeys, which allow the definition of
     *   paths for plugins, view templates and locale files respectively.
     */
    'App' => [
        'namespace' => 'App',
        'encoding' => env('APP_ENCODING', 'UTF-8'),
        'defaultLocale' => 'en_UK',
        'base' => env('APP_BASE', false),
        'dir' => 'src',
        'webroot' => 'webroot',
        'wwwRoot' => WWW_ROOT,
        // 'baseUrl' => env('APP_BASE_URL'),
        'fullBaseUrl' => env('APP_FULL_BASE_URL', false),
        'imageBaseUrl' => 'img/',
        'cssBaseUrl' => 'css/',
        'jsBaseUrl' => 'js/',
        //
        // Customization of paths is not supported in passbolt v3
        // Default is overridden directly in bootstrap.php
        //
        //'paths' => [
        //    'plugins' => [ROOT . DS . 'plugins' . DS],
        //    'templates' => [ROOT . DS . 'templates' . DS],
        //    'locales' => [RESOURCES . 'locales' . DS],
        //],
    ],

    /**
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '__SALT__'),
    ],

    /**
     * Apply timestamps with the last modified time to static assets (js, css, images).
     * Will append a querystring parameter containing the time the file was modified.
     * This is useful for busting browser caches.
     *
     * Set to true to apply timestamps when debug is true. Set to 'force' to always
     * enable timestamping regardless of debug value.
     */
    'Asset' => [
        // 'timestamp' => true,
        // 'cacheTime' => '+1 year'
    ],

    /**
     * Configure the cache adapters.
     */
    'Cache' => [
        'default' => [
            'className' => FileEngine::class,
            'path' => CACHE,
            'url' => env('CACHE_DEFAULT_URL', null),

            // Allow using custom cache engine
            'host' => env('CACHE_DEFAULT_HOST', null),
            'port' => env('CACHE_DEFAULT_PORT', null),
            'password' => env('CACHE_DEFAULT_PASSWORD', null),
            'database' => env('CACHE_DEFAULT_DATABASE', null),
        ],

        /**
         * Configure the cache used for general framework caching.
         * Translation cache files are stored with this configuration.
         * Duration will be set to '+2 minutes' in bootstrap.php when debug = true
         * If you set 'className' => 'Null' core cache will be disabled.
         */
        '_cake_core_' => [
            'className' => env('CACHE_CAKECORE_CLASSNAME', FileEngine::class),
            'prefix' => 'myapp_cake_core_',
            'path' => CACHE . 'persistent' . DS,
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKECORE_URL', null),

            // Allow using custom cache engine
            'host' => env('CACHE_CAKECORE_HOST', null),
            'port' => env('CACHE_CAKECORE_PORT', null),
            'password' => env('CACHE_CAKECORE_PASSWORD', null),
            'database' => env('CACHE_CAKECORE_DATABASE', null),
            'fallback' => env('CACHE_CAKECORE_FALLBACK', 'default'),
        ],

        /**
         * Configure the cache for model and datasource caches. This cache
         * configuration is used to store schema descriptions, and table listings
         * in connections.
         * Duration will be set to '+2 minutes' in bootstrap.php when debug = true
         */
        '_cake_model_' => [
            'className' => FileEngine::class,
            'prefix' => 'myapp_cake_model_',
            'path' => CACHE . 'models' . DS,
            'serialize' => true,
            'duration' => '+1 years',
            'url' => env('CACHE_CAKEMODEL_URL', null),

            // Allow using custom cache engine
            'host' => env('CACHE_CAKEMODEL_HOST', null),
            'port' => env('CACHE_CAKEMODEL_PORT', null),
            'password' => env('CACHE_CAKEMODEL_PASSWORD', null),
            'database' => env('CACHE_CAKEMODEL_DATABASE', null),
            'fallback' => env('CACHE_CAKEMODEL_FALLBACK', 'default'),
        ],
    ],

    /**
     * Configure the Error and Exception handlers used by your application.
     *
     * By default errors are displayed using Debugger, when debug is true and logged
     * by Cake\Log\Log when debug is false.
     *
     * In CLI environments exceptions will be printed to stderr with a backtrace.
     * In web environments an HTML page will be displayed for the exception.
     * With debug true, framework errors like Missing Controller will be displayed.
     * When debug is false, framework errors will be coerced into generic HTTP errors.
     *
     * Options:
     *
     * - `errorLevel` - int - The level of errors you are interested in capturing.
     * - `trace` - boolean - Whether or not backtraces should be included in
     *   logged errors/exceptions.
     * - `log` - boolean - Whether or not you want exceptions logged.
     * - `exceptionRenderer` - string - The class responsible for rendering
     *   uncaught exceptions. If you choose a custom class you should place
     *   the file for that class in src/Error. This class needs to implement a
     *   render method.
     * - `skipLog` - array - List of exceptions to skip for logging. Exceptions that
     *   extend one of the listed exceptions will also be skipped for logging.
     *   E.g.:
     *   `'skipLog' => ['Cake\Network\Exception\NotFoundException', 'Cake\Network\Exception\UnauthorizedException']`
     * - `extraFatalErrorMemory` - int - The number of megabytes to increase
     *   the memory limit by when a fatal error is encountered. This allows
     *   breathing room to complete logging or error handling.
     */
    'Error' => [
        'errorLevel' => E_ALL,
        'exceptionRenderer' => ExceptionRenderer::class,
        'skipLog' => [],
        'log' => true,
        'trace' => true,
        'ignoredDeprecationPaths' => [],
    ],

    /**
     * Debugger configuration
     *
     * Define development error values for Cake\Error\Debugger
     *
     * - `editor` Set the editor URL format you want to use.
     *   By default atom, emacs, macvim, phpstorm, sublime, textmate, and vscode are
     *   available. You can add additional editor link formats using
     *   `Debugger::addEditor()` during your application bootstrap.
     * - `outputMask` A mapping of `key` to `replacement` values that
     *   `Debugger` should replace in dumped data and logs generated by `Debugger`.
     */
    'Debugger' => [
        'editor' => 'phpstorm',
    ],

    /**
     * Email configuration.
     *
     * By defining transports separately from delivery profiles you can easily
     * re-use transport configuration across multiple profiles.
     *
     * You can specify multiple configurations for production, development and
     * testing.
     *
     * Each transport needs a `className`. Valid options are as follows:
     *
     *  Mail   - Send using PHP mail function
     *  Smtp   - Send using SMTP
     *  Debug  - Do not send the email, just return the result
     *
     */
    'EmailTransport' => [
        'default' => [
            /*
             * The keys host, port, timeout, username, password, client and tls
             * are used in SMTP transports
             */
            'host' => env('EMAIL_TRANSPORT_DEFAULT_HOST', 'localhost'),
            'port' => env('EMAIL_TRANSPORT_DEFAULT_PORT', 25),
            'timeout' => env('EMAIL_TRANSPORT_DEFAULT_TIMEOUT', 30),
            /*
             * It is recommended to set these options through your environment or passbolt.php
             */
            'username' => env('EMAIL_TRANSPORT_DEFAULT_USERNAME', null),
            'password' => env('EMAIL_TRANSPORT_DEFAULT_PASSWORD', null),
            'client' => env('EMAIL_TRANSPORT_DEFAULT_CLIENT', null),
            'tls' => env('EMAIL_TRANSPORT_DEFAULT_TLS', null),
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),

        ],
        'Debug' => [
            'className' => 'Debug'
        ],
    ],

    /**
     * Email delivery profiles
     *
     * Delivery profiles allow you to predefine various properties about email
     * messages from your application and give the settings a name. This saves
     * duplication across your application and makes maintenance and development
     * easier. Each profile accepts a number of keys. See `Cake\Mailer\Email`
     * for more information.
     */
    'Email' => [
        'default' => [
            'transport' => env('EMAIL_DEFAULT_TRANSPORT', 'default'),
            'from' => [
                env('EMAIL_DEFAULT_FROM', 'you@localhost.test') => env('EMAIL_DEFAULT_FROM_NAME', 'Passbolt')
            ],
            //'charset' => 'utf-8',
            //'headerCharset' => 'utf-8',
        ],
    ],

    /**
     * Connection information used by the ORM to connect
     * to your application's datastores.
     * Do not use periods in database name - it may lead to error.
     * See https://github.com/cakephp/cakephp/issues/6471 for details.
     * Drivers include Mysql Postgres Sqlite Sqlserver
     * See vendor\cakephp\cakephp\src\Database\Driver for complete list
     */
    'Datasources' => [
        'default' => [
            'className' => Connection::class,
            'driver' => env('DATASOURCES_DEFAULT_DRIVER', Mysql::class),
            'persistent' => false,
            'timezone' => 'UTC',

            /*
             * For MariaDB/MySQL the internal default changed from utf8 to utf8mb4, aka full utf-8 support
             */
            'flags' => [],
            'cacheMetadata' => true,
            'log' => env('DATASOURCES_DEFAULT_LOG', false),

            /*
             * Set identifier quoting to true if you are using reserved words or
             * special characters in your table or column names. Enabling this
             * setting will result in queries built using the Query Builder having
             * identifiers quoted when creating SQL. It should be noted that this
             * decreases performance because each query needs to be traversed and
             * manipulated before being executed.
             */
            'quoteIdentifiers' => env('DATASOURCES_QUOTE_IDENTIFIER', true),

            /*
             * During development, if using MySQL < 5.6, uncommenting the
             * following line could boost the speed at which schema metadata is
             * fetched from the database. It can also be set directly with the
             * mysql configuration directive 'innodb_stats_on_metadata = 0'
             * which is the recommended value in production environments
             */
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],

            /*
             * Non default config
             * Passbolt support setting these from environment variables
             * They can also be overridden in passbolt.php
             */
            'host' => env('DATASOURCES_DEFAULT_HOST', 'localhost'),
            'port' => env('DATASOURCES_DEFAULT_PORT', 3306),
            'url' => env('DATASOURCES_DEFAULT_URL', null),
            'username' => env('DATASOURCES_DEFAULT_USERNAME', ''),
            'password' => env('DATASOURCES_DEFAULT_PASSWORD', ''),
            'database' => env('DATASOURCES_DEFAULT_DATABASE', ''),
            'ssl_key' => env('DATASOURCES_DEFAULT_SSL_KEY', ''),
            'ssl_cert' => env('DATASOURCES_DEFAULT_SSL_CERT', ''),
            'ssl_ca' => env('DATASOURCES_DEFAULT_SSL_CA', ''),
            'encoding' => env('DATASOURCES_DEFAULT_ENCODING','utf8mb4'),

        ],

        /**
         * The test connection is used during the test suite.
         */
        'test' => [
            'className' => Connection::class,
            'driver' => env('DATASOURCES_TEST_DRIVER', Mysql::class),
            'persistent' => false,
            'timezone' => 'UTC',
            'encoding' => env('DATASOURCES_TEST_ENCODING','utf8mb4'),
            'flags' => [],
            'cacheMetadata' => true,
            'quoteIdentifiers' => env('DATASOURCES_QUOTE_IDENTIFIER', true),
            'log' => env('DATASOURCES_TEST_LOG', false),
            //'init' => ['SET GLOBAL innodb_stats_on_metadata = 0'],

            /*
             * Non default config
             * Passbolt support setting these from environment variables
             * They can also be overridden in passbolt.php
             */
            'host' => env('DATASOURCES_TEST_HOST', 'localhost'),
            'port' => env('DATASOURCES_TEST_PORT', 3306),
            'username' => env('DATASOURCES_TEST_USERNAME', 'my_app'),
            'password' => env('DATASOURCES_TEST_PASSWORD', 'secret'),
            'database' => env('DATASOURCES_TEST_DATABASE', 'my_app'),
            'ssl_key' => env('DATASOURCES_TEST_SSL_KEY', ''),
            'ssl_cert' => env('DATASOURCES_TEST_SSL_CERT', ''),
            'ssl_ca' => env('DATASOURCES_TEST_SSL_CA', ''),
            'url' => env('DATASOURCES_TEST_URL', null),
        ],
    ],

    /**
     * Configures logging options
     */
    'Log' => [
        'debug' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'debug',
            'levels' => ['notice', 'info', 'debug'],
            'url' => env('LOG_DEBUG_URL', null),
            'formatter' => env('LOG_DEBUG_FORMATTER', 'Cake\Log\Formatter\DefaultFormatter'),
        ],
        'error' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'error',
            'levels' => ['warning', 'error', 'critical', 'alert', 'emergency'],
            'url' => env('LOG_ERROR_URL', null),
            'formatter' => env('LOG_ERROR_FORMATTER', 'Cake\Log\Formatter\DefaultFormatter'),
        ],
        // To enable this dedicated query log, you need set your datasource's log flag to true
        // See DATASOURCES_DEFAULT_LOG
        'queries' => [
            'className' => FileLog::class,
            'path' => LOGS,
            'file' => 'queries',
            'url' => env('LOG_QUERIES_URL', null),
            'formatter' => env('LOG_QUERIES_FORMATTER', 'Cake\Log\Formatter\DefaultFormatter'),
            'scopes' => ['queriesLog'],
        ],
    ],

    /**
     * Session configuration.
     *
     * Contains an array of settings to use for session configuration. The
     * `defaults` key is used to define a default preset to use for sessions, any
     * settings declared here will override the settings of the default config.
     *
     * ## Options
     *
     * - `cookie` - The name of the cookie to use. Defaults to 'CAKEPHP'.
     * - `cookiePath` - The url path for which session cookie is set. Maps to the
     *   `session.cookie_path` php.ini config. Defaults to base path of app.
     * - `timeout` - The time in minutes the session should be valid for.
     *    Pass 0 to disable checking timeout.
     *    Please note that php.ini's session.gc_maxlifetime must be equal to or greater
     *    than the largest Session['timeout'] in all served websites for it to have the
     *    desired effect.
     * - `defaults` - The default configuration set to use as a basis for your session.
     *    There are four built-in options: php, cake, cache, database.
     * - `handler` - Can be used to enable a custom session handler. Expects an
     *    array with at least the `engine` key, being the name of the Session engine
     *    class to use for managing the session. CakePHP bundles the `CacheSession`
     *    and `DatabaseSession` engines.
     * - `ini` - An associative array of additional ini values to set.
     *
     * The built-in `defaults` options are:
     *
     * - 'php' - Uses settings defined in your php.ini.
     * - 'cake' - Saves session files in CakePHP's /tmp directory.
     * - 'database' - Uses CakePHP's database sessions.
     * - 'cache' - Use the Cache class to save sessions.
     *
     * To define a custom session handler, save it at src/Network/Session/<name>.php.
     * Make sure the class implements PHP's `SessionHandlerInterface` and set
     * Session.handler to <name>
     *
     * To use database sessions, load the SQL file located at config/Schema/sessions.sql
     */
    'Session' => [
        'cookie' => env('SESSION_COOKIE', 'passbolt_session'),
        'defaults' => env('SESSION_DEFAULTS', 'php'),
    ],
];
