<?php
/**
 * StorageManager - manages and instantiates gaufrette storage engine instances
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class StorageManager {

/**
 * Adapter configs
 *
 * @var array
 */
	protected $_adapterConfig = array(
		'Local' => array(
			'adapterOptions' => array(TMP, true),
			'adapterClass' => '\Gaufrette\Adapter\Local',
			'class' => '\Gaufrette\Filesystem'
		)
	);

/**
 * Sets the default or active adapter that is used
 *
 * @var string
 */
	protected $_activeAdapter = 'Local';

/**
 * Return a singleton instance of the StorageManager.
 *
 * @return ClassRegistry instance
 */
	public static function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] = new StorageManager();
		}
		return $instance[0];
	}

/**
 * Sets or gets the active storage adapter
 *
 * @param string $adapter
 * @param array $options
 * @return mixed
 */
	public static function config($adapter = null, $options = array()) {
		$_this = StorageManager::getInstance();

		if (!empty($adapter) && !empty($options)) {
			return $_this->_adapterConfig[$adapter] = $options;
		}

		if (empty($adapter)) {
			return $_this->_adapterConfig[$_this->_activeAdapter];
		}

		if (isset($_this->_adapterConfig[$adapter])) {
			return $_this->_adapterConfig[$adapter];
		}

		return false;
	}

/**
 * Sets or gets the active storage adapter
 *
 * @param string
 * @return mixed
 */
	public static function activeAdapter($name = null) {
		$_this = StorageManager::getInstance();

		if (empty($name)) {
			return $_this->_activeAdapter;
		}

		if (isset($_this->_adapterConfig[$name])) {
			return $_this->_activeAdapter = $name;
		}
		return false;
	}

/**
 * Flush all or a single adapter from the config
 *
 * @param string $name Config name, if none all adapters are flushed
 * @throws RuntimeException
 * @return boolean True on success
 */
	public static function flush($name = null) {
		$_this = StorageManager::getInstance();

		if (empty($name)) {
			$_this->_adapterConfig = array();
			$_this->_activeAdapter = '';
		}

		if (isset($_this->_adapterConfig[$name])) {
			if ($_this->_activeAdapter == $name) {
				throw new RuntimeException(__d('file_storage', 'You can not flush the active adapter %s', $name));
			}
			unset($_this->_adapterConfig[$name]);
			return true;
		}

		return false;
	}

/**
 * StorageAdapter
 *
 * @param mixed $adapterName string of adapter configuration or array of settings
 * @param boolean $renewObject Creates a new instance of the given adapter in the configuration
 * @throws RuntimeException
 * @return Gaufrette object as configured by first argument
 */
	public static function adapter($adapterName = null, $renewObject = false) {
		$_this = StorageManager::getInstance();

		if (empty($adapterName)) {
			$adapterName = $_this->_activeAdapter;
		}

		$isConfigured = true;
		if (is_string($adapterName)) {
			if (!empty($_this->_adapterConfig[$adapterName])) {
				$adapter = $_this->_adapterConfig[$adapterName];
			} else {
				throw new RuntimeException(__d('file_storage', 'Invalid Storage Adapter %s', $adapterName));
			}

			if (!empty($_this->_adapterConfig[$adapterName]['object']) && $renewObject === false) {
				return $_this->_adapterConfig[$adapterName]['object'];
			}
		}

		if (is_array($adapterName)) {
			$adapter = $adapterName;
			$isConfigured = false;
		}

		$class = $adapter['adapterClass'];
		$Reflection = new ReflectionClass($class);
		$adapterObject = $Reflection->newInstanceArgs($adapter['adapterOptions']);
		$engineObject = new $adapter['class']($adapterObject);
		if ($isConfigured) {
			$_this->_adapterConfig[$adapterName]['object'] = &$engineObject;
		}
		return $engineObject;
	}

}