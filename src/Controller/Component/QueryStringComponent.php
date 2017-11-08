<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;
use Cake\Network\Exception\BadRequestException;
use Cake\Core\Exception\Exception;

/**
 * Query String Component
 * Class used for extracting query string parameters
 */
class QueryStringComponent extends Component
{
    /**
     * @var Request
     */
    protected $_request;

    /**
     * Initialize properties.
     *
     * @param array $config The config data.
     * @return void
     */
    public function initialize(array $config)
    {
        $controller = $this->_registry->getController();
        $this->_request = $controller->request;
    }

    /**
     * Get query Items
     * @param $allowedQueryItems
     * @return array
     */
    public function get($allowedQueryItems)
    {
        $query = $this->_request->getQueryParams();
        $query = self::rewriteLegacyItems($query);
        $query = self::extractQueryItems($query);
        $query = self::unsetUnwantedQueryItems($query, $allowedQueryItems);
        $query = self::normalizeQueryItems($query);
        self::validateQueryItems($query, $allowedQueryItems);
        $query = self::finalizeOrder($query);

        return $query;
    }

    /**
     * Allow rewriting legacy parameters to support new format
     * While keep backward compatibility in API
     *
     * @param $query
     */
    static function rewriteLegacyItems($query)
    {
        if (isset($query['modified_after'])) {
            $query['filter']['modified-after'] = $query['modified_after'];
            unset($query['modified_after']);
        }
        if (isset($query['keywords'])) {
            $query['filter']['search'] = $query['keywords'];
            unset($query['keywords']);
        }

        return $query;
    }

    /**
     * Additional normalization and array tranformations
     */
    static function normalizeQueryItems($query)
    {
        // order should always be an array even when one value is provided
        if (isset($query['order']) && !is_array($query['order'])) {
            $query['order'] = [$query['order']];
        }

        // filters with is-* means we are expecting a boolean
        // we accept 'TRUE', 'true', '1' as true and the rest is set to false
        if (isset($query['filter'])) {
            foreach ($query['filter'] as $filterName => $filter) {
                if (substr($filterName, 0, 3) === "is-") {
                    $query['filter'][$filterName] = self::normalizeBoolean($filter);
                } elseif ($filterName === 'search') {
                    // search should always be an array
                    if (!is_array($query['filter']['search'])) {
                        $query['filter']['search'] = [$query['filter']['search']];
                    }
                }
            }
        }
        // idem with contain clauses
        if (isset($query['contain'])) {
            foreach ($query['contain'] as $containName => $contain) {
                $query['contain'][$containName] = self::normalizeBoolean($contain);
            }
        }

        return $query;
    }

    /**
     * Extract array string items
     *
     * @return array $query the sanitized query
     */
    static function unsetUnwantedQueryItems($query, $allowedQueryItems)
    {
        foreach ($query as $key => $items) {
            if (!isset($allowedQueryItems[$key])) {
                unset($query[$key]);
            } else {
                if (is_array($items)) {
                    foreach ($items as $subkey => $subItem) {
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
    static function extractQueryItems($query)
    {
        foreach ($query as $key => $items) {
            if (is_array($items)) {
                foreach ($items as $subKey => $subItems) {
                    if (substr($subKey, -1) === 's' && is_scalar($query[$key][$subKey])) {
                        $query[$key][$subKey] = explode(',', $query[$key][$subKey]);
                    }
                }
            } elseif (is_string($items)) {
                if (strpos($items, ',')) {
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
    static function validateQueryItems($query, $allowedQueryItems)
    {
        foreach ($query as $key => $parameters) {
            switch ($key) {
                case 'filter':
                    try {
                        self::validateFilters($parameters);
                    } catch (Exception $e) {
                        throw new BadRequestException(__('Invalid filter.') . ' ' . $e->getMessage());
                    }
                    break;
                case 'order':
                    try {
                        self::validateOrders($parameters, $allowedQueryItems);
                    } catch (Exception $e) {
                        throw new BadRequestException(__('Invalid order.') . ' ' . $e->getMessage());
                    }
                    break;
                case 'contain':
                    try {
                        self::validateContain($parameters);
                    } catch (Exception $e) {
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
     * @throws Exception if one of the has-managers or has-users values is not a valid uuid
     * @return true if valid
     */
    static function validateFilters($filters = null)
    {
        if (isset($filters)) {
            foreach ($filters as $filter => $values) {
                switch ($filter) {
                    case 'search':
                        self::validateFilterSearch($values);
                        break;
                    case 'has-managers':
                    case 'has-users':
                        self::validateFilterUsers($values, $filter);
                        break;
                    case 'has-groups':
                        self::validateFilterGroups($values, $filter);
                        break;
                    case 'is-shared-with-group':
                        self::validateFilterGroup($values, $filter);
                        break;
                    case 'modified-after':
                        self::validateFilterTimestamp($values, $filter);
                        break;
                    case 'is-active':
                    case 'is-favorite':
                    case 'is-owned-by-me':
                    case 'is-shared-with-me':
                        self::validateFilterBoolean($values, $filter);
                        break;
                    default:
                        throw new Exception(__('No validation rule for filter {0}. Please create one.', $filter));
                    break;
                }
            }
        }

        return true;
    }

    /**
     * Check if the filter is a valid boolean
     *
     * @param $values
     * @throw Exception if the filter is not valid
     * @return true if the filter is valid
     */
    public static function validateFilterBoolean($values, $filtername)
    {
        if (!is_bool($values)) {
            throw new Exception(__('"{0}" is not a valid value for filter {1}.', $values, $filtername));
        }

        return true;
    }

    /**
     * Validate Search Filters
     * Input must be a non-assoc array with utf8 char values between 3 and 64 char in length
     * Bueno: [0 => 'ada', 1 => 'betty']
     * No Bueno: ['this' => 'no']
     *
     * @param $values
     * @throw Exception if the filter is not valid
     * @return true if the filter is valid
     */
    public static function validateFilterSearch($values)
    {
        foreach ($values as $i => $keyword) {
            if (!is_int($i)) {
                throw new Exception(__('"{0}" is not a valid search filter.', $i));
            }
            if (!is_scalar($keyword) || empty($keyword)) {
                throw new Exception(__('"{0}" is not a valid search filter.', $i));
            }
            if (!Validation::utf8($keyword)) {
                throw new Exception(__('"{0}" is not a valid search filter. It is not a UTF8 string.', $keyword));
            }
            if (!Validation::lengthBetween($keyword, 3, 64)) {
                throw new Exception(__('"{0}" is not a valid search filter. It should be between 3 and 64 char in length.', $keyword));
            }
        }

        return true;
    }

    /**
     * Validate Users Filters
     * Input must be a non-assoc array with utf8 char values between 3 and 64 char in length
     * Bueno: [0 => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08']
     * No Bueno: ['this' => 'no']
     *
     * @param $values
     * @throw Exception if the filter is not valid
     * @return true if the filter is valid
     */
    public static function validateFilterUsers($values, $filterName)
    {
        foreach ($values as $i => $userId) {
            if (!is_int($i)) {
                throw new Exception(__('"{0}" is not a valid user filter.', $i, $filterName));
            }
            if (!is_scalar($userId) || empty($userId)) {
                throw new Exception(__('"{0}" is not a valid user filter.', $i));
            }
            if (!Validation::uuid($userId)) {
                throw new Exception(__('"{0}" is not a valid user id for filter {1}.', $userId, $filterName));
            }
        }

        return true;
    }

    /**
     * Validate a filter that is an array of group id
     * Bueno: [0 => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08']
     * No Bueno: ['this' => 'no']
     *
     * @param $values
     * @param $filtername
     * @throw Exception if the filter is not valid
     * @return bool
     */
    public static function validateFilterGroups($values, $filtername)
    {
        foreach ($values as $i => $groupId) {
            if (!is_int($i)) {
                throw new Exception(__('"{0}" is not a valid group filter.', $i, $filtername));
            }
            if (!is_scalar($groupId) || empty($groupId)) {
                throw new Exception(__('"{0}" is not a valid group filter.', $i));
            }
            self::validateFilterGroup($groupId, $filtername);
        }

        return true;
    }

    /**
     * Validate a filter that is a single group id
     * Bueno: '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08'
     * No Bueno: 'no'
     *
     * @param $groupId
     * @param $filtername
     * @throw Exception if the filter is not valid
     * @return bool
     */
    public static function validateFilterGroup($groupId, $filtername)
    {
        if (!Validation::uuid($groupId)) {
            throw new Exception(__('"{0}" is not a valid group id for filter {1}.', $groupId, $filtername));
        }

        return true;
    }

    /**
     * Validate a filter that is a timestamp
     *
     * @param $values
     * @param $filtername
     * @throw Exception if the filter is not valid
     * @return bool
     */
    public static function validateFilterTimestamp($values, $filtername)
    {
        $timestamp = $values;
        if (!self::isTimestamp($timestamp)) {
            throw new Exception(__('"{0}" is not a valid timestamp for filter {1}.', $timestamp, $filtername));
        }

        return true;
    }

    /**
     * Validate order
     *
     * @param array $orders
     * - Group.name: a string that is a valid group name
     * @throws Exception if the group name does not validate
     * @return bool true if valid
     */
    public static function validateOrders($orders = null, $allowedQueryItems = null)
    {
        if (isset($orders)) {
            foreach ($orders as $i => $orderName) {
                if (!self::isOrder($orderName)) {
                    throw new Exception(__('"{0}" is not a valid order.', $orderName));
                }
                $order = explode(' ', $orderName); // remove ASC DESC if any
                if (!in_array($order[0], $allowedQueryItems['order'])) {
                    throw new Exception(__('"{0}" is not in the list of allowed order.', $orderName));
                }
            }
        }

        return true;
    }

    /**
     * Validate Contain
     *
     * @param array $contain conditions
     * @throws Exception if the contain value is not 0 or 1
     * @return bool true if valid
     */
    public static function validateContain($contain = null)
    {
        if (isset($contain)) {
            foreach ($contain as $item => $value) {
                if (!is_bool($value)) {
                    throw new Exception(__('"{0}" is not a valid contain value.', $value));
                }
            }
        }

        return true;
    }

    /**
     * Normalize string to boolean if it looks like one
     * 'TRUE', 'True', 'true', '1' becomes true
     * 'FALSE', 'False', 'false', '0' becomes false
     *
     * @param string $str the string to normalize
     * @return boolean|string if original string is not bool
     */
    public static function normalizeBoolean($str)
    {
        if (!is_scalar($str)) {
            return false;
        }
        if ((strtolower($str) === 'true' || $str === '1')) {
            return true;
        } elseif ((strtolower($str) === 'false' || $str === '0')) {
            return false;
        } else {
            return $str;
        }
    }

    /**
     * ['order' => [' User.first_name ASC', 'User.last_name DESC']]
     * becomes
     * ['order' => ['Users.first_name' => 'ASC', 'Users.last_name' => 'DESC']]
     */
    public static function finalizeOrder($query)
    {
        if (isset($query['order']) && count($query['order'])) {
            $neworder = [];
            foreach ($query['order'] as $order) {
                $output = preg_split("/(\.|( ))/", $order);
                if (count($output) < 2 && count($output) > 3) {
                    throw new Exception(__('"{0}" is not a valid order...', $order));
                }
                $key = Inflector::pluralize($output[0]) . '.' . $output[1];
                if (count($output) == 2) {
                    $output[2] = 'ASC';
                }
                $neworder[$key] = $output[2];
            }
            $query['order'] = $neworder;
        }

        return $query;
    }

    /**
     * Return true if valid timestamp
     *
     * @param string $timestamp unixtimestamp
     * @return bool true if unix timestamp
     */
    public static function isTimestamp($timestamp)
    {
        if (!is_scalar($timestamp)) {
            return false;
        }

        return ((string)(int)$timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * Return true if valid order
     *
     * @param string $orderName
     * @return bool true if valid order
     */
    public static function isOrder($orderName)
    {
        if (!is_scalar($orderName)) {
            return false;
        }
        $orderRegex = '/^[a-zA-Z]+(\.){1}([a-z_]+){1}(( ){1}(ASC|DESC){1}){0,1}$/';

        return (bool)(preg_match($orderRegex, $orderName) === 1);
    }
}
