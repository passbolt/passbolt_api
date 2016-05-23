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
 *
 * @param string $fieldName This should be "Modelname.fieldname"
 * @param array $options Each type of input takes different options.
 * @return string Completed form input.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::input
 */
	public function input($fieldName, $options = []) {
		if (isset($options['hint'])) {
			$options['after'] = $this->hint($options['hint']);
			unset($options['hint']);
		}

		return parent::input($fieldName, $options) . "\n";
	}

/**
 * Submit button
 *
 * @param string $caption The label appearing on the button OR if string contains :// or the
 *  extension .jpg, .jpe, .jpeg, .gif, .png use an image if the extension
 *  exists, AND the first character is /, image is relative to webroot,
 *  OR if the first character is not /, image is relative to webroot/img.
 * @param array $options Array of options. See above.
 * @return string A HTML submit button
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#FormHelper::submit
 */
	public function submit($caption = null, $options = []) {
		return parent::submit($caption, $options) . "\n";
	}

/**
 * Form end
 *
 * @param string|array $options as a string will use $options as the value of button,
 * @param array $secureAttributes will be passed as html attributes into the hidden input elements generated for the
 *   Security Component.
 * @return string a closing FORM tag optional submit button.
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#closing-the-form
 */
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

/**
 * Insert a hint
 *
 * @param string $message hint content
 * @return string
 * @access public
 */
	public function hint($message) {
		return '<p class=\'info\'><span>' . $message . '</span></p>';
	}
}

