<?php
App::uses('Validation', 'Utility');
App::uses('Migration', 'Lib/Migration');
App::uses('HttpSocket', 'Network/Http');

/**
 * Class Healthchecks
 *
 * @copyright (c) 2017 Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Healthchecks {
    static public function all() {
        $checks = [];
        $checks = array_merge(Healthchecks::environment(), $checks);
        $checks = array_merge(Healthchecks::configFiles(), $checks);
        $checks = array_merge(Healthchecks::core(), $checks);
        $checks = array_merge(Healthchecks::ssl(), $checks);
        $checks = array_merge(Healthchecks::database(), $checks);
        $checks = array_merge(Healthchecks::gpg(), $checks);
        $checks = array_merge(Healthchecks::application(), $checks);
        $checks = array_merge(Healthchecks::devTools(), $checks);
        return $checks;
    }

/**
 * Return core checks:
 * - phpVersion: php version is superior to 5.2.8
 * - pcre: unicode support
 * - tmpWritable: the TMP directory is writable for the current user
 *
 * @return array
 */
    static public function environment() {
        $checks['environment']['phpVersion'] = (version_compare(PHP_VERSION, '5.2.8', '>='));
        $checks['environment']['pcre'] = (Validation::alphaNumeric('cakephp'));
        $checks['environment']['tmpWritable'] = is_writable(TMP);
        $checks['environment']['imgPublicWritable'] = is_writable(IMAGES . DS . 'public');
        return $checks;
    }

/**
 * Return config file checks:
 * - configFiles.core true if file is present
 * - configFiles.app true if file is present
 * - configFiles.database true if file is present
 * - configFiles.email true if file is present
 *
 * @return array
 */
    static public function configFiles() {
        $files = ['core', 'app', 'database', 'email'];
        $checks = [];
        foreach($files as $file) {
            $checks['configFile'][$file] = (file_exists(APP . 'Config' . DS . $file . '.php'));
        }
        return $checks;
    }

/**
 * Check core file configuration
 * - cache: settings are set
 * - debugDisabled: the core.debug is set to 0
 * - salt: true if non default salt is used
 * - cipherSeed: true if non default cipherSeed is used
 *
 * @return array
 */
    static public function core() {
        $settings = Cache::settings();
        $checks['core']['cache'] = (!empty($settings));
        $checks['core']['debugDisabled'] = (Configure::read('debug') === 0);
        $checks['core']['salt'] = (Configure::read('Security.salt') !== 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');
        $checks['core']['cipherSeed'] = (Configure::read('Security.cipherSeed') !== '76859309657453542496749683645');
        $checks['core']['fullBaseUrl'] = (Configure::read('App.fullBaseUrl') !== 'http://localhost');
        $checks['core']['validFullBaseUrl'] = Validation::url(Configure::read('App.fullBaseUrl'), true);
        $checks['core']['info']['fullBaseUrl'] = Configure::read('App.fullBaseUrl');

        // Check if the URL is reachable
        $checks['core']['fullBaseUrlReachable'] = false;
        try {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET'
                ],
                'ssl' => [
                    'verify_peer'      => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';
            $response = @file_get_contents($url, false, $context);
            if(isset($response)) {
                $json = json_decode($response);
                if(isset($json->body)) {
                    $checks['core']['fullBaseUrlReachable'] = ($json->body === 'OK');
                }
            }
        } catch(Exception $e) {
        }

        return $checks;
    }

/**
 * Return database checks:
 * - connect:  can connect to the detabase
 * - tablesPrefixes: not using tablesPrefix
 * - tableCount: at least one table is present
 * - info.tableCount: number of tables installed
 * - defaultContent: some default contennt (4 roles)
 *
 * @return array
 */
    static public function database() {
        // init results to false by default
        $checks = [];
        $cases = ['connect', 'supportedBackend', 'tablesPrefix', 'tablesCount', 'defaultContent'];
        foreach ($cases as $case) {
            $checks['database'][$case] = false;
        }

        // No point to check anything if config file is not present
        $config = Healthchecks::configFiles();
        if(!$config['configFile']['database'])  {
            return $checks;
        }

        // Check config file content
        include_once APP . 'Config' . DS . 'database.php';
        $db = new DATABASE_CONFIG();
        if($db->default['datasource'] == 'Database/Mysql') {
            $checks['database']['supportedBackend'] = true;
        }
        if($db->default['prefix'] == '') {
            // Database prefix are not supported
            // https://github.com/passbolt/passbolt_api/issues/56
            $checks['database']['tablesPrefix'] = true;
        }

        // Check if can connect to database
        try {
            App::uses('ConnectionManager', 'Model');
            ConnectionManager::getDataSource('default');
            $checks['database']['connect'] = true;
        } catch (Exception $connectionError) {
            return $checks;
        }

        // Check if tables are present
        $checks['database']['info']['tablesCount'] = 0;
        try {
            $User = Common::getModel('User');
            $tables = $User->query('show tables;');
            if( isset($tables) && sizeof($tables)) {
                $checks['database']['tablesCount'] = (sizeof($tables) > 0);
                $checks['database']['info']['tablesCount'] = sizeof($tables);
            }
        } catch (Exception $connectionError) {
            return $checks;
        }

        // Check if some default data is present
        // We only check the number of roles
        try {
            $Role = Common::getModel('Role');
            $i = $Role->find('count');
            $checks['database']['defaultContent'] = ($i > 3);
        } catch (Exception $e) {
            return $checks;
        }

        return $checks;
    }

/**
 * Application checks
 * - latestVersion: true if using latest version
 * - schema: schema up to date no need to do a migration
 * - info.remoteVersion
 * - sslForce: enforcing the use of SSL
 * - seleniumDisabled: true if selenium API is disabled
 * - registrationClosed: true if registration is not open
 * - jsProd: true if using minified/concatenated javascript
 *
 * @return array $checks
 * @access private
 */
    static public function application() {
        try {
            $checks['application']['info']['remoteVersion'] = Migration::getLatestTagName();
            $checks['application']['latestVersion'] = Migration::isLatestVersion();
        } catch (exception $e) {
            $checks['latestVersion'] = null;
        }
        $checks['application']['schema'] = !Migration::needMigration();
        $checks['application']['robotsIndexDisabled'] = !Configure::read('App.meta.robots.index');
        $checks['application']['sslForce'] = Configure::read('App.ssl.force');
        $checks['application']['sslFullBaseUrl'] = !(strpos(Configure::read('App.fullBaseUrl'),'https') === false);
        $checks['application']['seleniumDisabled'] = !Configure::read('App.selenium.active');
        $checks['application']['registrationClosed'] = !Configure::read('App.registration.public');
        $checks['application']['jsProd'] = (Configure::read('App.js.build') === 'production');

        $checks = array_merge(Healthchecks::appUser(), $checks);
        return $checks;
    }

/**
 * Gpg checks
 *
 * @return array $checks
 * @access private
 */
     static public function gpg() {
         $checks['gpg']['lib'] = (class_exists('gnupg'));
         $checks['gpg']['gpgKey'] = (Configure::read('GPG.serverKey.fingerprint') != null);
         $checks['gpg']['gpgKeyDefault'] = (Configure::read('GPG.serverKey.fingerprint') != '2FC8945833C51946E937F9FED47B0811573EE67E');
         $checks['gpg']['gpgHome'] = (getenv('GNUPGHOME') !== false);
         $checks['gpg']['gpgHomeWritable'] = false;
         if($checks['gpg']['gpgHome']) {
             $checks['gpg']['info']['gpgHome'] = getenv('GNUPGHOME');
             $checks['gpg']['gpgHomeWritable'] = is_writable(getenv('GNUPGHOME'));
         }
         return $checks;
     }

/**
 * Check that users are set in the database
 * - app.adminCount there is at least an admin in the database
 *
 * @access private
 * @return array
 */
    static public function appUser() {
        $checks['application']['adminCount'] = false;

        // no point checking for records if can not connect
        $checks = Healthchecks::database();
        if (!$checks['database']['connect']) {
            return $checks;
        }

        // check number of admin user
        $User = Common::getModel('User');
        try {
            $i = $User->find('count', [
                'conditions' => ['Role.name' => Role::ADMIN],
                'contain' => ['Role' => [
                    'fields' => [
                        'Role.id',
                        'Role.name'
                    ]
                ]]
            ]);
            $checks['application']['adminCount'] = ($i > 0);
        } catch(Exception $e) {
        }

        return $checks;
    }

/**
 * Development tools check
 * - devTools.debugKit debugkit plugin is present
 * - devTools.phpunit phpunit is installed
 * - devTools.phpunitVersion phpunit version == 3.7.38
 *
 * @return array
 * @access private
 */
    static public function devTools() {
        $checks['devTools']['phpunit'] = (class_exists('PHPUnit_Runner_Version'));
        $checks['devTools']['phpunitVersion'] = ($checks['devTools']['phpunit'] && PHPUnit_Runner_Version::id() === '3.7.38');
        $checks['devTools']['info']['phpunitVersion'] = PHPUnit_Runner_Version::id();
        return $checks;
    }

/**
 * SSL certs check
 * - devTools.debugKit debugkit plugin is present
 * - devTools.phpunit phpunit is installed
 * - devTools.phpunitVersion phpunit version == 3.7.38
 *
 * @return array
 * @access private
 */
    static public function ssl() {
        $checks['ssl'] = [
            'peerValid' => false,
            'hostValid' => false,
            'notSelfSigned' => false
        ];

        // No point to check anything if Core file is not valid
        $config = Healthchecks::core();
        if (!$config['core']['fullBaseUrlReachable'])  {
            return $checks;
        }
        $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';

        // Check peer
        try {
            $HttpSocket = new HttpSocket(array(
                'ssl_verify_peer' => true,
                'ssl_verify_host' => false,
                'ssl_allow_self_signed' => true
            ));
            $response = $HttpSocket->get($url);
            $checks['ssl']['peerValid'] = $response->isOk();
        } catch(Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();
            return $checks;
        }

        // Check host
        try {
            $HttpSocket = new HttpSocket(array(
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => true
            ));
            $response = $HttpSocket->get($url);
            $checks['ssl']['hostValid'] = $response->isOk();
        } catch(Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();
            return $checks;
        }

        // Check self-signed
        try {
            $HttpSocket = new HttpSocket(array(
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => false
            ));
            $response = $HttpSocket->get($url);
            $checks['ssl']['notSelfSigned'] = $response->isOk();
        } catch(Exception $e) {
            return $checks;
        }

        return $checks;
    }
}