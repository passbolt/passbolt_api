<?php
/**
 * Insert Model Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::import('Model', 'User');
class ModelTask extends AppShell {

/**
 * Execute the task
 *
 * @return void
 */
	public function execute() {
		$Model = Common::getModel($this->model);

		// Set Db Connection according to what is provided in params.
//		if(isset($this->params['connection']) && !empty($this->params['connection'])) {
//			$Model->useDbConfig = $this->params['connection'];
//		}

		$data = $this->getData();

		foreach ($data as $item) {
			$this->insertItem($item, $Model);
		}
	}

/**
 * Insert an item using the model save functionality
 *
 * @param $item array to insert
 * @param $Model object
 */
	public function insertItem($item, $Model) {
		$Model->create();
		try {
			if (!$Model->save($item)) {
				$this->out('Unable to validate data for insert in ' . $Model->name);
				$this->out(pr($Model->data));
				$this->out(var_dump($Model->validationErrors));
				$this->out('<error>Installation failed</error>');
				die;
			}
		} catch(exception $e) {
			$this->out('Unable to save an item for ' . $Model->name);
			$this->out(pr($Model->data));
			$this->out($e->getMessage());
			$this->out('<error>Installation failed</error>');
			die;
		}
	}
}
