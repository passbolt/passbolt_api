<?php
/**
 * Insert Model Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::import('Model', 'User');
class ModelTask extends AppShell {

/**
 * Get Model
 *
 * @param string $model name
 * @return Model $Model
 */
	protected function _getModel($model) {
		$Model = Common::getModel($model);

		// Set Db Connection according to what is provided in params.
		if (isset($this->params['connection']) && !empty($this->params['connection'])) {
			$Model->useDbConfig = $this->params['connection'];
		}
		return $Model;
	}

/**
 * Before execute callback
 *
 * @param Model $Model allow performing operations on the model such as disabling behaviors
 * @return void
 */
	public function beforeInsert($Model) {
	}

/**
 * Execute the task
 *
 * @return void
 */
	public function execute() {
		$Model = $this->_getModel($this->model);
		$this->beforeInsert($Model);
		$data = $this->getData();
		$i = 0;
		foreach ($data as $item) {
			$this->insertItem($item, $Model);
			$i++;
		}
		$this->out('Data for model ' . $this->model . ' inserted (' . $i . ')');
	}

/**
 * Insert an item using the model save functionality
 *
 * @param array $item array to insert
 * @param Model $Model object to insert the data with
 * @return void
 */
	public function insertItem($item, $Model) {
		$Model->create();
		try {
			if (!$Model->save($item)) {
				$this->out('Unable to validate data for insert in ' . $Model->name);
				$this->out(pr($Model->data));
				$this->out(pr($Model->validationErrors));
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
