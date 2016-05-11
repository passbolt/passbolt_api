<?php
/**
 * Copyright 2011-2015, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2015, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
namespace Burzum\Imagine\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Security;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use InvalidArgumentException;

/**
 * CakePHP Imagine Plugin
 *
 * @package Imagine.Controller.Component
 */
class ImagineComponent extends Component {

/**
 * Default config
 *
 * These are merged with user-provided config when the component is used.
 *
 * @var array
 */
	protected $_defaultConfig = [
		'hashField' => 'hash',
		'checkHash' => true,
		'actions' => [],
	];

/**
 * Controller instance
 *
 * @var object
 */
	public $Controller;

/**
 * Image processing operations taken by ImagineBehavior::processImage()
 *
 * This property is auto populated by ImagineComponent::unpackParams()
 *
 * @var array
 */
	public $operations = [];

/**
 * Constructor
 *
 * @param \Cake\Controller\ComponentRegistry $collection
 * @param array $config Config options array
 */
	public function __construct(ComponentRegistry $collection, $config = array()) {
		parent::__construct($collection, $config);
		$Controller = $collection->getController();
		$this->request = $Controller->request;
		$this->response = $Controller->response;
	}

/**
 * Start Up
 *
 * @param Event $Event
 * @return void
 */
	public function startup(Event $Event) {
		$Controller = $Event->subject();
		$this->Controller = $Controller;
		if (!empty($this->_config['actions'])) {
			if (in_array($this->Controlle->action, $this->_config['actions'])) {
				if ($this->_config['checkHash'] === true) {
					$this->checkHash();
				}
				$this->unpackParams();
			}
		}
	}

/**
 * Creates a hash based on the named params but ignores the hash field
 *
 * The hash can also be used to determine if there is already a cached version
 * of the requested image that was processed with these params. How you do that
 * is up to you.
 *
 * @throws InvalidArgumentException
 * @return mixed String if a hash could be retrieved, false if not
 */
	public function getHash() {
		$mediaSalt = Configure::read('Imagine.salt');
		if (empty($mediaSalt)) {
			throw new InvalidArgumentException('Please configure Imagine.salt using Configure::write(\'Imagine.salt\', \'YOUR-SALT-VALUE\')');
		}

		if (!empty($this->request->query)) {
			$params = $this->request->query;
			unset($params[$this->_config['hashField']]);
			ksort($params);
			return Security::hash(serialize($params) . $mediaSalt);
		}
		return false;
	}

/**
 * Compares the hash passed within the named args with the hash calculated based
 * on the other named args and the imagine salt
 *
 * This is done to avoid that people can randomly generate tons of images by
 * just incrementing the width and height for example in the url.
 *
 * @param boolean $error If set to false no 404 page will be rendered if the hash is wrong
 * @throws NotFoundException if the hash was not present
 * @return boolean True if the hashes match
 */
	public function checkHash($error = true) {
		if (!isset($this->request->query[$this->_config['hashField']]) && $error) {
			throw new NotFoundException();
		}

		$result = $this->request->query[$this->_config['hashField']] == $this->getHash();

		if (!$result && $error) {
			throw new NotFoundException();
		}

		return $result;
	}

/**
 * Unpacks the strings into arrays that were packed with ImagineHelper::pack()
 *
 * @param array $namedParams
 * @internal param array $params If empty the method tries to get them from Controller->request['named']
 * @return array Array with operation options for imagine, if none found an empty array
 */
	public function unpackParams($namedParams = []) {
		if (empty($namedParams)) {
			$namedParams = $this->request->query;
		}

		foreach ($namedParams as $name => $params) {
			$tmpParams = explode(';', $params);
			$resultParams = [];
			foreach ($tmpParams as &$param) {
				list($key, $value) = explode('|', $param);
				$resultParams[$key] = $value;
			}
			$resultParams;
			$namedParams[$name] = $resultParams;
		}

		$this->operations = $namedParams;
		return $namedParams;
	}
}
