<?php
/**
 * AuthThrottle Helper Customization
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Helpers.AuthThrottleHelper
 * @since        version 2.12.9
 */
class AuthThrottleHelper extends AppHelper {

	public function get() {
		$nextLogin = $this->_View->Session->read("Throttle.nextLogin");
		if ($nextLogin < time()) {
			return null;
		}
		$html = '<input type="hidden" id="nextLogin" name="nextLogin" value="' . $nextLogin . '" />';
		$html .= '<div class="auththrottler"><span class="countdown"></span> seconds before next login</div>';
		return $html;
	}
}