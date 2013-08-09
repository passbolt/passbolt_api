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

        // The line below line avoids to have zero not accepted as auto_increment in the database
        $Model->query('SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
		
		$data = $this->getData();
		foreach ($data as $item) {
			$Model->create();
            $Model->query('SET SESSION SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"');
			$Model->save($item);
		}
	}

}
