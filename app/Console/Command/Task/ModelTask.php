<?php

/**
 * Insert Model Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
			// Get fields to validate. (Sometimes we need exceptions).
			$validationFields = method_exists($this, 'getValidationFields') ? ['fieldList' => $this->getValidationFields($item)] : [];
			if (!$Model->validates($validationFields)) {
				var_dump($Model->validationErrors);
			}
			$instance = $Model->save($item, false);
			if (!$instance) {
				$this->out('<error>Unable to insert ' . pr($item[$this->model]) . '</error>');
				die;
			}
		}
	}

}
