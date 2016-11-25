<?php
/**
 * Anonymous Statistic Model
 *
 * @copyright (c) 2015-present passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('Resource', 'Model');
App::uses('User', 'Model');
App::uses('ControllerLog', 'Model');
App::uses('HttpSocket', 'Network/Http');

class AnonymousStatistic extends AppModel {

	public $useTable = false;

/**
 * Config file name.
 */
	const ConfigFile = 'anonymous_statistics';

/**
 * Statistics context (install or update).
 */
	const CONTEXT_INSTALL = 'install';
	const CONTEXT_UPDATE = 'update';

/**
 * Find Instance statistics.
 * @return array
 *   * passwords_count (int) number of passwords
 *   * users_count (int) number of users
 *   * logs_count (int) number of logs
 *   * version (string) version number in format x.y.z
 */
	public function findInstanceStatistics() {
		$Resource = Common::getModel('Resource');
		$User = Common::getModel('User');
		$ControllerLog = Common::getModel('ControllerLog');

		$resourcesCount = $Resource->find('count');
		$usersCount = $User->find('count');
		$logsCount = $ControllerLog->find('count');

		return [
			'passwords_count' => $resourcesCount,
			'users_count' => $usersCount,
			'logs_count' => $logsCount,
			'version' => Configure::read('App.version.number'),
		];
	}

/**
 * Force reloading configuration file.
 */
	public static function reloadConfigFile() {
		if (file_exists(APP . 'Config' . DS . self::ConfigFile .'.php')) {
			Configure::load(self::ConfigFile); // anonymous statistics config
		}
	}

/**
 * Write config file.
 *
 * @param $instanceId
 * @param $send
 * @return int
 */
	public static function writeConfigFile($instanceId, $send) {
		// Build config file path.
		$configFilePath = APP . 'Config' . DS . self::ConfigFile . '.php';

		// Config file content.
		$v = new View();
		$v->set(['instanceId' => $instanceId, 'send' => $send]);
		$configFileContent = $v->render('Elements/config/anonymous_statistics' , 'ajax');

		// Write file.
		$write = file_put_contents($configFilePath, $configFileContent);

		return $write;
	}

/**
 * Send statistics to server.
 * @param $context
 *  install or update
 * @param $data
 *  data to be sent. if not provided, will be retrieved automatically.
 * @return bool $response
 */
	public function send($context = self::CONTEXT_INSTALL) {

		// Get statistics data.
		$data = $this->findInstanceStatistics();

		$postData = array(
			'InstallStatistic' => [
				'instance_id' => Configure::read('AnonymousStatistics.instanceId'),
				'context' => $context,
				'passwords_count' => $data['passwords_count'],
				'users_count' => $data['users_count'],
				'logs_count' => $data['logs_count'],
				'version'   => $data['version'],
			],
		);

		$HttpSocket = new HttpSocket();
		$entryPoint = Configure::read('AnonymousStatistics.url');
		try {
			$response = $HttpSocket->post($entryPoint, $postData);
		}
		catch(Exception $e) {
			$this->log("Could not send anonymous statistics. Can't reach server $entryPoint");
		}

		return $response;
	}
}