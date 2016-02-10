<?php

/**
 * PassboltAuth Helper Customization
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Helpers.PassboltAuthHelper
 * @since        version 2.13.03
 */
class PassboltAuthHelper extends AppHelper {

	public function get() {
		$html = '';
		$nextLoginTime = $this->_View->Session->read("Throttle.nextLoginTime");
		if (gmdate('U') >= $nextLoginTime) {
			return null;
		}
		// Calculate diff
		$nbSecondsDiff = $nextLoginTime - gmdate('U');


		// Creates the html
		$html .= '<input type="hidden" id="nextLogin" name="nextLogin" value="' . $nbSecondsDiff . '" />';
		$html .= '<div class="auththrottler"><span class="countdown">' . $nbSecondsDiff . '</span> seconds before next login</div>';

		return $html;
	}
}