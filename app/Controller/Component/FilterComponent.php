<?php
/**
 * Filter Component
 * Class used for working with filter

 * @copyright		 Copyright 2012, Passbolt.com
 * @license			 http://www.passbolt.com/license
 * @package			 app.Controller.Component.IpAddressComponent
 * @since				 version 2.13.03
 */

class FilterComponent extends Component {

	/**
	 * Check if the request contains a filter and extract it
	 * @param {array} $params The parameters of the request
	 * @return {array} The extract filter or null
	 */
	public static function fromRequest ($params = null) {
		$returnValue = array();

		// extract the keywords to filter on
		if(isset($params['fltr_keywords'])) {
			$returnValue['keywords'] = $params['fltr_keywords'];
		}
		// extract the script to apply to filter
		if(isset($params['fltr_case'])) {
			$returnValue['case'] = $params['fltr_case'];
		}
		// extrat the order to apply to the result
		if(isset($params['fltr_order'])) {
			$returnValue['order'] = $params['fltr_order'];
		}
		// extract the foreign model to filter on
		$exp = "/^fltr_model_(.*)$/";
		foreach($params as $param=>$value) {
			$matches = array();
			preg_match($exp, $param, $matches);
			if(!empty($matches)) {
				if(!isset($params['foreignModels'])) {
					$returnValue['foreignModels'] = array();
				}
				$returnValue['foreignModels'][ucfirst($matches[1]) . '.id'] = explode(',', $value);
			}
		}

		return is_array($returnValue) ? $returnValue : null;
	}

}