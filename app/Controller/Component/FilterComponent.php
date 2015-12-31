<?php
/**
 * Filter Component
 * Class used for extracting search filter parameters from requests of JS client
 *
 * @copyright	(c) 2015-present Passbolt.com
 * @licence		GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class FilterComponent extends Component {

/**
 * Check if the request contains a filter and extract it
 *
 * @param Array $params The parameters of the request
 * @return Array The extract filter or null
 */
	public static function fromRequest($params = null) {
		$returnValue = array();

		// extract the keywords to filter on
		if (isset($params['fltr_keywords'])) {
			$returnValue['keywords'] = $params['fltr_keywords'];
		}
		// extract the script to apply to filter
		if (isset($params['fltr_case'])) {
			$returnValue['case'] = $params['fltr_case'];
		}
		// extract the order to apply to the result
		if (isset($params['fltr_order'])) {
			$returnValue['order'] = $params['fltr_order'];
		}
		if (isset($params['modified_after'])) {
			$returnValue['modified_after'] = $params['modified_after'];
		}
		// extract the foreign model to filter on
		$exp = "/^fltr_model_(.*)$/";
		foreach ($params as $param => $value) {
			$matches = array();
			preg_match($exp, $param, $matches);
			if (!empty($matches)) {
				if (!isset($params['foreignModels'])) {
					$returnValue['foreignModels'] = array();
				}
				$returnValue['foreignModels'][ucfirst($matches[1]) . '.id'] = explode(',', $value);
			}
		}

		return is_array($returnValue) ? $returnValue : null;
	}
}
