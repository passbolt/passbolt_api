<?php
/**
 * Insert Model Task
 * 
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.ModelTask
 * @since        version 2.12.11
 */
class ModelTask extends AppShell {

	public function execute() {
		$User = ClassRegistry::init('User');
		$kk = $User->findByUsername('root@passbolt.com');
		$User->setActive($kk);
		
		$Model = ClassRegistry::init($this->model);
		
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
			$Model->save($item);
		}
	}

}
