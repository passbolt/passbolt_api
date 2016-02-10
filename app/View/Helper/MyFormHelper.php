<?php

/**
 * Form Helper Customization
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Helpers.MyFormHelper
 * @since        version 2.12.9
 */
class MyFormHelper extends FormHelper {

	/**
	 * Input redefinition - required class += required span
	 *
	 * @link http://book.cakephp.org/view/1390/Automagic-Form-Elements
	 * @return string input
	 * @access public
	 */
	public function input($fieldName, $options = []) {
		//if (isset($options['class']) && !empty($options['class']) && strstr($options['class'],'required')) {
		//	$options['label'] .= $this->required();
		//}
		if (isset($options['hint'])) {
			$options['after'] = $this->hint($options['hint']);
			unset($options['hint']);
		}

		return parent::input($fieldName, $options) . "\n";
	}

	public function submit($caption = null, $options = []) {
		return parent::submit($caption, $options) . "\n";
	}

	public function end($options = null, $secureAttributes = []) {
		return parent::end() . "\n";
	}

	/**
	 * Required field special marker
	 *
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

