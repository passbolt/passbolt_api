<?php
/**
 * Insert Secret Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.SecretTask
 * @since        version 2.12.11
 */

require_once(APP_DIR . DS  . 'Plugin' . DS . 'DataExtras' . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Secret', 'Model');
App::uses('Resource', 'Model');
App::uses('User', 'Model');

class SecretTask extends ModelTask {

	public $model = 'Secret';

	public function execute() {
		$Model = ClassRegistry::init($this->model);
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->set($item);
			if (!$Model->validates()) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save();
			if (!$instance) {
				$this->out('<error>Unable to insert ' . $item[$this->model]['name'] . '</error>');
			}
		}
	}

	/**
	 * Get Dummy Secret Data.
	 *
	 * This secret was encrypted with the dummy public key, also located in the repository.
	 *
	 * @return string
	 */
	protected function getDummySecretData() {
		$s = '-----BEGIN PGP MESSAGE-----
Version: OpenPGP.js v0.7.2
Comment: http://openpgpjs.org

wcBMAwvNmZMMcWZiAQf+MIoTnUl1TZ37Smc7vN+pZa1eykxiBoL9qyLMIoXO
/ICcFVLB21X4snN7C9Kj7tZAh+K1n4C1BPcokb4lLjtrUUxxeb4CEmszutaQ
67eyuIi2oUBh4YqERexAcC89xzLNVeHa7X4LcUltdmydyut9BZq6vh9OGxKs
l5H89H5CYnSVgY9uEGQKJViVNhdTCtSOvYVG3thpSnfrv5V4kPxBPeI3TRX1
izMvb9XXCGgmudF6H+NxzyY9OqnDzk4sVYtw4LD+tYSebYulyZz4KyFQIBVN
O2Dhm2LikecJUj154HuN+b1ZiFFkugsV6vk2LTIC58/jqMypGZ1UvEkEE8J2
WtJfAV3hQYtt9278GPXH69KgwRTfSLOt9FDAfa/Gtpad1USRe3aZOnoTownv
BIueB4S3TDNlIgJ0oicIFa++GghK+QWlnMgvtDJRZfb7wmSToYQwXcZei7bW
4xIEkjyjRes=
=MjPm
-----END PGP MESSAGE-----';
		return $s;
	}

	/**
	 * Get all Secret data.
	 * @return array
	 */
	protected function getData() {
		$Resource = Common::getModel('Resource');
		$User = Common::getModel('User');
		$rs = $Resource->find('all');
		$us = $User->find('all');

		// Insertion for all users who can access to available resources.
		// We insert dummy data, same secret for everyone.
		foreach($rs as $r) {
			foreach ($us as $u) {
				$isAuthorized = $Resource->isAuthorized($r['Resource']['id'], PermissionType::READ, $u['User']['id']);
				if ($isAuthorized) {
					$s[] = array('Secret'=>array(
						'id' => Common::uuid(),
						'user_id' => $u['User']['id'],
						'resource_id' => $r['Resource']['id'],
						'data' => $this->getDummySecretData(),
						'created' => '2012-12-24 03:34:40',
						'modified' => '2012-12-24 03:34:40',
						'created_by' => $u['User']['id'],
						'modified_by' => $u['User']['id']
					));
				}
			}
		}
		return $s;
	}
}