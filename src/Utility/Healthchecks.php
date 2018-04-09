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
namespace App\Utility;

use App\Model\Entity\Role;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Database\Exception as DatabaseException;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class Healthchecks
{
    /**
     * Run all healtchecks
     *
     * @return array
     */
    public static function all()
    {
        $checks = [];
        $checks = array_merge(Healthchecks::environment(), $checks);
        $checks = array_merge(Healthchecks::configFiles(), $checks);
        $checks = array_merge(Healthchecks::core(), $checks);
        $checks = array_merge(Healthchecks::ssl(), $checks);
        $checks = array_merge(Healthchecks::database(), $checks);
        $checks = array_merge(Healthchecks::gpg(), $checks);
        $checks = array_merge(Healthchecks::application(), $checks);

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
    public static function application()
    {
        try {
            $checks['application']['info']['remoteVersion'] = Migration::getLatestTagName();
            $checks['application']['latestVersion'] = Migration::isLatestVersion();
        } catch (\Exception $e) {
            $checks['application']['info']['remoteVersion'] = 'undefined';
            $checks['application']['latestVersion'] = null;
        }
        try {
            $checks['application']['schema'] = !Migration::needMigration();
        } catch (\Exception $e) {
            // Cannot connect to the database
            $checks['application']['schema'] = null;
        }
        $checks['application']['robotsIndexDisabled'] = (strpos(Configure::read('passbolt.meta.robots'), 'noindex') !== false);
        $checks['application']['sslForce'] = Configure::read('passbolt.ssl.force');
        $checks['application']['sslFullBaseUrl'] = !(strpos(Configure::read('App.fullBaseUrl'), 'https') === false);
        $checks['application']['seleniumDisabled'] = !Configure::read('passbolt.selenium.active');
        $checks['application']['registrationClosed'] = !Configure::read('passbolt.registration.public');
        $checks['application']['jsProd'] = (Configure::read('passbolt.js.build') === 'production');
        $checks['application']['emailNotificationEnabled'] = !(preg_match('/false/', json_encode(Configure::read('passbolt.email.send'))) === 1);

        $checks = array_merge(Healthchecks::appUser(), $checks);

        return $checks;
    }

    /**
     * Check that users are set in the database
     * - app.adminCount there is at least an admin in the database
     *
     * @access private
     * @return array
     */
    public static function appUser()
    {
        // no point checking for records if can not connect
        $checks = Healthchecks::database();
        $checks['application']['adminCount'] = false;
        if (!$checks['database']['connect']) {
            return $checks;
        }

        // check number of admin user
        $User = TableRegistry::get('Users');
        try {
            $i = $User->find('all')
                ->contain(['Roles'])
                ->where(['Roles.name' => Role::ADMIN])
                ->count();

            $checks['application']['adminCount'] = ($i > 0);
        } catch (Exception $e) {
        }

        return $checks;
    }

    /**
     * Return config file checks:
     * - configFile.app true if file is present, false otherwise
     *
     * @return array
     */
    public static function configFiles()
    {
        $files = ['app', 'passbolt'];
        $checks = [];
        foreach ($files as $file) {
            $checks['configFile'][$file] = (file_exists(CONFIG . $file . '.php'));
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
    public static function core()
    {
        $settings = Cache::getConfig('_cake_core_');
        $checks['core']['cache'] = (!empty($settings));
        $checks['core']['debugDisabled'] = (Configure::read('debug') === false);
        $checks['core']['salt'] = (Configure::read('Security.salt') !== '__SALT__');
        $checks['core']['fullBaseUrl'] = (Configure::read('App.fullBaseUrl') !== null);
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
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';
            $response = @file_get_contents($url, false, $context); // phpcs:ignore
            if (isset($response)) {
                $json = json_decode($response);
                if (isset($json->body)) {
                    $checks['core']['fullBaseUrlReachable'] = ($json->body === 'OK');
                }
            }
        } catch (Exception $e) {
        }

        return $checks;
    }

    /**
     * Return database checks:
     * - connect: can connect to the database
     * - tablesPrefixes: not using tablesPrefix
     * - tableCount: at least one table is present
     * - info.tableCount: number of tables installed
     * - defaultContent: some default content (4 roles)
     *
     * @return array
     */
    public static function database()
    {
        // Do not use default connection when test are running
        // Otherwise tables may be dropped
        $connectionName = 'default';
        if (defined('TEST_IS_RUNNING') && TEST_IS_RUNNING) {
            $connectionName = 'test';
        }

        // init results to false by default
        $checks = [];
        $cases = ['connect', 'supportedBackend', 'tablesPrefix', 'tablesCount', 'defaultContent'];
        foreach ($cases as $case) {
            $checks['database'][$case] = false;
        }
        $checks['database']['info']['tablesCount'] = 0;

        // Check if can connect to database
        try {
            $connection = ConnectionManager::get($connectionName);
            $connection->connect();
            $checks['database']['connect'] = true;
        } catch (MissingConnectionException $connectionError) {
            $errorMsg = $connectionError->getMessage();
            if (method_exists($connectionError, 'getAttributes')) {
                $attributes = $connectionError->getAttributes();
                if (isset($errorMsg['message'])) {
                    $checks['database']['info'] .= ' ' . $attributes['message'];
                }
            }

            return $checks;
        }

        // Check driver
        $config = $connection->config();
        if ($config['driver'] === 'Cake\Database\Driver\Mysql') {
            $checks['database']['supportedBackend'] = true;
        }

        // Check if tables are present
        try {
            $connection = ConnectionManager::get($connectionName);
            $tables = $connection->execute('show tables')->fetchAll('assoc');

            if (isset($tables) && count($tables)) {
                $checks['database']['tablesCount'] = (count($tables) > 0);
                $checks['database']['info']['tablesCount'] = count($tables);
            }
        } catch (DatabaseException $connectionError) {
            return $checks;
        }

        // Check if some default data is present
        // We only check the number of roles
        try {
            $Roles = TableRegistry::get('Roles');
            if (!empty($Roles)) {
                $i = $Roles->find('all')->count();
                $checks['database']['defaultContent'] = ($i > 3);
            }
        } catch (DatabaseException $e) {
            return $checks;
        }

        return $checks;
    }

    /**
     * Return core checks:
     * - phpVersion: php version is superior to 7.0
     * - pcre: unicode support
     * - tmpWritable: the TMP directory is writable for the current user
     *
     * @return array
     */
    public static function environment()
    {
        $checks['environment']['phpVersion'] = (version_compare(PHP_VERSION, '7.0', '>='));
        $checks['environment']['pcre'] = (Validation::alphaNumeric('passbolt'));
        $checks['environment']['mbstring'] = extension_loaded('mbstring');
        $checks['environment']['gnupg'] = extension_loaded('gnupg');
        $checks['environment']['intl'] = extension_loaded('intl');
        $checks['environment']['image'] = (extension_loaded('gd') || extension_loaded('imagick'));
        $checks['environment']['tmpWritable'] = self::_checkRecursiveDirectoryWritable(TMP);
        $checks['environment']['imgPublicWritable'] = self::_checkRecursiveDirectoryWritable(IMAGES . 'public/');
        $checks['environment']['logWritable'] = is_writable(LOGS);

        return $checks;
    }

    /**
     * Gpg checks
     *
     * @return array $checks
     * @access private
     */
    public static function gpg()
    {
        // Check gpg php module is installed and enabled
        $checks['gpg']['lib'] = (class_exists('gnupg'));

        // Check fingerprint is set
        $checks['gpg']['gpgKey'] = (Configure::read('passbolt.gpg.serverKey.fingerprint') != null);
        $checks['gpg']['gpgKeyNotDefault'] = (Configure::read('passbolt.gpg.serverKey.fingerprint') != '2FC8945833C51946E937F9FED47B0811573EE67E');

        // If no keyring location has been set, use the default one ~/.gnupg.
        $gnupgHome = getenv('GNUPGHOME');
        if (empty($gnupgHome)) {
            $uid = posix_getuid();
            $user = posix_getpwuid($uid);
            $gnupgHome = $user['dir'] . '/.gnupg';
        }

        $checks['gpg']['info']['gpgHome'] = $gnupgHome;
        $checks['gpg']['gpgHome'] = file_exists($checks['gpg']['info']['gpgHome']);
        $checks['gpg']['gpgHomeWritable'] = is_writable($checks['gpg']['info']['gpgHome']);

        // Check key file exist and are readable
        $checks['gpg']['gpgKeyPublic'] = (Configure::read('passbolt.gpg.serverKey.public') != null);
        $checks['gpg']['gpgKeyPublicReadable'] = is_readable(Configure::read('passbolt.gpg.serverKey.public'));
        $checks['gpg']['gpgKeyPrivate'] = (Configure::read('passbolt.gpg.serverKey.private') != null);
        $checks['gpg']['info']['gpgKeyPrivate'] = Configure::read('passbolt.gpg.serverKey.private');
        $checks['gpg']['gpgKeyPrivateReadable'] = is_readable(Configure::read('passbolt.gpg.serverKey.private'));

        // Check that the private key match the fingerprint
        $checks['gpg']['gpgKeyPrivateFingerprint'] = false;
        $checks['gpg']['gpgKeyPublicFingerprint'] = false;
        $checks['gpg']['gpgKeyPublicEmail'] = false;
        if ($checks['gpg']['gpgKeyPublicReadable'] && $checks['gpg']['gpgKeyPrivateReadable'] && $checks['gpg']['gpgKey']) {
            $gpg = new Gpg();
            $privateKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.private'));
            $privateKeyInfo = $gpg->getKeyInfo($privateKeydata);
            if ($privateKeyInfo['fingerprint'] === Configure::read('passbolt.gpg.serverKey.fingerprint')) {
                $checks['gpg']['gpgKeyPrivateFingerprint'] = true;
            }
            $publicKeydata = file_get_contents(Configure::read('passbolt.gpg.serverKey.public'));
            $publicKeyInfo = $gpg->getPublicKeyInfo($publicKeydata);
            if ($publicKeyInfo['fingerprint'] === Configure::read('passbolt.gpg.serverKey.fingerprint')) {
                $checks['gpg']['gpgKeyPublicFingerprint'] = true;
            }
            $Gpgkeys = TableRegistry::get('Gpgkeys');
            $checks['gpg']['gpgKeyPublicEmail'] = $Gpgkeys->uidContainValidEmailRule($publicKeyInfo['uid']);
        }

        // Check that the public key is present in the keyring.
        $checks['gpg']['gpgKeyPublicInKeyring'] = false;
        if ($checks['gpg']['gpgHome'] && Configure::read('passbolt.gpg.serverKey.fingerprint')) {
            $gpg = new Gpg();
            $keyInfo = $gpg->getKeyInfoFromKeyring(Configure::read('passbolt.gpg.serverKey.fingerprint'));
            if (!empty($keyInfo)) {
                if ($keyInfo[0]['can_sign'] && $keyInfo[0]['can_encrypt']) {
                    $checks['gpg']['gpgKeyPublicInKeyring'] = true;
                }
            }
        }

        // Check that the server can be used for encrypting/decrypting
        $checks['gpg']['canDecrypt'] = false;
        $checks['gpg']['canEncrypt'] = false;
        $checks['gpg']['canEncryptSign'] = false;
        $checks['gpg']['canDecryptVerify'] = false;
        $checks['gpg']['canVerify'] = false;
        $checks['gpg']['canSign'] = false;

        if ($checks['gpg']['gpgKeyPublicInKeyring']) {
            $_gpg = new \gnupg();
            $_gpg->addencryptkey(Configure::read('passbolt.gpg.serverKey.fingerprint'));
            $_gpg->addsignkey(Configure::read('passbolt.gpg.serverKey.fingerprint'), Configure::read('passbolt.gpg.serverKey.passphrase'));
            $messageToEncrypt = 'test message';
            $encryptedMessage = '';

            // Try to encrypt a message.
            try {
                $encryptedMessage = $_gpg->encrypt($messageToEncrypt);
                if ($encryptedMessage !== false) {
                    $checks['gpg']['canEncrypt'] = true;
                }
            } catch (Exception $e) {
            }

            // Try to sign a message.
            try {
                $signature = $_gpg->sign($messageToEncrypt);
                if ($signature !== false) {
                    $checks['gpg']['canSign'] = true;
                }
            } catch (Exception $e) {
            }

            // Try to encrypt and sign a message.
            try {
                $encryptedMessage2 = $_gpg->encryptsign($messageToEncrypt);
                if ($encryptedMessage2 !== false) {
                    $checks['gpg']['canEncryptSign'] = true;
                }
            } catch (Exception $e) {
            }

            // Try to decrypt the unsigned message.
            if ($checks['gpg']['canEncrypt']) {
                $_gpg->adddecryptkey(Configure::read('passbolt.gpg.serverKey.fingerprint'), Configure::read('passbolt.gpg.serverKey.passphrase'));

                try {
                    $decryptedMessage = $_gpg->decrypt($encryptedMessage);
                    if ($decryptedMessage === $messageToEncrypt) {
                        $checks['gpg']['canDecrypt'] = true;
                    }
                } catch (Exception $e) {
                }
            }

            // Try to decrypt and verify the signature of a message.
            if ($checks['gpg']['canDecrypt']) {
                $_gpg->adddecryptkey(Configure::read('passbolt.gpg.serverKey.fingerprint'), Configure::read('passbolt.gpg.serverKey.passphrase'));
                $decryptedMessage2 = '';

                try {
                    $_gpg->decryptverify($encryptedMessage2, $decryptedMessage2);
                    if ($decryptedMessage === $messageToEncrypt) {
                        $checks['gpg']['canDecryptVerify'] = true;
                    }
                } catch (Exception $e) {
                }
            }

            // Try to verify the signature of a message.
            if ($checks['gpg']['canDecryptVerify']) {
                $_gpg->adddecryptkey(Configure::read('passbolt.gpg.serverKey.fingerprint'), Configure::read('passbolt.gpg.serverKey.passphrase'));

                try {
                    $plaintext = "";
                    $info = $_gpg->verify($encryptedMessage2, false, $plaintext);
                    if ($info !== false && isset($info['fingerprint']) && Configure::read('passbolt.gpg.serverKey.fingerprint') == $info['fingerprint']) {
                        $checks['gpg']['canVerify'] = true;
                    }
                    $info = $_gpg->verify($signature, false, $encryptedMessage2);
                    if ($info !== false && isset($info[0]['fingerprint']) && Configure::read('passbolt.gpg.serverKey.fingerprint') == $info[0]['fingerprint']) {
                        $checks['gpg']['canVerify'] = true;
                    }
                } catch (Exception $e) {
                }
            }
        }

        return $checks;
    }

    /**
     * SSL certs check
     * - ssl.peerValid
     * - ssl.hostValid
     * - ssl.notSelfSigned
     *
     * @return array
     * @access private
     */
    public static function ssl()
    {
        $checks['ssl'] = [
            'peerValid' => false,
            'hostValid' => false,
            'notSelfSigned' => false
        ];

        // No point to check anything if Core file is not valid
        $config = Healthchecks::core();
        if (!$config['core']['fullBaseUrlReachable']) {
            return $checks;
        }
        $url = Configure::read('App.fullBaseUrl') . '/healthcheck/status.json';

        // Check peer
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => false,
                'ssl_allow_self_signed' => true
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['peerValid'] = $response->isOk();
        } catch (Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();

            return $checks;
        }

        // Check host
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => true
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['hostValid'] = $response->isOk();
        } catch (Exception $e) {
            $checks['ssl']['info'] = $e->getMessage();

            return $checks;
        }

        // Check self-signed
        try {
            $HttpSocket = new Client([
                'ssl_verify_peer' => true,
                'ssl_verify_host' => true,
                'ssl_allow_self_signed' => false
            ]);
            $response = $HttpSocket->get($url);
            $checks['ssl']['notSelfSigned'] = $response->isOk();
        } catch (Exception $e) {
            return $checks;
        }

        return $checks;
    }

    /**
     * Check that a directory and its content are writable
     *
     * @param string $path the directory path
     * @return bool
     */
    private static function _checkRecursiveDirectoryWritable($path)
    {
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path),
            \RecursiveIteratorIterator::SELF_FIRST
        );
        foreach ($iterator as $name => $fileInfo) {
            if (in_array($fileInfo->getFilename(), ['.', '..', 'empty'])) {
                continue;
            }
            if (!is_writable($name)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get schema tables list. (per version number).
     * @param int $version passbolt major version number.
     * @return array list of tables.
     */
    public static function getSchemaTables($version = 2)
    {
        // List of tables for passbolt v1.
        $tables = [
            'authentication_tokens',
            'comments',
            'email_queue',
            'favorites',
            'file_storage',
            'gpgkeys',
            'groups',
            'groups_users',
            'permissions',
            'profiles',
            'resources',
            'roles',
            'secrets',
            'user_agents',
            'users',
        ];

        // Extra tables for passbolt v2.
        if ($version == 2) {
            $tables = array_merge($tables, [
                'burzum_file_storage_phinxlog',
                'email_queue_phinxlog',
                'phinxlog',
            ]);
        }

        return $tables;
    }
}
