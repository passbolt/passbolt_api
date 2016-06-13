<?php

/**
 * AuthenticationBlacklist  model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class AuthenticationBlacklist extends AppModel {

/**
 * Get the validation rules upon context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'allowEmpty' => true,
					'required' => false,
					'message' => __('UUID must be in correct format')
				]
			],
			'ip' => [
				'validRange' => [
					'rule' => ['validIpRange', null],
					'allowEmpty' => false,
					'message' => __('Please provide a valid ip')
				]
			],
			'expiry' => [
				'validDate' => [
					'rule' => ['datetime', 'ymd'],
					'message' => 'Please enter a valid date and time.'
				]
			]
		];
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
		}

		return $rules;
	}

/**
 * Validate an IP address
 *
 * @param array $check ['ip']
 * @return bool true if a valid IP address
 */
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