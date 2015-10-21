<?php
App::uses('Purifier', 'HtmlPurifier.Lib');
/**
 * HtmlPurifierHelper
 *
 * @author Florian Krämer
 * @copyright 2012 Florian Krämer
 * @license MIT
 */
class HtmlPurifierHelper extends AppHelper {
/**
 * Settings
 *
 * @var array
 */
	public $settings = array();

/**
 * Constructor - determines engine helper
 *
 * @param View $View the view object the helper is attached to.
 * @param array $settings Settings array contains name of engine helper.
 */
	public function __construct(View $View, $settings = array()) {
		$this->settings = $settings;
		parent::__construct($View, $settings);
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