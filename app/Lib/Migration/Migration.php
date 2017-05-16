<?php
/**
 * Migration Component
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('HttpSocket', 'Network/Http');
App::uses('MigrationVersion', 'Migrations.Lib');

class Migration extends CakeObject {
	protected $_remoteTagName;

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
		$remoteVersion = ltrim(Migration::getLatestTagName(), 'v');
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
			$url = 'https://api.github.com/repos/passbolt/passbolt_api/tags';
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
