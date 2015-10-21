<?php
/**
 * ImageShell
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class ImageVersionShell extends Shell {
/**
 * Models
 *
 * @var array
 */
	public $uses = array();

/**
 * Storage Model Object
 */
	public $Model = null;

/**
 * Limit
 *
 * @var integer
 */
	public $limit = 10;

/**
 * Program entry point
 *
 * @return void
 */
	public function main() {
		$storageModel = 'FileStorage.ImageStorage';
		if (isset($this->params['storageModel'])) {
			$storageModel = $this->params['storageModel'];
		}

		$this->Model = ClassRegistry::init($storageModel);

		if (!$this->Model instanceOf ImageStorage) {
			$this->out(__d('file_storage', 'Invalid Storage Model: %s', $storageModel));
			$this->out(__d('file_storage', 'The model must be an instance of ImageStorage or inherit it!'));
			$this->_stop();
		}

		if (isset($this->params['limit'])) {
			if (!is_numeric($this->params['limit'])) {
				$this->out(__d('file_storage', '--limit must be an integer!'));
				$this->_stop();
			}
			$this->limit = $this->params['limit'];
		}

		if ($this->command == 'generate' || $this->command == 'remove') {
			if (isset($this->args[1]) && isset($this->args[2])) {
				$operations = Configure::read('Media.imageSizes.' . $this->args[1] . '.' . $this->args[2]);

				if (empty($operations)) {
					$this->out(__d('file_storage', 'Invalid model or version.'));
					$this->_stop();
				}

				try {
					$this->_loop($this->command, $this->args[1], array($this->args[2] => $operations));
				} catch (Exception $e) {
					$this->out($e->getMessage());
					$this->_stop();
				}

			} else {
				$this->out(__d('file_storage', 'Please use: generate <model> <version>'));
				$this->_stop();
			}
		}
	}

/**
 * getOptionParser
 *
 * @return Parser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();

		$parser->addSubcommand('generate', array(
			'help' => '<model> <version> Generate a new image version',
			'boolean' => true));
		$parser->addSubcommand('remove', array(
			'help' => '<model> <version> Remove an image version',
			'boolean' => true));

		$parser->addOption('storageModel', array(
			'short' => 's',
			'help' => __('The storage model for image processing you want to use.')));
		$parser->addOption('limit', array(
			'short' => 'l',
			'help' => __('Limits the amount of records to be processed in one batch')));

		return $parser;
	}

	protected function _loop($action, $model, $operations = array()) {
		if (!in_array($action, array('generate', 'remove'))) {
			$this->_stop();
		}

		$this->totaleImageCount = $this->Model->find('count', array(
			'recursive' => -1,
			'contain' => array(),
			'conditions' => array(
				$this->Model->alias . '.model' => $model,
				$this->Model->alias . '.extension' => array('jpg', 'png'))));

		if ($this->totaleImageCount > 0) {
			$this->out(__d('file_storage', '%d image files will be processed' . "\n", $this->totaleImageCount));

			$processed = 0;
			$options = array(
				'recursive' => -1,
				'contain' => array(),
				'conditions' => array(
					$this->Model->alias . '.model' => $model,
					$this->Model->alias . '.extension' => array('jpg', 'png')));

			$offset = 0;
			$limit = $this->limit;

			do {
				$options['limit'] = $limit;
				$options['offset'] = $offset;
				$images = $this->Model->find('all', $options);

				if (!empty($images)) {
					foreach ($images as $image) {
						$Storage = StorageManager::adapter($image[$this->Model->alias]['adapter']);
						if ($Storage === false) {
							$this->out(__d('file_storage'), 'Cant load adapter config %s for record %s', $image[$this->Model->alias]['adapter'], $image[$this->Model->alias][$this->Model->primaryKey]);
						} else {
							$payload = array(
								'record' => $image,
								'storage' => $Storage,
								'operations' => $operations);

							if ($action == 'generate') {
								$Event = new CakeEvent('ImageVersion.createVersion', $this->Model, $payload);
								CakeEventManager::instance()->dispatch($Event);
							}

							if ($action == 'remove') {
								$Event = new CakeEvent('ImageVersion.removeVersion', $this->Model, $payload);
								CakeEventManager::instance()->dispatch($Event);
							}

							$this->out(__('%s processed', $image[$this->Model->alias]['id']));
						}
					}
				}

				$offset += $limit;
			} while (!empty($images));
		} else {
			$this->out(__d('file_storage', 'No Images for model %s found', $model));
		}
	}

}