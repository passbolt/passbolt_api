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
		$order = [];

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

		// Manage orders.
		if(isset($this->controller->request->query['order']) && !empty($this->controller->request->query['order'])) {
			$orderData = $this->controller->request->query['order'];
			if (is_array($orderData)) {
				foreach($orderData as $orderKey => $orderValue) {
					$order[] = $orderValue;
				}
			} else {
				$order[] = $orderData;
			}
		}

		// Set contain, filter and order as params.
		$this->controller->request->params['contain'] = $contain;
		$this->controller->request->params['filter'] = $filter;
		$this->controller->request->params['order'] = $order;
	}
}
