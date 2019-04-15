<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Shell\Task;

use App\Shell\AppShell;
use App\Utility\Healthchecks;
use Cake\Core\Configure;

class HealthcheckTask extends AppShell
{
    /**
     * Total number of errors for that check
     *
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
     * By overriding this method you can configure the ConsoleOptionParser before returning it.
     *
     * @return \Cake\Console\ConsoleOptionParser
     * @link https://book.cakephp.org/3.0/en/console-and-shells.html#configuring-options-and-generating-help
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        // Display options
        $parser
            ->setDescription(__('Check the configuration of this installation and associated environment.'))
            ->addOption('hide-pass', [
                'help' => __d('cake_console', 'Hide passing checks.'),
                'boolean' => true
            ])
            ->addOption('hide-warning', [
                'help' => __d('cake_console', 'Hide warnings.'),
                'boolean' => true
            ])
            ->addOption('hide-help', [
                'help' => __d('cake_console', 'Hide help messages.'),
                'boolean' => true
            ])
            ->addOption('hide-title', [
                'help' => __d('cake_console', 'Hide section titles.'),
                'boolean' => true
            ]);

        // Checks
        $parser
            ->addOption('environment', [
                'help' => __d('cake_console', 'Run environment tests only.'),
                'boolean' => true
            ])
            ->addOption('configFiles', [
                'help' => __d('cake_console', 'Run configFiles tests only.'),
                'boolean' => true
            ])
            ->addOption('core', [
                'help' => __d('cake_console', 'Run core tests only.'),
                'boolean' => true
            ])
            ->addOption('ssl', [
                'help' => __d('cake_console', 'Run SSL tests only.'),
                'boolean' => true
            ])
            ->addOption('database', [
                'help' => __d('cake_console', 'Run database tests only.'),
                'boolean' => true
            ])
            ->addOption('gpg', [
                'help' => __d('cake_console', 'Run gpg tests only.'),
                'boolean' => true
            ])
            ->addOption('application', [
                'help' => __d('cake_console', 'Run passbolt app tests only.'),
                'boolean' => true
            ]);

        return $parser;
    }

    /**
     * Assert all the checks
     *
     * @return bool
     */
    public function main()
    {
        // Root user is not allowed to execute this command.
        if (!$this->assertNotRoot()) {
            return false;
        }

        $results = [];

        // display options
        $displayOptions = ['hide-pass', 'hide-warning', 'hide-help', 'hide-title'];
        foreach ($displayOptions as $option) {
            $this->_displayOptions[$option] = (isset($this->params[$option]) && $this->params[$option]);
        }

        // if user only want to run one check
        $paramChecks = [];
        $checks = [
            'environment', 'configFiles', 'core', 'ssl', 'database', 'gpg', 'application'
        ];
        foreach ($checks as $check) {
            if (isset($this->params[$check]) && $this->params[$check]) {
                $paramChecks[] = $check;
            }
        }
        if (count($paramChecks)) {
            $checks = $paramChecks;
        }

        // Run all the selected checks
        $this->out(' Healthcheck shell', 0);
        foreach ($checks as $check) {
            $this->out('.', 0); // Print a dot for each checks to show progress
            $results = array_merge(Healthchecks::{$check}(), $results);
        }
        // Remove all dots
        $this->out(str_repeat(chr(0x08), count($checks)) . str_repeat(" ", count($checks)), 0);

        // Print results
        $this->out('');
        $this->hr();
        foreach ($checks as $check) {
            $fn = 'assert' . ucfirst($check);
            $this->{$fn}($results);
        }
        $this->out('');
        $this->summary();

        return true;
    }

    /**
     * Assert all the checks
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertEnvironment($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::environment();
        }
        $this->title(__('Environment'));
        $this->assert(
            $checks['environment']['phpVersion'],
            __('PHP version {0}.', PHP_VERSION),
            __('PHP version is too low, passbolt need PHP 7.0 or higher.')
        );
        $this->assert(
            $checks['environment']['pcre'],
            __('PCRE compiled with unicode support.'),
            __('PCRE has not been compiled with Unicode support.'),
            __('Recompile PCRE with Unicode support by adding --enable-unicode-properties when configuring.')
        );
        $this->assert(
            $checks['environment']['tmpWritable'],
            __('The temporary directory and its content are writable.'),
            __('The temporary directory and its content are not writable.'),
            [
                __('Ensure the temporary directory and its content are writable by the webserver user.'),
                __('you can try:'),
                'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . TMP,
                'sudo chmod 775 $(find ' . TMP . ' -type d)',
                'sudo chmod 664 $(find ' . TMP . ' -type f)',
            ]
        );
        $this->assert(
            $checks['environment']['imgPublicWritable'],
            __('The public image directory and its content are writable.'),
            __('The public image directory and its content are not writable.'),
            [
                __('Ensure the public image directory and its content are writable by the webserver user.'),
                __('you can try:'),
                'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . IMAGES . 'public',
                'sudo chmod 775 $(find ' . IMAGES . 'public -type d)',
                'sudo chmod 664 $(find ' . IMAGES . 'public -type f)',
            ]
        );
        $this->assert(
            $checks['environment']['logWritable'],
            __('The logs directory and its content are writable.'),
            __('The logs directory and its content are not writable.'),
            [
                __('Ensure the logs directory and its content are writable by the user the webserver user.'),
                __('you can try:'),
                'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . ROOT . 'logs',
                'sudo chmod 775 $(find ' . ROOT . 'logs -type d)',
                'sudo chmod 664 $(find ' . ROOT . 'logs -type f)',
            ]
        );
        $this->assert(
            $checks['environment']['image'],
            __('GD or Imagick extension is installed.'),
            __('You must enable the gd or imagick extensions to use Passbolt.'),
            [
                __('See. https://secure.php.net/manual/en/book.image.php'),
                __('See. https://secure.php.net/manual/en/book.imagick.php'),
            ]
        );
        $this->assert(
            $checks['environment']['intl'],
            __('Intl extension is installed.'),
            __('You must enable the intl extension to use Passbolt.'),
            [
                __('See. https://secure.php.net/manual/en/book.intl.php')
            ]
        );
        $this->assert(
            $checks['environment']['mbstring'],
            __('Mbstring extension is installed.'),
            __('You must enable the mbstring extension to use Passbolt.'),
            [
                __('See. https://secure.php.net/manual/en/book.mbstring.php')
            ]
        );
    }

    /**
     * Assert config files exist
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertConfigFiles($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::configFiles();
        }
        $this->title(__('Config files'));
        $this->assert(
            $checks['configFile']['app'],
            __('The application config file is present'),
            __('The application config file is missing in {0}', CONFIG),
            __('Copy {0} to {1}', CONFIG . 'app.php.default', CONFIG . 'app.php')
        );
        $this->warning(
            $checks['configFile']['passbolt'],
            __('The passbolt config file is present'),
            __('The passbolt config file is missing in {0}', CONFIG),
            [
                __('Copy {0} to {1}', CONFIG . 'passbolt.php.default', CONFIG . 'passbolt.php'),
                __('The passbolt config file is not required if passbolt is configured with environment variables')
            ]
        );
    }

    /**
     * Assert the core file configuration
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertCore($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::core();
        }
        $this->title(__('Core config'));
        $this->assert(
            $checks['core']['debugDisabled'],
            __('Debug mode is off.'),
            __('Debug mode is on.'),
            __('Set debug = false; in {0}', 'config/passbolt.php')
        );
        $this->assert(
            $checks['core']['cache'],
            __('Cache is working.'),
            __('Cache is NOT working.'),
            __('Check the settings in {0}', 'config/app.php')
        );
        $this->assert(
            $checks['core']['salt'],
            __('Unique value set for security.salt'),
            __('Default value found for security.salt'),
            __('Edit the security.salt in {0}', 'config/app.php')
        );
        $this->assert(
            $checks['core']['fullBaseUrl'],
            __('Full base url is set to {0}', $checks['core']['info']['fullBaseUrl']),
            __('Full base url is not set. The application is using: {0}.', $checks['core']['info']['fullBaseUrl']),
            __('Edit App.fullBaseUrl in {0}', 'config/passbolt.php')
        );
        $this->assert(
            $checks['core']['validFullBaseUrl'],
            __('App.fullBaseUrl validation OK.'),
            __('App.fullBaseUrl does not validate. {0}.', $checks['core']['info']['fullBaseUrl']),
            [
                __('Edit App.fullBaseUrl in {0}', 'config/passbolt.php'),
                __('Select a valid domain name as defined by section 2.3.1 of http://www.ietf.org/rfc/rfc1035.txt')
            ]
        );
        $this->assert(
            $checks['core']['fullBaseUrlReachable'],
            __('/healthcheck/status is reachable.'),
            __('Could not reach the /healthcheck/status with the url specified in App.fullBaseUrl'),
            [
                __('Check that the domain name is correct in {0}', 'config/passbolt.php'),
                __('Check the network settings')
            ]
        );
    }

    /**
     * Assert the core file configuration
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertSSL($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::ssl();
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
        $this->warning(
            $checks['ssl']['notSelfSigned'],
            __('Not using a self-signed certificate'),
            __('Using a self-signed certificate')
        );
        if (isset($checks['ssl']['info'])) {
            $this->help($checks['ssl']['info']);
        }
    }

    /**
     * Assert database is in order
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertDatabase($checks = null)
    {
        if (!isset($checks['database']) || !isset($checks['application'])) {
            $checks = array_merge(Healthchecks::database(), Healthchecks::application());
        }
        $this->title(__('Database'));
        $this->assert(
            $checks['database']['connect'],
            __('The application is able to connect to the database'),
            __('The application is not able to connect to the database.'),
            [
                __('Double check the host, database name, username and password in config/passbolt.php'),
                __('Make sure the database exists and is accessible for the given database user.')
            ]
        );
        $this->assert(
            $checks['database']['tablesCount'],
            __('{0} tables found', $checks['database']['info']['tablesCount']),
            __('No table found'),
            [
                __('Run the install script to install the database tables'),
                'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake install" ' . PROCESS_USER
            ]
        );
        $this->assert(
            $checks['database']['defaultContent'],
            __('Some default content is present'),
            __('No default content found'),
            [
                __('Run the install script to set the default content such as roles and permission types'),
                'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake install" ' . PROCESS_USER
            ]
        );
        $this->assert(
            $checks['application']['schema'],
            __('The database schema up to date.'),
            __('The database schema is not up to date.'),
            [
                __('Run the migration scripts:'),
                'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake migrations migrate --no-lock" ' . PROCESS_USER,
                __('See. https://www.passbolt.com/help/tech/update')
            ]
        );
    }

    /**
     * Assert passbolt application configuration is in order
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertApplication($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::application();
        }
        $this->title(__('Application configuration'));
        if (!isset($checks['application']['latestVersion'])) {
            $this->assert(
                false,
                __('Could connect to passbolt repository to check versions.'),
                __('Could not connect to passbolt repository to check versions. It is not possible check if your version is up to date.'),
                __('Check the network configuration to allow this script to check for updates.')
            );
        } else {
            $this->assert(
                $checks['application']['latestVersion'],
                __('Using latest passbolt version ({0}).', Configure::read('passbolt.version')),
                __(
                    'This installation is not up to date. Currently using {0} and it should be {1}.',
                    Configure::read('passbolt.version'),
                    $checks['application']['info']['remoteVersion']
                ),
                __('See. https://www.passbolt.com/help/tech/update')
            );
        }
        $this->assert(
            $checks['application']['sslForce'],
            __('Passbolt is configured to force SSL use.'),
            __('Passbot is not configured to force SSL use.'),
            __('Set passbolt.ssl.force to true in config/passbolt.php.')
        );
        $this->assert(
            $checks['application']['sslFullBaseUrl'],
            __('App.fullBaseUrl is set to HTTPS.'),
            __('App.fullBaseUrl is not set to HTTPS.'),
            __('Check App.fullBaseUrl url scheme in {0}.', 'config/passbolt.php')
        );
        $this->assert(
            $checks['application']['seleniumDisabled'],
            __('Selenium API endpoints are disabled.'),
            __('Selenium API endpoints are active. This setting should be used for testing only.'),
            __('Set passbolt.selenium.active to false in config/passbolt.php.')
        );
        $this->warning(
            $checks['application']['robotsIndexDisabled'],
            __('Search engine robots are told not to index content.'),
            __('Search engine robots are not told not to index content.'),
            __('Set passbolt.meta.robots to false in config/passbolt.php.')
        );
        $this->warning(
            $checks['application']['registrationClosed'],
            __('Registration is closed, only administrators can add users.'),
            __('Registration is open to everyone.'),
            [
                __('Make sure this instance is not publicly available on the internet.'),
                __('Or set passbolt.registration.public to false in config/passbolt.php.')
            ]
        );
        $this->warning(
            $checks['application']['jsProd'],
            __('Serving the compiled version of the javascript app'),
            __('Using non-compiled Javascript. Passbolt will be slower.'),
            __('Set passbolt.js.build to production in config/passbolt.php')
        );
        $this->warning(
            $checks['application']['emailNotificationEnabled'],
            __('All email notifications will be sent.'),
            __('Some email notifications are disabled by the administrator.')
        );
    }

    /**
     * Warn GPG environment is in order
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertGpgEnv($checks = null)
    {
        $this->assert(
            $checks['gpg']['lib'],
            __('PHP GPG Module is installed and loaded.'),
            __('PHP GPG Module is not installed or loaded.'),
            __('Install php-gnupg, see. http://php.net/manual/en/gnupg.installation.php'),
            __('Make sure to add extension=gnupg.so in php ini files for both php-cli and php.')
        );
        $this->assert(
            $checks['gpg']['gpgHome'],
            __('The environment variable GNUPGHOME is set to {0}.', $checks['gpg']['info']['gpgHome']),
            __('The environment variable GNUPGHOME is set to {0}, but the directory does not exist.', $checks['gpg']['info']['gpgHome']),
            [
                __('Ensure the keyring location exists and is accessible by the webserver user.'),
                __('you can try:'),
                'sudo mkdir ' . $checks['gpg']['info']['gpgHome'],
                'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . $checks['gpg']['info']['gpgHome'],
                'sudo chmod 700 ' . $checks['gpg']['info']['gpgHome'],
                __('You can change the location of the keyring by editing the GPG.env.setenv and GPG.env.home variables in config/passbolt.php.'),
            ]
        );
        if ($checks['gpg']['gpgHome']) {
            $this->assert(
                $checks['gpg']['gpgHomeWritable'],
                __('The directory {0} containing the keyring is writable by the webserver user.', $checks['gpg']['info']['gpgHome']),
                __('The directory {0} containing the keyring is not writable by the webserver user.', $checks['gpg']['info']['gpgHome']),
                [
                    __('Ensure the keyring location is accessible by the webserver user.'),
                    __('you can try:'),
                    'sudo chown -R ' . PROCESS_USER . ':' . PROCESS_USER . ' ' . $checks['gpg']['info']['gpgHome'],
                    'sudo chmod 700 ' . $checks['gpg']['info']['gpgHome'],
                ]
            );
        }
    }

    /**
     * Warn GPG settings are in order
     *
     * @param array $checks existing results
     * @return void
     */
    public function assertGpg($checks = null)
    {
        if (!isset($checks)) {
            $checks = Healthchecks::gpg();
        }
        $this->title(__('GPG Configuration'));
        $this->assertGpgEnv($checks);
        if ($checks['gpg']['gpgKey']) {
            $this->assert(
                $checks['gpg']['gpgKeyNotDefault'],
                __('The server gpg key is not the default one'),
                __('Do not use the default gpg key for the server'),
                [
                    __('Create a key, export it and add the fingerprint to config/passbolt.php'),
                    __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
                ]
            );
        } else {
            $this->assert(
                $checks['gpg']['gpgKey'],
                __('The server gpg key is set'),
                __('The server gpg key is not set'),
                [
                    __('Create a key, export it and add the fingerprint to config/passbolt.php'),
                    __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
                ]
            );
        }
        $this->assert(
            $checks['gpg']['gpgKeyPublic'] && $checks['gpg']['gpgKeyPublicReadable'] && $checks['gpg']['gpgKeyPublicBlock'],
            __('The public key file is defined in config/passbolt.php and readable.'),
            __('The public key file is not defined in config/passbolt.php or not readable.'),
            [
                __('Ensure the public key file is defined by the variable passbolt.gpg.serverKey.public in config/passbolt.php.'),
                __('Ensure there is a public key armored block in the key file.'),
                __('Ensure the public key defined in config/passbolt.php exists and is accessible by the webserver user.'),
                __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
            ]
        );
        $this->assert(
            $checks['gpg']['gpgKeyPrivate'] && $checks['gpg']['gpgKeyPrivateReadable'] && $checks['gpg']['gpgKeyPrivateBlock'],
            __('The private key file is defined in config/passbolt.php and readable.'),
            __('The private key file is not defined in config/passbolt.php or not readable.'),
            [
                __('Ensure the private key file is defined by the variable passbolt.gpg.serverKey.private in config/passbolt.php.'),
                __('Ensure there is a private key armored block in the key file.'),
                __('Ensure the private key defined in config/passbolt.php exists and is accessible by the webserver user.'),
                __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
            ]
        );
        $this->assert(
            $checks['gpg']['gpgKeyPrivateFingerprint'] && $checks['gpg']['gpgKeyPublicFingerprint'],
            __('The server key fingerprint matches the one defined in config/passbolt.php.'),
            __('The server key fingerprint doesn\'t match the one defined in config/passbolt.php.'),
            [
                __('Double check the key fingerprint, example: '),
                'sudo su -s /bin/bash -c "gpg --list-keys --fingerprint --home ' . $checks['gpg']['info']['gpgHome'] . '" ' . PROCESS_USER . ' | grep -i -B 2 \'SERVER_KEY_EMAIL\'',
                __('SERVER_KEY_EMAIL: The email you used when you generated the server key.'),
                __('See. https://www.passbolt.com/help/tech/install#toc_gpg')
            ]
        );
        $this->assert(
            $checks['gpg']['gpgKeyPublicInKeyring'],
            __('The server public key defined in the config/passbolt.php (or environment variables) is in the keyring.'),
            __('The server public key defined in the config/passbolt.php (or environment variables) is not in the keyring'),
            [
                __('Import the private server key in the keyring of the webserver user.'),
                __('you can try:'),
                'sudo su -s /bin/bash -c "gpg --home ' . $checks['gpg']['info']['gpgHome'] . ' --import ' . $checks['gpg']['info']['gpgKeyPrivate'] . '" ' . PROCESS_USER
            ]
        );
        $this->assert(
            $checks['gpg']['gpgKeyPublicEmail'],
            __('There is a valid email id defined for the server key.'),
            __('The server key does not have a valid email id.'),
            __('Edit or generate another key with a valid email id.')
        );

        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $tip = [
                __('Make sure that the server private key is valid and that there is no passphrase.'),
                __('Make sure you imported the private server key in the keyring of the webserver user.'),
                __('you can try:'),
                'sudo su -s /bin/bash -c "gpg --home ' . $checks['gpg']['info']['gpgHome'] . ' --import ' . $checks['gpg']['info']['gpgKeyPrivate'] . '" ' . PROCESS_USER
            ];

            $this->assert(
                $checks['gpg']['canEncrypt'],
                __('The public key can be used to encrypt a message.'),
                __('The public key cannot be used to encrypt a message'),
                $tip
            );
            $this->assert(
                $checks['gpg']['canSign'],
                __('The private key can be used to sign a message.'),
                __('The private key cannot be used to sign a message'),
                $tip
            );
            $this->assert(
                $checks['gpg']['canEncryptSign'],
                __('The public and private keys can be used to encrypt and sign a message.'),
                __('The public and private keys cannot be used to encrypt and sign a message')
            );
            $this->assert(
                $checks['gpg']['canDecrypt'],
                __('The private key can be used to decrypt a message.'),
                __('The private key cannot be used to decrypt a message')
            );
            $this->assert(
                $checks['gpg']['canDecryptVerify'],
                __('The private key can be used to decrypt and verify a message.'),
                __('The private key cannot be used to decrypt and verify a message')
            );
            $this->assert(
                $checks['gpg']['canVerify'],
                __('The public key can be used to verify a signature.'),
                __('The public key cannot be used to verify a signature.')
            );
        }
    }

    /**
     * Display a success or error message depending on given condition
     *
     * @param bool $condition to check
     * @param string $success to display when success
     * @param string $error to display when error
     * @param string $help string optional help message
     * @return void
     */
    protected function assert($condition, $success, $error, $help = null)
    {
        if ($condition) {
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
     * @param bool $condition to check
     * @param string $success message to display when success
     * @param string $warning message to display if fails
     * @param string $help optional help message
     * @return void
     */
    protected function warning($condition, $success, $warning, $help = null)
    {
        if ($this->_displayOptions['hide-warning']) {
            return;
        }
        if ($condition) {
            $this->display($success, 'pass');
        } else {
            $this->display($warning, 'warn');
            $this->help($help);
        }
    }

    /**
     * Display one or more help messages
     *
     * @param array $help messages
     * @return void
     */
    protected function help($help = null)
    {
        if (isset($help)) {
            if (is_array($help)) {
                foreach ($help as $helpMsg) {
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
     * @param string $msg message
     * @param string $case pass or fail
     * @throws Exception case is not defined or missing
     * @return void
     */
    protected function display($msg, $case)
    {
        switch ($case) {
            case 'pass':
                if ($this->_displayOptions['hide-pass']) {
                    return;
                }
                $msg = ' <success>[' . __('PASS') . ']</success> ' . $msg;
                break;
            case 'fail':
                $msg = ' <fail>[' . __('FAIL') . '] ' . $msg . '</fail>';
                break;
            case 'warn':
                $msg = ' <warning>[' . __('WARN') . '] ' . $msg . '</warning>';
                break;
            case 'info':
                if ($this->_displayOptions['hide-help']) {
                    return;
                }
                $msg = '  <info>[' . __('HELP') . ']</info> ' . $msg;
                break;
            default:
                throw new Exception('Task output case not defined: ' . $case . ' ' . $msg);
        }
        $this->out($msg);
    }

    /**
     * Title section display
     *
     * @param string $title message
     * @return void
     */
    protected function title($title)
    {
        if ($this->_displayOptions['hide-title']) {
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
    protected function summary()
    {
        if ($this->__errorCount >= 1) {
            $summary = ' <fail> ' . __('{0} error(s) found. Hang in there!', $this->__errorCount) . '</fail>';
        } else {
            $summary = ' <success>' . __('No error found. Nice one sparky!') . '</success>';
        }
        $this->out($summary);
        $this->out('');
    }
}
