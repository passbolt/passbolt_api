<?php
/**
 * HtmlPurifierComponent
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class HtmlPurifierComponent extends Component {

/**
 * @var array $setting main component settings
 */
	public $settings = [];

/**
 * Constructor
 *
 * @param ComponentCollection $collection A ComponentCollection this component can use to lazy load its components
 * @param array $settings Array of configuration settings.
 */
	public function __construct(ComponentCollection $collection, $settings = []) {
		$this->settings = $settings;
		parent::__construct($collection, $settings);
	}

/**
 * Clean markup
 *
 * @param string $markup html to clean
 * @param string $config rules for the cleaning job
 * @return string cleaned up text
 */
	public function clean($markup, $config = null) {
		if (empty($config) && !empty($this->settings['config'])) {
			$config = $this->settings['config'];
		}
		return Purifier::clean($markup, $config);
	}

/**
 * Clean markup in nested arrays
 * Useful for clean an entire $_POST for example
 *
 * @param string $markup html to clean
 * @param string $config rules for the cleaning job
 * @return string cleaned up text
 */
	public function cleanRecursive($markup, $config = null) {
		if (empty($config) && !empty($this->settings['config'])) {
			$config = $this->settings['config'];
		}
		if (is_array($markup)) {
			foreach ($markup as $key => $value) {
				$markup[$key] = $this->cleanRecursive($value, $config);
			}
			return $markup;
		} else {
			return Purifier::clean($markup, $config);
		}
	}
}