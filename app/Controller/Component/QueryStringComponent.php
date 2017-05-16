<?php
/**
 * Query String Component
 * Class used for extracting query string parameters
 *
 * @copyright (c) 2017-present Passbolt SARL
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class QueryStringComponent extends Component {

/**
 * @const regular expression to validate orders
 */
	const ORDER_REGEXP = '/^[a-zA-Z]+(\.){1}([a-z_]+){1}(( ){1}(ASC|DESC){1}){0,1}$/';

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
	}

/**
 * Get query Items
 * @param $allowedQueryItems
 * @return array
 */
	public function get($allowedQueryItems) {
		$query = $this->controller->request->query;
		$query = self::rewriteLegacyItems($query);
		$query = self::extractQueryItems($query);
		$query = self::unsetUnwantedQueryItems($query, $allowedQueryItems);
		$query = self::normalizeQueryItems($query);
		self::validateQueryItems($query, $allowedQueryItems);
		return $query;
	}

/**
 * Allow rewriting legacy parameters to support new format
 * While keep backward compatibility in API
 *
 * @param $query
 */
	static function rewriteLegacyItems($query) {
		if (isset($query['modified_after'])) {
			$query['filter']['modified-after'] = $query['modified_after'];
			unset($query['modified_after']);
		}
		if (isset($query['keywords'])) {
			$query['filter']['keywords'] = $query['keywords'];
			unset($query['keywords']);
		}
		return $query;
	}

/**
 * Additional normalization and array tranformations
 */
	 static function normalizeQueryItems($query) {
	 	// order should always be an array even when one value is provided
	 	if(isset($query['order']) && !is_array($query['order'])) {
			$query['order'] = [$query['order']];
	 	}
	 	// filters with is-* means we are expecting a boolean
		// we accept 'TRUE', 'true', '1' as true and the rest is set to false
	 	if (isset($query['filter'])) {
	 		foreach ($query['filter'] as $filterName => $filter) {
	 			if (substr($filterName, 0, 3) === "is-") {
					$query['filter'][$filterName] = Common::normalizeIfBoolean($filter);
				}
	 		}
	 	}
	 	// idem with contain clauses
		if (isset($query['contain'])) {
			foreach ($query['contain'] as $containName => $contain) {
				$query['contain'][$containName] = Common::normalizeIfBoolean($contain);
			}
		}
	 	return $query;
	 }

/**
 * Extract array string items
 *
 * @return array $query the sanitized query
 */
	static function unsetUnwantedQueryItems($query, $allowedQueryItems) {
		foreach ($query as $key => $items) {
			if(!isset($allowedQueryItems[$key])) {
				unset($query[$key]);
			} else {
				if(is_array($items)) {
					foreach($items as $subkey => $subItem) {
						if (is_string($subkey) && !in_array($subkey, $allowedQueryItems[$key])) {
							unset($query[$key][$subkey]);
						}
					}
				}
			}
		}
		return $query;
	}

/**
 * Extract filters items
 * - Transform to array when key ends with 's' like 'has-users'
 * - Transform to array when multiple comma separated values are present
 *
 * @return array $query the sanitized query
 */
	static function extractQueryItems($query) {
		foreach ($query as $key => $items) {
			if(is_array($items)) {
				foreach($items as $subKey => $subItems) {
					if(substr($subKey, -1) === 's') {
						$query[$key][$subKey] = explode(',', $query[$key][$subKey]);
					}
				}
			} else if(is_string($items)) {
				if(strpos($items, ',')) {
					$query[$key] = explode(',', $query[$key]);
				}
			}
		}
		return $query;
	}

/**
 * Validate query items
 *
 * @params array $allowedQueryItems whitelisted items
 * @params array $query items to validate
 * @throws BadRequestException if a validation error occurs
 * @return bool true if validate
 */
	static function validateQueryItems($query, $allowedQueryItems) {
		foreach($query as $key => $parameters) {
			switch ($key) {
				case 'filter':
					try {
						self::validateFilters($parameters);
					} catch (ValidationException $e) {
						throw new BadRequestException(__('Invalid filter.') . ' ' . $e->getMessage());
					}
					break;
				case 'order':
					try {
						self::validateOrders($parameters, $allowedQueryItems);
					} catch (ValidationException $e) {
						throw new BadRequestException(__('Invalid order.') . ' ' . $e->getMessage());
					}
					break;
				case 'contain':
					try {
						self::validateContain($parameters);
					} catch (ValidationException $e) {
						throw new BadRequestException(__('Invalid contain.') . ' ' . $e->getMessage());
					}
					break;
			}
		}
		return true;
	}

/**
 * Validate filters
 *
 * @params array $filters
 * - has-users: an array of user uuids
 * - has-manager: an array of user uuids
 * @throws ValidationException if one of the has-managers or has-users values is not a valid uuid
 * @return true if valid
 */
	static function validateFilters ($filters = null) {
		if (isset($filters)) {
			foreach ($filters as $filter => $values) {
				switch ($filter) {
					case 'has-managers':
					case 'has-users':
						foreach($values as $i => $userId) {
							if(!Common::isUuid($userId)) {
								throw new ValidationException(__('"%s" is not a valid user id for filter %s.', $userId, $filter));
							}
						}
					break;
					case 'modified-after':
						$timestamp = $values;
						if (!Common::isTimestamp($timestamp)) {
							throw new ValidationException(__('"%s" is not a valid timestamp for filter %s.', $timestamp, $filter));
						}
					break;
					case 'is-favorite':
					case 'is-owned-by-me':
					case 'is-shared-with-me':
						if (!is_bool($values)) {
							throw new ValidationException(__('"%s" is not a valid value for filter %s.', $values, $filter));
						}
					break;
				}
			}
		}
		return true;
	}

/**
 * Validate order
 *
 * @param array $orders
 * - Group.name: a string that is a valid group name
 * @throws ValidationException if the group name does not validate
 * @return bool true if valid
 */
	static function validateOrders($orders = null, $allowedQueryItems = null) {
		if (isset($orders)) {
			foreach ($orders as $i => $orderName) {
				if (preg_match(self::ORDER_REGEXP, $orderName) !== 1) {
					throw new ValidationException(__('"%s" is not a valid order.', $orderName));
				}
				$order = explode(' ', $orderName); // remove ASC DESC if any
				if(!in_array($order[0], $allowedQueryItems['order'])) {
					throw new ValidationException(__('"%s" is not a valid order.', $orderName));
				}
			}
		}
		return true;
	}

/**
 * Validate Contain
 *
 * @param $contain conditions
 * @throws ValidationException if the contain value is not 0 or 1
 * @return bool true if valid
 */
	static function validateContain($contain = null) {
		if (isset($contain)) {
			foreach ($contain as $item => $value) {
				if (!is_bool($value)) {
					throw new ValidationException(__('"%s" is not a valid contain value.', $value));
				}
			}
		}
		return true;
	}
}