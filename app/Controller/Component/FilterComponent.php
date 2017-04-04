<?php
/**
 * Filter Component
 * Class used for extracting search filter parameters from requests of JS client
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class FilterComponent extends Component {

/**
 * @var Controller $controller convenience reference to the parent controller
 */
	public $controller;

/**
 * Called before the Controller::beforeFilter().
 *
 * @param Controller $controller Controller with components to initialize
 * @throws CakeException if session component is not present
 * @return void
 * @link http://book.cakephp.org/2.0/en/controllers/components.html#Component::initialize
 */
	public function initialize(Controller $controller) {
		$this->controller = $controller;
		$contain = [];
		$filter = [];

		// Manage contains.
		if(isset($this->controller->request->query['contain']) && !empty($this->controller->request->query['contain'])) {
			$containData = $this->controller->request->query['contain'];
			if (!is_array($containData)) {
				$containList = explode(',', $containData);
				foreach($containList as $elt) {
					$contain[$elt] = 1;
				}
			}
			else {
				$contain = $containData;
			}
		}

		// Manage filters.
		if(isset($this->controller->request->query['filter']) && !empty($this->controller->request->query['filter'])) {
			$filterData = $this->controller->request->query['filter'];
			if (is_array($filterData)) {
				foreach($filterData as $filterName => $filterD) {
					$filterList = explode(',', $filterD);
					$filter[$filterName] = $filterList;
				}
			}
		}

		// Set contain and filter as params.
		$this->controller->request->params['contain'] = $contain;
		$this->controller->request->params['filter'] = $filter;
	}

/**
 * Check if the request contains a filter and extract it
 *
 *
 * @deprecated use the new format instead (?contains[users]=1&...) that will be automatically parsed
 * by the function initialize which will populate the params array.
 *
 * @param array $params The parameters of the request
 * @return array The extract filter or null
 */
	public static function fromRequest($params = null) {
		$returnValue = [];

		// extract the keywords to filter on
		if (isset($params['filter_keywords'])) {
			$returnValue['keywords'] = $params['filter_keywords'];
		}
		// extract the script to apply to filter
		if (isset($params['filter_case'])) {
			$returnValue['case'] = $params['filter_case'];
		}
		// extract the order to apply to the result
		if (isset($params['filter_order'])) {
			$returnValue['order'] = $params['filter_order'];
		}
		if (isset($params['modified_after'])) {
			$returnValue['modified_after'] = $params['modified_after'];
		}
		// extract the foreign model to filter on
		$exp = "/^filter_model_(.*)$/";
		foreach ($params as $param => $value) {
			$matches = [];
			preg_match($exp, $param, $matches);
			if (!empty($matches)) {
				if (!isset($params['foreignModels'])) {
					$returnValue['foreignModels'] = [];
				}
				$returnValue['foreignModels'][ucfirst($matches[1]) . '.id'] = explode(',', $value);
			}
		}

		return is_array($returnValue) ? $returnValue : null;
	}
}
