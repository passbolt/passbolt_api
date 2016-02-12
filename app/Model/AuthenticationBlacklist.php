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
 * @param string $case (optional) The target validation case if any.
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
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
					'rule' => array('datetime', 'ymd'),
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

}