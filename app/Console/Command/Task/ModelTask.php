<?php

/**
 * Insert Model Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Console.Command.Task.ModelTask
 * @since        version 2.12.11
 */
class ModelTask extends AppShell {

	public function execute() {
		$User = ClassRegistry::init('User');
		$user = $User->findByUsername('root@passbolt.com');
		$User->setActive($user);
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
				$this->out('<error>Unable to insert ' . pr($item[$this->model]) . '</error>');
				die;
			}
		}
	}

}
