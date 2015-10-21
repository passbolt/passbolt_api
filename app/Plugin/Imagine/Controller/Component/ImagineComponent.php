<?php
/**
 * Copyright 2011-2014, Florian Krämer
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * Copyright 2011-2014, Florian Krämer
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Component', 'Controller/Component');
App::uses('Security', 'Utility');

/**
 * CakePHP Imagine Plugin
 *
 * @package Imagine.Controller.Component
 */
class ImagineComponent extends Component {

/**
 * Settings
 *
 * @var array
 */
	public $settings = array(
		'hashField' => 'hash',
		'checkHash' => true,
		'actions' => array()
	);

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
	public $operations = array();

/**
 * Constructor
 *
 * @param ComponentCollection $collection
 * @param array $settings
 * @return ImagineComponent
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->settings = Set::merge($this->settings, $settings);
		parent::__construct($collection, $this->settings);
	}

/**
 * Start Up
 *
 * @param Controller $Controller
 * @return void
 */
	public function startUp(Controller $Controller) {
		$this->Controller = $Controller;
		if (!empty($this->settings['actions'])) {
			if (in_array($this->Controller->action, $this->settings['actions'])) {
				if ($this->settings['checkHash'] === true) {
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
			throw new InvalidArgumentException(__d('imagine', 'Please configure Imagine.salt using Configure::write(\'Imagine.salt\', \'YOUR-SALT-VALUE\')', true));
		}

		if (!empty($this->Controller->request->params['named'])) {
			$params = $this->Controller->request->params['named'];
			unset($params[$this->settings['hashField']]);
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
		if (!isset($this->Controller->request->params['named'][$this->settings['hashField']]) && $error) {
			throw new NotFoundException();
		}

		$result = $this->Controller->request->params['named'][$this->settings['hashField']] == $this->getHash();

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
	public function unpackParams($namedParams = array()) {
		if (empty($namedParams)) {
			$namedParams = $this->Controller->request['named'];
		}

		foreach ($namedParams as $name => $params) {
			$tmpParams = explode(';', $params);
			$resultParams = array();
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