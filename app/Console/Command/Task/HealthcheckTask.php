<?php
/**
 * Healthcheck task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Healthchecks', 'Lib');
App::uses('Healthchecks', 'Lib');

class HealthcheckTask extends AppShell {
    private $__errorCount = 0;

/**
 * Gets the option parser instance and configures it.
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
        $this->stdout->styles(
            'fail', array('text' => 'red', 'blink' => false),
            'success', array('text' => 'green', 'blink' => false)
        );

		$parser = parent::getOptionParser();
		$parser
			->description(__('Check the configuration of the passbolt installation and associated environment'));

		return $parser;
	}

/**
 * Register a new user.
 *
 * @return void
 */
	public function execute() {
        $this->out(' Healthcheck shell', 0);

        // Print a dot for each checks
        $results = [];
        $checks = [
    	    'environment', 'configFiles', 'core', 'database', 'gpg', 'app', 'devTools'
        ];
        foreach($checks as $check) {
            $this->out('.', 0);
            $results = array_merge(Healthchecks::{$check}(), $results);
        }
        $this->out(str_repeat(chr(0x08),sizeof($checks)).str_repeat(" ",sizeof($checks)), 0);

        // Print results
        $this->out('');
        $this->hr();
        foreach($checks as $check) {
            $fn = 'assert' . ucfirst($check);
            $this->{$fn}($results);
        }
        $this->out('');
        $this->summary();
	}

/**
 * Assert all the checks
 *
 * @param $checks array
 * @return void
 */
    public function assertEnvironment($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::environment();
        }
        $this->title(__('Environment'));
        $this->assert(
            $checks['environment']['phpVersion'],
            __('PHP version %s', PHP_VERSION),
            __('PHP version is too low, passbolt need PHP 5.2.8 or higher')
        );
        $this->assert(
            $checks['environment']['pcre'],
            __('PCRE compiled with unicode support'),
            __('PCRE has not been compiled with Unicode support'),
            __('Recompile PCRE with Unicode support by adding --enable-unicode-properties when configuring')
        );
        $this->assert(
            $checks['environment']['tmpWritable'],
            __('The app/tmp directory is writable'),
            __('The app/tmp directory is not writable'),
            __('try: chmod -R 770 app/tmp')
        );
        $this->assert(
            $checks['environment']['imgPublicWritable'],
            __('The app/webroot/img/public directory is writable'),
            __('The app/webroot/img/public directory is not writable'),
            __('try: chmod -R 770 app/webroot/img/public')
        );
    }

/**
 * Assert config files exist
 *
 * @param $checks array
 * @return void
 */
    public function assertConfigFiles($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::configFiles();
        }
        $this->title(__('Config files'));
        $this->assert(
            $checks['configFile']['core'],
            __('The core config file is present'),
            __('The core config file is missing in app/Core'),
            [
                __('Copy %s to %s', 'app/Config/core.php.default', 'app/Config/core.php'),
                __('Set the App.fullBaseUrl, Security.cipherSeed, and Security.salt')
            ]
        );
        $this->assert(
            $checks['configFile']['database'],
            __('The database config file is present'),
            __('The database config file is missing in app/Core'),
            [
                __('Copy %s to %s', 'app/Config/database.php.default', 'app/Config/database.php'),
                __('Set the host, database, username, password')
            ]
        );
        $this->assert(
            $checks['configFile']['email'],
            __('The email config file is present'),
            __('The email config file is missing in app/Config'),
            [
                __('Copy %s to %s', 'app/Config/email.php.default', 'app/Config/email.php'),
                __('Set the SMTP host, username, password and more if needed')
            ]
        );
        $this->assert(
            $checks['configFile']['app'],
            __('The application config file is present'),
            __('The application config file is missing in app/Core'),
            __('Copy %s to %s', 'app/Config/app.php.default', 'app/Config/app.php')
        );
    }

/**
 * Assert the core file configuration
 *
 * @param $checks array
 * @return void
 */
    public function assertCore($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::core();
        }
        $this->title(__('Core config'));
        $this->assert($checks['core']['debugDisabled'],
            __('Debug mode is off.'),
            __('Debug mode is on.'),
            __('Set Configure::write(\'debug\', 0); in %s', 'app/Config/core.php')
        );
        $this->assert($checks['core']['cache'],
            __('Cache is working.'),
            __('Cache is NOT working.'),
            __('Check the settings in %s', 'core.php')
        );
        $this->assert($checks['core']['salt'],
            __('Unique value set for security.salt'),
            __('Default value found for security.salt'),
            __('Edit the security.salt in %s', 'core.php')
        );
        $this->assert($checks['core']['cipherSeed'],
            __('Unique value set for security.cipherSeed'),
            __('Default value found for security.cipherSeed'),
            __('Edit the security.salt in %s', 'core.php')
        );
    }

/**
 * Assert database is in order
 *
 * @param $checks array
 * @return void
 */
    public function assertDatabase($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::database();
        }
        $this->title(__('Database'));
        $this->assert(
            $checks['database']['supportedBackend'],
            __('Configured to use a supported database backend'),
            __('Configuration file set to use a non-supported backend'),
            __('Use Database/Mysql instead.')
        );
        $this->assert(
            $checks['database']['connect'],
            __('The application is able to connect to the database'),
            __('The application is not able to connect to the database.'),
            [
                __('Double check the host, database name, username and password in app/Config/database.php'),
                __('Make sure the database exists and is accessible for the given database user.')
            ]
        );
        $this->assert(
            $checks['database']['tablesPrefix'],
            __('Not using a prefix for database tables'),
            __('Using a prefix for database tables'),
            __('Table prefix are not supported. see. https://github.com/passbolt/passbolt_api/issues/56')
        );
        $this->assert(
            $checks['database']['tablesCount'],
            __('%s tables found', $checks['database']['info']['tablesCount']),
            __('No table found'),
            __('Run the install script to install the database tables')
        );
        $this->assert(
            $checks['database']['defaultContent'],
            __('Some default content is present'),
            __('No default content found'),
            __('Run the install script to set the default content such as roles and permission types')
        );
        $this->assert(
            $checks['app']['schema'],
            __('The database schema up to date.'),
            __('The database schema is not up to date.'),
            [
                __('Run the migration scripts:'),
                './app/Console/cake Migrations.migration run all',
                __('See. https://www.passbolt.com/help/tech/update')
            ]
        );
    }

/**
 * Assert application configuration is in order
 *
 * @param $checks array
 * @return void
 */
    public function assertApp($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::app();
        }
        $this->title(__('Application configuration'));
        if (!isset($checks['app']['latestVersion'])) {
            $this->assert(
                false,
                __('Could connect to passbolt repository to check versions'),
                __('Could not connect to passbolt repository to check versions. It is not possible check if your version is up to date.'),
                __('Check the network configuration to allow this script to check for updates')
            );
        } else {
            $this->assert(
                $checks['app']['latestVersion'],
                __('Using latest passbolt version (%s)', Configure::read('App.version.number')),
                __('This installation is not up to date. Currently using %s and it should be %s.', Configure::read('App.version.number'), $checks['app']['info']['remoteVersion']),
                __('See. https://www.passbolt.com/help/tech/update')
            );
        }
        $this->assert(
            $checks['app']['sslForce'],
            __('Passbolt is configured to force SSL use'),
            __('Passbot is not configured to force SSL use'),
            __('Set App.ssl.force to true in app/Config/app.php')
        );
        $this->assert(
            $checks['app']['seleniumDisabled'],
            __('Selenium API endpoints are disabled.'),
            __('Selenium API endpoints are active. This setting should be used for testing only.'),
            __('Set App.selenium.active to false in app/Config/app.php')
        );
        $this->warn(
            $checks['app']['registrationClosed'],
            __('Registration is closed, only administrators can add users.'),
            __('Registration is open to everyone.'),
            __('Open registration is generally not a good idea if the passbolt service is publicly available.')
        );
        $this->warn(
            $checks['app']['jsProd'],
            __('Serving the compiled version of the javascript app'),
            __('Using non-compiled Javascript. Passbolt will be slower'),
            __('Set App.js.build to production in app/Config/app.php')
        );
    }

/**
 * Warn GPG settings are in order
 *
 * @param $checks array
 * @return void
 */
    public function assertGpg($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::devTools();
        }
        $this->title(__('GPG Configuration'));
        $this->assert(
            $checks['gpg']['lib'],
            __('PHP GPG Module is installed and loaded'),
            __('PHP GPG Module is not installed or loaded'),
            __('Install php-gnupg, see. http://php.net/manual/en/gnupg.installation.php'),
            __('Make sure to add extension=gnupg.so in php ini files for both php-cli and php')
        );
        if($checks['gpg']['gpgKey']) {
            $this->assert(
                $checks['gpg']['gpgKeyDefault'],
                __('The server gpg key is not the default one'),
                __('Do not use the default gpg key for the server'),
                [
                    __('Create a key, export it and add the fingerprint to app/Config/app.php'),
                    __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
                ]
            );
        } else {
            $this->assert(
                $checks['gpg']['gpgKey'],
                __('The server gpg key is set'),
                __('The server gpg key is not set'),
                __('Create a key, export it and add the fingerprint to app/Config/app.php')
            );
        }
    }

/**
 * Warn if dev tools are present
 *
 * @param $checks array
 * @return void
 */
    public function assertDevTools($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::devTools();
        }
        $this->title(__('Development Tools (optional)'));
        $this->warn(
            $checks['devTools']['phpunit'],
            __('Phpunit is installed'),
            __('Phpunit is not installed.')
        );
        $this->warn(
            $checks['devTools']['phpunitVersion'],
            __('Phpunit version is 3.7.38'),
            __('Phpunit version is not 3.7.38'),
            __('See. CONTRIBUTING.md for instructions on how to install the right version of phpunit')
        );
    }

/**
 * Display a success or error message depending on given condition
 *
 * @param $condition bool
 * @param $success string
 * @param $error string
 * @param $help string optional help message
 * @return void
 */
    protected function assert($condition, $success, $error, $help = null) {
        if($condition) {
            $this->display($success, 'pass');
        } else {
            $this->__errorCount++;
            $this->display($error, 'fail');
            $this->help($help);
        }
    }

/**
 * Display a success or warning message depending on given condition
 *
 * @param $condition bool
 * @param $success string
 * @param $error string
 * @param $help string optional help message
 * @return void
 */
    protected function warn($condition, $success, $warning, $help = null) {
        if($condition) {
            $this->display($success, 'pass');
        } else {
            $this->display($warning, 'warn');
            $this->help($help);
        }
    }

/**
 * Display one or more help messages
 *
 * @param array $help
 * @return void
 */
    protected  function help($help = null) {
        if(isset($help)) {
            if(is_array($help)) {
                foreach($help as $helpMsg) {
                    $this->display($helpMsg, 'info');
                }
            } else {
                $this->display($help, 'info');
            }
        }
    }

/**
 * Display a message for given case
 *
 * @param $msg string
 * @param $case string pass or fail
 * @throws Exception case is not defined or missing
 * @return void
 */
    protected function display($msg, $case) {

        switch ($case) {
            case 'pass':
                $msg = ' <success>['. __('PASS') . ']</success> ' . $msg;
            break;
            case 'fail':
                $msg = ' <fail>['. __('FAIL') . '] ' . $msg . '</fail>';
            break;
            case 'warn':
                $msg = ' <warning>['. __('WARN') . ']</warning> ' . $msg;
                break;
            case 'info':
                $msg = '  <info>['. __('HELP') . '] ' . $msg . '</info>';
                break;
            default:
                throw new Exception('Task output case not defined: ' . $case . ' ' . $msg);
            break;
        }
        $this->out($msg);
    }

/**
 * Title section display
 *
 * @param $title string
 * @return title
 */
     protected function title($title) {
        $this->out('');
        $this->out(' ' . $title);
        $this->out('');
     }

/**
 * Display a summary with an error count if any
 *
 * @return void
 */
    protected function summary() {
        if($this->__errorCount >= 1) {
            $summary = ' <fail> ' . __('%s error(s) found. Try correcting them before installing.' . '</fail>', $this->__errorCount);
        } else {
            $summary = ' <success>' . __('No error found. Nice one sparky!') . '</success>';
        }
        $this->out($summary);
        $this->out('');
    }
}
