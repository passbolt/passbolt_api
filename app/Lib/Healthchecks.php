<?php
App::uses('Validation', 'Utility');

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
        $checks = array_merge(Healthchecks::database(), $checks);
        return $checks;
    }

/**
 * Return core checks:
 * - phpVersion: php version is superior to 5.2.8
 * - PCRE: unicode support
 * - tmpWritable: the TMP directory is writable for the current user
 *
 * @return array
 */
    static public function environment() {
        $checks['environment']['phpVersion'] = (version_compare(PHP_VERSION, '5.2.8', '>='));
        $checks['environment']['PCRE'] = (Validation::alphaNumeric('cakephp'));
        $checks['environment']['tmpWritable'] = is_writable(TMP);
        $checks['environment']['imgPublicWritable'] = is_writable(IMAGES . DS . 'public');

        return $checks;
    }

/**
 * Return config file checks:
 * - configFiles.core
 * - configFiles.app
 * - configFiles.database
 * - configFiles.email
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
 */
    static public function core() {
        $settings = Cache::settings();
        $checks['core']['cache'] = (!empty($settings));
        $checks['core']['debugDisabled'] = Configure::read('debug') == 0;
        return $checks;
    }

/**
 * Return database checks:
 * - dbConfigFile: a database config file exist
 *
 * @return array
 */
    static public function database() {
        $config = Healthchecks::configFiles();
        $checks['database']['connect'] = false;

        if($config['configFile']['database'])  {
            App::uses('ConnectionManager', 'Model');
            try {
                ConnectionManager::getDataSource('default');
                $checks['database']['connect'] = true;
            } catch (Exception $connectionError) {
            }
        }
        return $checks;
    }
}