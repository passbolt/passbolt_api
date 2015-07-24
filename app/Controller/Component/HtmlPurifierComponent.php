<?php
/**
 * HtmlPurifierComponent
 *
 * @copyright	(c) 2015-present Passbolt.com
 * @licence		GNU Public Licence v3 - www.gnu.org/licenses/gpl-3.0.en.html
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

    /**
     * Clean markup in nested arrays
     * Useful for clean an entire $_POST for example
     *
     * @param $markup
     * @param $config
     * @return array
     */
    public function cleanRecursive($markup, $config = null) {
        if (empty($config) && !empty($this->settings['config'])) {
            $config = $this->settings['config'];
        }
        if(is_array($markup)) {
            foreach ($markup as $key => $value) {
                $markup[$key] = $this->cleanRecursive($value, $config);
            }
            return $markup;
        }
        else {
            return Purifier::clean($markup, $config);
        }
    }
}