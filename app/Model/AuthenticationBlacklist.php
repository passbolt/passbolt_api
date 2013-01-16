<?php
/**
 * AuthenticationBlacklist  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.AuthenticationBlacklist
 * @since        version 2.13.03
 */

class AuthenticationBlacklist extends AppModel {

/**
 * Get the validation rules upon context
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case='default') {
		$default = array(
			'id' => array(
				'uuid' => array(
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message'	=> __('UUID must be in correct format')
				)
			),
			'ip' => array(
				'validRange' => array(
					'rule' => array('validIpRange', null),
					'allowEmpty' => false,
					'message' => __('Please provide a valid ip')
				)
			),
			'expiry' => array(
				'validDate' => array(
					'rule'    => array('datetime', 'dmy'),
					'message' => 'Please enter a valid date and time.'
				)
			)
		);
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
		}
		return $rules;
	}

	public function validIpRange($check) {
		if ($check['ip'] == null) {
			return false;
		}
		$ipRegexp = '([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])';
		$ipwildcardRegexp = '^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.(\*?|[01]?\\d\\d?|2[0-4]\\d|25[0-5])$';
		$ipRangeRegexp = '^' . $ipRegexp . '-' . $ipRegexp . '$';
		$ipMaskRegexp = '^' . $ipRegexp . '\/[0-9]{1,2}$';
		if (preg_match('/' . $ipwildcardRegexp . '/', $check['ip'])) {
			return true;
		} elseif (preg_match('/' . $ipRangeRegexp . '/', $check['ip'])) {
			return true;
		} elseif (preg_match('/' . $ipMaskRegexp . '/', $check['ip'])) {
			return true;
		}
		return false;
	}

}