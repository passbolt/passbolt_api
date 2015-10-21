<?php
App::uses('CakeEventListener', 'Event');

abstract class AbstractStorageEventListener extends Object implements CakeEventListener {

/**
 * The adapter class
 *
 * @param null|string
 */
	public $adapterClass = null;

/**
 * Name of the storage model class the event listener requires the model
 * instances to extend
 *
 * Must be FileStorage OR ImageStorage
 *
 * @var string
 */
	public $storageModelClass = 'FileStorage';

/**
 * Options
 *
 * @var array
 */
	public $options = array();

/**
 * List of adapter classes the event listener can work with
 *
 * It is used in FileStorageEventListenerBase::getAdapterClassName to get the
 * class, to detect if an event passed to this listener should be processed or
 * not. Only events with an adapter class present in this array will be
 * processed.
 *
 * @var array
 */
	protected $_adapterClasses = array();

/**
 * Default settings
 *
 * @var array
 */
	protected $_defaults = array(
		'models' => false,
		'preserveFilename' => false,
		'preserveExtension' => true,
	);

/**
 * Constructor
 *
 * @param array $options
 * @return AbstractStorageEventListener
 */
	public function __construct($options = array()) {
		$this->options = array_merge($this->_defaults, $options);
	}

/**
 * Implemented Events
 *
 * @return array
 */
	abstract public function implementedEvents();

/**
 * Check if the event is of a type or subject object of type model we want to
 * process with this listener
 *
 * @throws InvalidArgumentException
 * @param CakeEvent $Event
 * @return boolean
 */
	protected function _checkEvent(CakeEvent $Event) {
		if (!in_array($this->storageModelClass, array('FileStorage', 'ImageStorage'))) {
			throw new InvalidArgumentException(__d('file_storage', 'Invalid storage model %s! Must be FileStorage or ImageStorage!', $this->storageModelClass));
		}

		$Model = $Event->subject();
		return (
			$this->_checkModel($Event)
			&& $this->getAdapterClassName($Event->data['record'][$Model->alias]['adapter'])
			&& $this->_modelFilter($Event)
		);
	}

/**
 * _modelFilter
 *
 * @param CakeEvent $Event
 * @return boolean
 */
	protected function _modelFilter(CakeEvent $Event) {
		if (is_array($this->options['models'])) {
			$model = $Event->data['record'][$Event->subject()->alias]['model'];
			if (!in_array($model, $this->options['models'])) {
				return false;
			}
		}
		return true;
	}

/**
 * Checks if the events subject is a model and extending FileStorage or ImageStorage
 *
 * @param CakeEvent $Event
 * @return boolean
 */
	protected function _checkModel(CakeEvent $Event) {
		$Model = $Event->subject();
		$instanceCheck = ($Model instanceOf $this->storageModelClass);
		$adapterCheck = isset($Event->data['record'][$Model->alias]['adapter']);
		$adapterCheck2 = isset($Event->data['record']['adapter']);

		return ($instanceCheck && ($adapterCheck || $adapterCheck2));
	}

/**
 * _getAdapterClassFromConfig
 *
 * @param string $configName
 * @return boolean|string
 */
	protected function _getAdapterClassFromConfig($configName) {
		$config = $this->getAdapterconfig($configName);
		if (!empty($config['adapterClass'])) {
			return $config['adapterClass'];
		}
		return false;
	}

/**
 * Gets the adapter class name from the adapter configuration key
 *
 * @param string
 * @return void
 */
	public function getAdapterClassName($configName) {
		$className = $this->_getAdapterClassFromConfig($configName);

		if (in_array($className, $this->_adapterClasses)) {
			$position = strripos($className, '\\');
			$this->adapterClass = substr($className, $position + 1, strlen($className));
			return $this->adapterClass;
		}

		return false;
	}

/**
 * Wrapper around the singleton call to StorageManager::config
 *
 * @param string $configName
 * @return array
 */
	public function getAdapterconfig($configName) {
		return StorageManager::config($configName);
	}

/**
 * Wrapper around the singleton call to StorageManager::config
 *
 * @param string $configName
 * @return Object
 */
	public function getAdapter($configName) {
		return StorageManager::adapter($configName);
	}

}

