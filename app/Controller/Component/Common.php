<?php
/**
 * Common Component
 * This class serves as a space for convenience functions (mostly static)
 * that need to be globally available within this application.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('MigrationVersion', 'Migrations.Lib');

class Common extends Object {
	protected $_remoteTagName;

/**
 * Instanciate and return the reference to a model object
 *
 * @param string $model name
 * @param bool $create init the model if not found in the class registry
 * @return model $ModelObj
 */
	public static function getModel($model, $create = false) {
		if (ClassRegistry::isKeySet($model) && !$create) {
			$ModelObj = ClassRegistry::getObject($model);
		} else {
			$ModelObj = ClassRegistry::init($model);
		}
		return $ModelObj;
	}

/**
 * Return a UUID v4 or v5
 *
 * @param string $seed used to create UUID v5
 * @return string UUID
 */
	public static function uuid($seed = null) {
		$pattern = '/^(.{8})(.{4})(.{1})(.{3})(.{1})(.{3})(.{12})$/';

		if (isset($seed)) {
			$string = substr(sha1($seed), 0, 32);
			$replacement = '${1}-${2}-3${4}-a${6}-${7}'; // v5
		} else {
			$string = bin2hex(openssl_random_pseudo_bytes(16));
			$replacement = '${1}-${2}-4${4}-a${6}-${7}'; // v4
		}
		return preg_replace($pattern, $replacement, $string);
	}

/**
 * Return true if a given string is a UUID
 *
 * @param string $str the UUID to be checked
 * @return bool true if str is a UUID
 */
	public static function isUuid($str) {
		return is_string($str) && preg_match('/^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[0-5][a-fA-F0-9]{3}-[089aAbB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$/',
			$str);
	}

/**
 * Check if the app or plugins need a database migration
 *
 * @param string $type 'all', 'app' or a supported plugin name
 * @throws Exception if migration type is not whitelisted
 * @return boolean
 */
	public static function needMigration($type = 'all') {
		$allowedMigrations = Configure::read('App.migrations');
		if($type == 'all') {
			$migrations = $allowedMigrations;
		} else {
			if(in_array($type, $allowedMigrations)) {
				$migrations[] = $type;
			} else {
				throw new Exception(__('Migration not found or not allowed: ') . $type);
			}
		}

		$migration = new MigrationVersion();
		foreach($migrations as $module) {
			$mapping = $migration->getMapping($module);
			$currentVersion = $migration->getVersion($module);
			if ($mapping) {
				$lastVersion = max(array_keys($mapping));
				if ($lastVersion - $currentVersion != 0) {
					return true;
				}
			}
		}
		return false;
	}
/**
 * Return true if the current installed version match the latest official one
 *
 * @return bool true if installed version is the latest
 */
	public static function isLatestVersion() {
		$remoteVersion = ltrim(Common::getLatestTagName(), 'v');
		$localVersion = ltrim(Configure::read('App.version.number'), 'v');
		return version_compare($localVersion, $remoteVersion, ">=");
	}

/**
 * Return the current master version according to the official passbolt repository
 *
 * @throws Exception if the github repository is not reachable
 * @return string tag name such as 'v1.0.1'
 */
	public static function getLatestTagName() {
		$remoteTagName = Configure::read('App.version.remote');
		if(is_null($remoteTagName)) {
			$url = 'https://api.github.com/repos/passbolt/passbolt/tags';
			$HttpSocket = new HttpSocket();
			$results = $HttpSocket->get($url);
			$tags = (json_decode($results->body));

			if (!isset($tags[0]) || !property_exists($tags[0], 'name')) {
				throw new Exception(__('Could not read tag information on github repository'));
			}
			$remoteTagName = $tags[0]->name;
			Configure::write('App.version.remote', $remoteTagName);
		}
		return $remoteTagName;
	}
}
