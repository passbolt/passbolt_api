<?php
/**
 * Form Helper Customization
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.View.Helpers.MyFormHelper
 * @since        version 2.12.9
 */
class MyFormHelper extends FormHelper {

/**
 * Input redefinition - required class += required span
 * @link http://book.cakephp.org/view/1390/Automagic-Form-Elements
 * @return string input
 * @access public
 */
	public function input($fieldName, $options = array()) {
		//if (isset($options['class']) && !empty($options['class']) && strstr($options['class'],'required')) {
		//	$options['label'] .= $this->required();
		//}
		if (isset($options['hint'])) {
			$options['after'] = $this->hint($options['hint']);
			unset($options['hint']);
		}
		return parent::input($fieldName, $options) . "\n";
	}

	public function submit($caption = NULL, $options = array()) {
		return parent::submit($caption, $options) . "\n";
	}

	public function end($options = NULL, $secureAttributes = Array()) {
		return parent::end() . "\n";
	}

/**
 * Required field special marker
 * @return string
 * @access public
 */
	public function required() {
		return ' <span class="required">*</span>';
	}

	public function hint($message) {
		return '<p class=\'info\'><span>' . $message . '</span></p>';
	}
}

