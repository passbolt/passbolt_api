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
namespace App\Utility;

use App\Model\Entity\Role;
use App\Utility\Healthchecks\DatabaseHealthchecks;
use App\Utility\Healthchecks\GpgHealthchecks;
use App\Utility\Healthchecks\SslHealthchecks;
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

class Healthchecks
{
    /**
     * Run all healthchecks
     *
     * @return array
     */
    public static function all()
    {
        $checks = [];
        $checks = Healthchecks::environment($checks);
        $checks = Healthchecks::configFiles($checks);
        $checks = Healthchecks::core($checks);
        $checks = Healthchecks::ssl($checks);
        $checks = Healthchecks::database($checks);
        $checks = Healthchecks::gpg($checks);
        $checks = Healthchecks::application($checks);

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
     * @param array $checks List of checks
     * @return array
     * @access private
     */
    public static function application($checks = null)
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
     * @param array $checks List of checks
     * @return array
     */
    public static function appUser($checks = null)
    {
        // no point checking for records if can not connect
        $checks = Healthchecks::database();
        $checks['application']['adminCount'] = false;
        if (!$checks['database']['connect']) {
            return $checks;
        }

        // check number of admin user
        $User = TableRegistry::getTableLocator()->get('Users');
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
     * @param array $checks List of checks
     * @return array
     */
    public static function configFiles($checks = null)
    {
        $files = ['app', 'passbolt'];
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
     * @param array $checks List of checks
     * @return array
     */
    public static function core($checks = [])
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
     * @param array $checks List of checks
     * @return array
     */
    public static function database($checks = [])
    {
        return DatabaseHealthchecks::all($checks);
    }

    /**
     * Return core checks:
     * - phpVersion: php version is superior to 7.0
     * - pcre: unicode support
     * - tmpWritable: the TMP directory is writable for the current user
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function environment($checks = [])
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
     * @param array $checks List of checks
     * @return array
     */
    public static function gpg($checks = [])
    {
        return GpgHealthchecks::all($checks);
    }

    /**
     * SSL certs check
     * - ssl.peerValid
     * - ssl.hostValid
     * - ssl.notSelfSigned
     *
     * @param array $checks List of checks
     * @return array
     */
    public static function ssl($checks = [])
    {
        return SslHealthchecks::all($checks);
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
     * @return array
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
                //'burzum_file_storage_phinxlog', // dropped in v2.8
                //'email_queue_phinxlog',
                'phinxlog',
            ]);
        }

        return $tables;
    }
}
