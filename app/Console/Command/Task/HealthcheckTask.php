<?php
/**
 * Healthcheck task
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('Healthchecks', 'Lib');

class HealthcheckTask extends AppShell {
/**
 * Total number of errors for that check
 * @var int
 */
    private $__errorCount = 0;

/**
 * Control what get displayed / what to hide
 *
 * @var array
 */
    protected $_displayOptions = [
        'hide-pass' => false,
        'hide-warning' => false,
        'hide-help' => false,
        'hide-title' => false
    ];

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

        // Display options
		$parser
			->description(__('Check the configuration of this installation and associated environment'))
            ->addOption('hide-pass', array(
                'help' => __d('cake_console', 'Hide passing checks'),
                'boolean' => true
            ))
            ->addOption('hide-warning', array(
                'help' => __d('cake_console', 'Hide warnings'),
                'boolean' => true
            ))
            ->addOption('hide-help', array(
                'help' => __d('cake_console', 'Hide help messages'),
                'boolean' => true
            ))
            ->addOption('hide-title', array(
                'help' => __d('cake_console', 'Hide section titles'),
                'boolean' => true
            ));

        // Checks
        $parser
            ->addOption('environment', array(
                'help' => __d('cake_console', 'Run environment tests only.'),
                'boolean' => true
            ))
            ->addOption('configFiles', array(
                'help' => __d('cake_console', 'Run configFiles tests only.'),
                'boolean' => true
            ))
            ->addOption('core', array(
                'help' => __d('cake_console', 'Run core tests only.'),
                'boolean' => true
            ))
            ->addOption('ssl', array(
                'help' => __d('cake_console', 'Run SSL tests only.'),
                'boolean' => true
            ))
            ->addOption('database', array(
                'help' => __d('cake_console', 'Run database tests only.'),
                'boolean' => true
            ))
            ->addOption('gpg', array(
                'help' => __d('cake_console', 'Run gpg tests only.'),
                'boolean' => true
            ))
            ->addOption('application', array(
                'help' => __d('cake_console', 'Run passbolt app tests only.'),
                'boolean' => true
            ))
            ->addOption('devTools', array(
                'help' => __d('cake_console', 'Run devTools tests only.'),
                'boolean' => true
            ));

		return $parser;
	}

/**
 * Register a new user.
 *
 * @return void
 */
	public function execute() {
		// Root user is not allowed to execute this command.
		// This command needs to be executed with the same user as the webserver.
		$this->rootNotAllowed();

        $results = [];

        // display options
        $displayOptions = ['hide-pass', 'hide-warning', 'hide-help', 'hide-title'];
        foreach($displayOptions as $option) {
            $this->_displayOptions[$option] = (isset($this->params[$option]) && $this->params[$option]);
        }

        // if user only want to run one check
        $paramChecks = [];
        $checks = [
            'environment', 'configFiles', 'core', 'ssl', 'database', 'gpg', 'application', 'devTools'
        ];
        foreach($checks as $check) {
            if (isset($this->params[$check]) && $this->params[$check]) {
                $paramChecks[] = $check;
            }
        }
        if(count($paramChecks)) {
            $checks = $paramChecks;
        }

        // Run all the selected checks
        $this->out(' Healthcheck shell', 0);
        foreach($checks as $check) {
            $this->out('.', 0); // Print a dot for each checks to show progress
            $results = array_merge(Healthchecks::{$check}(), $results);
        }
        // Remove all dots
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
			__('The temporary directory and its content are writable'),
			__('The temporary directory and its content are not writable'),
			[
				__('Ensure the temporary directory and its content are writable by the user the webserver is running as.'),
				__('you can try:'),
				'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . APP . 'tmp',
				'sudo chmod 775 $(find ' . APP . 'tmp -type d)',
				'sudo chmod 664 $(find ' . APP . 'tmp -type f)',
			]
		);
        $this->assert(
            $checks['environment']['imgPublicWritable'],
            __('The public image directory and its content are writable'),
            __('The public image directory and its content are not writable'),
			[
				__('Ensure the public image directory and its content are writable by the user the webserver is running as.'),
				__('you can try:'),
				'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . IMAGES . 'public',
				'sudo chmod 775 $(find ' . IMAGES . 'public -type d)',
				'sudo chmod 664 $(find ' . IMAGES . 'public -type f)',
			]
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
            __('The core config file is missing in %s', APP . 'Config'),
            [
                __('Copy %s to %s', APP . 'Config/core.php.default', APP . 'Config/core.php'),
                __('Set the App.fullBaseUrl, Security.cipherSeed, and Security.salt')
            ]
        );
        $this->assert(
            $checks['configFile']['database'],
            __('The database config file is present'),
            __('The database config file is missing in %s', APP . 'Config'),
            [
                __('Copy %s to %s', APP . 'Config/database.php.default', APP . 'Config/database.php'),
                __('Set the host, database, username, password')
            ]
        );
        $this->assert(
            $checks['configFile']['email'],
            __('The email config file is present'),
            __('The email config file is missing in %s', APP . 'Config'),
            [
                __('Copy %s to %s', APP . 'Config/email.php.default', APP . 'Config/email.php'),
                __('Set the SMTP host, username, password and more if needed')
            ]
        );
        $this->assert(
            $checks['configFile']['app'],
            __('The application config file is present'),
            __('The application config file is missing in %s', APP . 'Config'),
            __('Copy %s to %s',  APP . 'Config/app.php.default',  APP . 'Config/app.php')
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
        $this->assert(
            $checks['core']['debugDisabled'],
            __('Debug mode is off.'),
            __('Debug mode is on.'),
            __('Set Configure::write(\'debug\', 0); in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['core']['cache'],
            __('Cache is working.'),
            __('Cache is NOT working.'),
            __('Check the settings in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['core']['salt'],
            __('Unique value set for security.salt'),
            __('Default value found for security.salt'),
            __('Edit the security.salt in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['core']['cipherSeed'],
            __('Unique value set for security.cipherSeed'),
            __('Default value found for security.cipherSeed'),
            __('Edit the security.cipherSeed in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['core']['fullBaseUrl'],
            __('Full base url is set to %s', $checks['core']['info']['fullBaseUrl']),
            __('Full base url is not set. The application is using: %s.', $checks['core']['info']['fullBaseUrl']),
            __('Edit App.fullBaseUrl in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['core']['validFullBaseUrl'],
            __('App.fullBaseUrl validation OK.'),
            __('App.fullBaseUrl does not validate. %s.', $checks['core']['info']['fullBaseUrl']),
            [
                __('Edit App.fullBaseUrl in %s', 'app/Config/core.php'),
                __('Select a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt')
            ]
        );
        $this->assert(
            $checks['core']['fullBaseUrlReachable'],
            __('/healthcheck/status is reachable.'),
            __('Could not reach the /healthcheck/status with the url specified in App.fullBaseUrl'),
            [
                __('Check that the domain name is correct in %s', 'app/Config/core.php'),
                __('Check the network settings')
            ]
        );
    }

/**
 * Assert the core file configuration
 *
 * @param $checks array
 * @return void
 */
    public function assertSSL($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::ssl();
        }
        $this->title(__('SSL Certificate'));
        $this->assert(
            $checks['ssl']['peerValid'],
            __('SSL peer certificate validates'),
            __('SSL peer certificate does not validate')
        );
        $this->assert(
            $checks['ssl']['hostValid'],
            __('Hostname is matching in SSL certificate.'),
            __('Hostname does not match when validating certificates.')
        );
        $this->warn(
            $checks['ssl']['notSelfSigned'],
            __('Not using a self-signed certificate'),
            __('Using a self-signed certificate')
        );
        if(isset($checks['ssl']['info'])){
            $this->help($checks['ssl']['info']);
        }
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
            [
                __('Run the install script to install the database tables'),
                'sudo su -s /bin/bash -c "' . APP . 'Console/cake install" ' . PROCESS_USER
            ]
        );
        $this->assert(
            $checks['database']['defaultContent'],
            __('Some default content is present'),
            __('No default content found'),
			[
				__('Run the install script to set the default content such as roles and permission types'),
				'sudo su -s /bin/bash -c "' . APP . 'Console/cake install" ' . PROCESS_USER
			]
        );
        $this->assert(
            $checks['application']['schema'],
            __('The database schema up to date.'),
            __('The database schema is not up to date.'),
            [
                __('Run the migration scripts:'),
				'sudo su -s /bin/bash -c "' . APP . 'Console/cake Migrations.migration run all" ' . PROCESS_USER,
                __('See. https://www.passbolt.com/help/tech/update')
            ]
        );
    }

/**
 * Assert passbolt application configuration is in order
 *
 * @param $checks array
 * @return void
 */
    public function assertApplication($checks=null) {
        if(!isset($checks)) {
            $checks =  Healthchecks::application();
        }
        $this->title(__('Application configuration'));
        if (!isset($checks['application']['latestVersion'])) {
            $this->assert(
                false,
                __('Could connect to passbolt repository to check versions'),
                __('Could not connect to passbolt repository to check versions. It is not possible check if your version is up to date.'),
                __('Check the network configuration to allow this script to check for updates')
            );
        } else {
            $this->assert(
                $checks['application']['latestVersion'],
                __('Using latest passbolt version (%s)', Configure::read('App.version.number')),
                __('This installation is not up to date. Currently using %s and it should be %s.', Configure::read('App.version.number'), $checks['application']['info']['remoteVersion']),
                __('See. https://www.passbolt.com/help/tech/update')
            );
        }
        $this->assert(
            $checks['application']['sslForce'],
            __('Passbolt is configured to force SSL use'),
            __('Passbot is not configured to force SSL use'),
            __('Set App.ssl.force to true in app/Config/app.php')
        );
        $this->assert(
            $checks['application']['sslFullBaseUrl'],
            __('App.fullBaseUrl is set to HTTPS'),
            __('App.fullBaseUrl is not set to HTTPS'),
            __('Check App.fullBaseUrl url scheme in %s', 'app/Config/core.php')
        );
        $this->assert(
            $checks['application']['seleniumDisabled'],
            __('Selenium API endpoints are disabled.'),
            __('Selenium API endpoints are active. This setting should be used for testing only.'),
            __('Set App.selenium.active to false in app/Config/app.php')
        );
        $this->warn(
            $checks['application']['robotsIndexDisabled'],
            __('Search engine robots are told not to index content.'),
            __('Search engine robots are not told not to index content.'),
            __('Set App.meta.robots.index to false in app/Config/app.php')
        );
        $this->warn(
            $checks['application']['registrationClosed'],
            __('Registration is closed, only administrators can add users.'),
            __('Registration is open to everyone.'),
            [
                __('Make sure this instance is not publicly available on the internet.'),
                __('Or set App.registration.public to false in app/Config/app.php')
            ]
        );
        $this->warn(
            $checks['application']['jsProd'],
            __('Serving the compiled version of the javascript app'),
            __('Using non-compiled Javascript. Passbolt will be slower'),
            __('Set App.js.build to production in app/Config/app.php')
        );
		$this->warn(
			$checks['application']['emailNotificationEnabled'],
			__('All email notifications will be sent.'),
			__('Some email notifications are disabled by the administrator.')
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
                $checks['gpg']['gpgKeyNotDefault'],
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
				[
					__('Create a key, export it and add the fingerprint to app/Config/app.php'),
					__('See. https://www.passbolt.com/help/tech/install#toc_gpg')
				]
            );
        }
        $this->assert(
            $checks['gpg']['gpgHome'],
            __('The environment variable GNUPGHOME is set to %s', $checks['gpg']['info']['gpgHome']),
            __('The environment variable GNUPGHOME is set to %s, but the directory doesn\'t exist.', $checks['gpg']['info']['gpgHome']),
			[
				__('Ensure the keyring location exists and is accessible by the user the webserver is running as.'),
				__('you can try:'),
				'sudo mkdir ' . $checks['gpg']['info']['gpgHome'],
				'sudo chown -R '. PROCESS_USER . ':' . PROCESS_USER . ' ' . $checks['gpg']['info']['gpgHome'],
				'sudo chmod 700 ' . $checks['gpg']['info']['gpgHome'],
				__('You can change the location of the keyring by editing the GPG.env.setenv and GPG.env.home variables in app/Config/app.php.'),
			]
        );
		if ($checks['gpg']['gpgHome']) {
			$this->assert(
				$checks['gpg']['gpgHomeWritable'],
				__('The directory %s containing the keyring is writable by the user the webserver is running as.', $checks['gpg']['info']['gpgHome']),
				__('The directory %s containing the keyring is not writable by the user the webserver is running as.', $checks['gpg']['info']['gpgHome']),
				[
					__('Ensure the keyring location is accessible by the user the webserver is running as.'),
					__('you can try:'),
					'sudo chown -R '. PROCESS_USER . ':' . PROCESS_USER . ' ' . $checks['gpg']['info']['gpgHome'],
					'sudo chmod 700 ' . $checks['gpg']['info']['gpgHome'],
				]
			);
		}
		$this->assert(
			$checks['gpg']['gpgKeyPublic'] && $checks['gpg']['gpgKeyPublicReadable'],
			__('The public key file is defined in app/config.php and readable.'),
			__('The public key file is not defined in app/config.php or not readable.'),
			[
				__('Ensure the public key file is defined by the variable GPG.serverKey.public in app/Config/app.php.'),
				__('Ensure the public key defined in app/Config/app.php exists and is accessible by the user the webserver is running as.'),
				__('See. https://www.passbolt.com/help/tech/install#toc_gpg')
			]
		);
		$this->assert(
			$checks['gpg']['gpgKeyPrivate'] && $checks['gpg']['gpgKeyPrivateReadable'],
			__('The private key file is defined in app/config.php and readable.'),
			__('The public key file is not defined in app/config.php or not readable.'),
			[
				__('Ensure the private key file is defined by the variable GPG.serverKey.private in app/Config/app.php.'),
				__('Ensure the private key defined in app/Config/app.php exists and is accessible by the user the webserver is running as.'),
				__('See. https://www.passbolt.com/help/tech/install#toc_gpg')
			]
		);
		$this->assert(
			$checks['gpg']['gpgKeyPrivateFingerprint'] && $checks['gpg']['gpgKeyPublicFingerprint'],
			__('The server key fingerprint matches the one defined in app/config.php.'),
			__('The server key fingerprint doesn\'t match the one defined in app/config.php.'),
			[
				__('Double check the key fingerprint, example: '),
				'sudo su -s /bin/bash -c "gpg --list-keys --fingerprint --home ' . $checks['gpg']['info']['gpgHome'] . '" ' . PROCESS_USER . ' | grep -i -B 2 \'SERVER_KEY_EMAIL\'',
				__('SERVER_KEY_EMAIL: The email you used when you generated the server key.'),
				__('See. https://www.passbolt.com/help/tech/install#toc_gpg')
			]
		);
		$this->{$checks['database']['tablesCount']?'assert':'warn'}(
			$checks['gpg']['gpgKeyPrivateInKeyring'],
			__('The server key defined in the app/Config.php is in the keyring.'),
			__('The server key defined in the app/Config.php is not in the keyring'),
			[
				__('Import the private server key in the keyring of the user the webserver is running as.'),
				__('you can try:'),
				'sudo su -s /bin/bash -c "gpg --import ' . $checks['gpg']['info']['gpgKeyPrivate'] . ' --home ' . $checks['gpg']['info']['gpgHome'] . '" ' . PROCESS_USER
			]
		);
		$this->assert(
			$checks['gpg']['gpgKeyPublicEmail'],
			__('There is a valid email id defined for the server key.'),
			__('The server key does not have a valid email id.'),
			__('Edit or generate another key with a valid email id.')
		);
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
        if($this->_displayOptions['hide-warning']) {
            return;
        }
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
                if($this->_displayOptions['hide-pass']) {
                    return;
                }
                $msg = ' <success>['. __('PASS') . ']</success> ' . $msg;
                break;
            case 'fail':
                $msg = ' <fail>['. __('FAIL') . '] ' . $msg . '</fail>';
            break;
            case 'warn':
                $msg = ' <warning>['. __('WARN') . '] ' . $msg . '</warning>';
                break;
            case 'info':
                if($this->_displayOptions['hide-help']) {
                    return;
                }
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
         if($this->_displayOptions['hide-title']) {
             return;
         }
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
            $summary = ' <fail> ' . __('%s error(s) found. Hang in there!', $this->__errorCount) . '</fail>';
        } else {
            $summary = ' <success>' . __('No error found. Nice one sparky!') . '</success>';
        }
        $this->out($summary);
        $this->out('');
    }
}
