<?php

/**
 * PassboltAuth Helper Customization
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.View.Helpers.PassboltAuthHelper
 * @since        version 2.13.03
 */
class PassboltAuthHelper extends AppHelper {

/**
 * Return indication on how long someone need to wait before trying to login again
 *
 * @return null|string
 */
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