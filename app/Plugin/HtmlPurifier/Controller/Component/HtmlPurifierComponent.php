<?php

/**
 * HtmlPurifierComponent
 *
 * @author Bernhard Picher
 * @copyright 2012 Bernhard Picher
 * @license MIT
 */
class HtmlPurifierComponent extends Component {

/**
 * Settings
 *
 * @var array
 */
	public $settings = array();

/**
 * Constructor
 *
 * @param ComponentCollection $collection
 * @param array $settings
 */
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$this->settings = $settings;
		parent::__construct($collection, $settings);
	}

/**
 * Clean markup
 *
 * @param string $markup
 * @param string $config
 * @return string
 */
	public function clean($markup, $config = null) {
		if (empty($config) && !empty($this->settings['config'])) {
			$config = $this->settings['config'];
		}

		return Purifier::clean($markup, $config);
	}

}