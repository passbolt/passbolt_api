<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Exception\CakeException;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Inflector;
use Cake\Validation\Validation;

/**
 * Query String Component
 * Class used for extracting query string parameters
 *
 * @method \App\Controller\AppController getController()
 * @property \App\Controller\Component\ApiPaginationComponent $ApiPagination
 */
class QueryStringComponent extends Component
{
    public $components = ['ApiPagination'];

    /**
     * Get query Items
     *
     * @param array $allowedQueryItems whitelist
     * @param callable[] $filterValidators Filters validator callable
     * @return array
     */
    public function get(array $allowedQueryItems, array $filterValidators = [])
    {
        $request = $this->getController()->getRequest();
        $query = $request->getQueryParams();
        $query = self::rewriteLegacyItems($query);
        $query = self::extractQueryArrayItems($query);
        $query = self::unsetUnwantedQueryItems($query, $allowedQueryItems);
        $query = self::normalizeQueryItems($query);
        self::validateQueryItems($query, $allowedQueryItems, $filterValidators);
        $query = self::finalizeOrder($query);

        return $query;
    }

    /**
     * Allow rewriting legacy parameters to support new format
     * While keep backward compatibility in API
     *
     * @param array $query original query string items
     * @return array modified query
     */
    public static function rewriteLegacyItems(array $query): array
    {
        if (isset($query['modified_after'])) {
            $query['filter']['modified-after'] = $query['modified_after'];
            unset($query['modified_after']);
        }
        if (isset($query['keywords'])) {
            $query['filter']['search'] = $query['keywords'];
            unset($query['keywords']);
        }
        if (isset($query['filter']['keywords'])) {
            $query['filter']['search'] = $query['filter']['keywords'];
            unset($query['filter']['keywords']);
        }
        if (isset($query['contain']['LastLoggedIn'])) {
            $query['contain']['last_logged_in'] = $query['contain']['LastLoggedIn'];
            unset($query['contain']['LastLoggedIn']);
        }
        // Sorting is now handled by the ApiPaginationComponent
        if (isset($query['order'])) {
            unset($query['order']);
        }

        return $query;
    }

    /**
     * Additional normalization and array transformations
     *
     * @param array $query original query string items
     * @return array modified query
     */
    public static function normalizeQueryItems(array $query): array
    {
        // order should always be an array even when one value is provided
        // this is deprecated, order is now handled by the ApiPaginationComponent
        if (isset($query['order']) && !is_array($query['order'])) {
            $query['order'] = [$query['order']];
        }

        // filters with is-* means we are expecting a boolean
        // we accept 'TRUE', 'true', '1' as true and the rest is set to false
        if (isset($query['filter'])) {
            if ($query['filter'] == '[]') {
                $query['filter'] = [];
            }
            if (!is_array($query['filter'])) {
                throw new BadRequestException(__('Invalid query string. The filter parameter should be an array.'));
            }
            foreach ($query['filter'] as $filterName => $filter) {
                if (!is_string($filterName)) {
                    continue;
                }
                if (in_array($filterName, self::mustBeArrayFilters())) {
                    // these should always be an array
                    $query['filter'][$filterName] = $filter = (array)$query['filter'][$filterName];
                }
                if (substr($filterName, 0, 3) === 'is-') {
                    $query['filter'][$filterName] = self::normalizeBoolean($filter);
                } elseif ($filterName === 'has-parent') {
                    foreach ($query['filter']['has-parent'] as $i => $parentId) {
                        if ($parentId === 'false' || $parentId === '0') {
                            $query['filter']['has-parent'][$i] = false;
                        }
                    }
                } elseif ($filterName === 'from') {
                    try {
                        $query['filter']['from'] = new \DateTime($query['filter']['from']);
                    } catch (\Exception $e) {
                        $query['filter']['from'] = false;
                    }
                } elseif ($filterName === 'frequency') {
                    $query['filter'][$filterName] = self::normalizeInteger($filter);
                }
            }
        }
        // idem with contain clauses
        if (isset($query['contain'])) {
            if (!is_array($query['contain'])) {
                throw new BadRequestException(__('Invalid query string. The contain parameter should be an array.'));
            }
            foreach ($query['contain'] as $containName => $contain) {
                $query['contain'][$containName] = self::normalizeBoolean($contain);
            }
        }

        return $query;
    }

    /**
     * Extract array string items
     *
     * @param array $query original query string items
     * @param array $allowedQueryItems whitelist
     * @return array $query the sanitized query
     */
    public static function unsetUnwantedQueryItems(array $query, array $allowedQueryItems): array
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
     * Defines the filters which values should by all mean be converted to arrays.
     *
     * @return string[]
     */
    protected static function mustBeArrayFilters(): array
    {
        return ['search', 'has-parent', 'has-id', 'has-groups', 'has-managers', 'has-users', 'has-access',];
    }

    /**
     * Extract filters items
     * - Transform to array when key ends with 's' like 'has-users' and is not a boolean like 'is-'
     * - Transform to array when multiple comma separated values are present
     *
     * @param array $query original query string items
     * @return array $query the sanitized query
     */
    public static function extractQueryArrayItems(array $query): array
    {
        foreach ($query as $key => $items) {
            if ($key === 'contain') {
                continue;
            }
            if (is_array($items)) {
                foreach ($items as $subKey => $subItems) {
                    if (
                        is_string($subKey) &&
                        substr($subKey, -1) === 's' &&
                        is_string($subItems) &&
                        substr($subKey, 0, 3) !== 'is-'
                    ) {
                        $query[$key][$subKey] = explode(',', $subItems);
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
     * @param array $query items to validate
     * @param array $allowedQueryItems whitelisted items
     * @param callable[] $filterValidators Filters validator callable
     * @return bool true if validate
     * @throws \Cake\Http\Exception\BadRequestException if a validation error occurs
     */
    public static function validateQueryItems(array $query, array $allowedQueryItems, array $filterValidators): bool
    {
        foreach ($query as $key => $parameters) {
            switch ($key) {
                case 'filter':
                    try {
                        self::validateFilters($parameters, $filterValidators);
                    } catch (CakeException $e) {
                        throw new BadRequestException(__('Invalid filter.') . ' ' . $e->getMessage());
                    }
                    break;
                case 'order':
                    try {
                        self::validateOrders($parameters, $allowedQueryItems);
                    } catch (CakeException $e) {
                        throw new BadRequestException(__('Invalid order.') . ' ' . $e->getMessage());
                    }
                    break;
                case 'contain':
                    try {
                        self::validateContain($parameters);
                    } catch (CakeException $e) {
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
     * @param array|null $filters such as:
     * - search: a string to do a keyword based search
     * - has-access: a resource id
     * - has-users: an array of user uuids
     * - has-manager: an array of user uuids
     * - has-groups: an array of group uuids
     * - has-parent: an array of folder uuids
     * - is-shared-with-group: a group uuid
     * - modified-after: timestamp
     * - created-before: timestamp
     * - created-after: timestamp
     * - is-active: bool
     * - is-favorite: bool
     * - is-owned-by-me: bool
     * - is-shared-with-me: bool
     * - is-success: bool
     * @param callable[] $filterValidators Filter validators callable
     * @return bool true if valid
     * @throws \Cake\Core\Exception\CakeException if one of the filters is not supported / not in the list
     */
    public static function validateFilters(?array $filters = null, array $filterValidators = []): bool
    {
        if (isset($filters)) {
            foreach ($filters as $filterName => $values) {
                // See: https://www.php.net/manual/en/types.comparisons.php for NULL/0/FALSE
                switch ((string)$filterName) {
                    case 'search':
                        self::validateFilterSearch($values);
                        break;
                    case 'from':
                    case 'created-before':
                    case 'created-after':
                        self::validateFilterDateTime($values, $filterName);
                        break;
                    case 'has-access':
                    case 'has-id':
                        self::validateFilterResources($values, $filterName);
                        break;
                    case 'has-managers':
                    case 'has-users':
                        self::validateFilterUsers($values, $filterName);
                        break;
                    case 'has-groups':
                        self::validateFilterGroups($values, $filterName);
                        break;
                    case 'has-parent':
                        self::validateFilterParentFolders($values, $filterName);
                        break;
                    case 'is-shared-with-group':
                        self::validateFilterGroup($values, $filterName);
                        break;
                    case 'modified-after':
                        self::validateFilterTimestamp($values, $filterName);
                        break;
                    case 'is-active':
                    case 'is-admin':
                    case 'is-favorite':
                    case 'is-owned-by-me':
                    case 'is-shared-with-me':
                    case 'is-success':
                    case 'is-deleted':
                        self::validateFilterBoolean($values, $filterName);
                        break;
                    case 'has-tag':
                        self::validateFilterString($values, $filterName);
                        break;
                    case 'frequency':
                        self::validateFilterInteger($values, $filterName);
                        break;
                    default:
                        // Check if custom filter validators were defined for this filter
                        if (!isset($filterValidators[$filterName])) {
                            $msg = __('No validation rule for filter {0}. Please create one.', $filterName);
                            throw new CakeException($msg);
                        }

                        if (!call_user_func($filterValidators[$filterName], $values)) {
                            throw new CakeException(__('Filter {0} is not valid.', $filterName));
                        }

                        break;
                }
            }
        }

        return true;
    }

    /**
     * Check if the filter is a valid string
     *
     * @param mixed $value to check
     * @param string $filtername for error message display
     * @throw CakeException if the filter is not valid
     * @return bool true if the filter is valid
     */
    public static function validateFilterString($value, string $filtername)
    {
        if (empty($value) || !is_string($value)) {
            throw new CakeException(__('"{0}" is not a valid value for filter {1}.', $value, $filtername));
        }

        return true;
    }

    /**
     * Check if the filter is a valid boolean
     *
     * @param mixed $values to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool true if the filter is valid
     */
    public static function validateFilterBoolean($values, string $filterName): bool
    {
        if (!is_bool($values)) {
            throw new CakeException(__('"{0}" is not a valid value for filter {1}.', $values, $filterName));
        }

        return true;
    }

    /**
     * Check if the filter is a valid integer
     *
     * @param mixed $values to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool true if the filter is valid
     */
    public static function validateFilterInteger($values, string $filterName): bool
    {
        if (!is_int($values)) {
            throw new CakeException(__('"{0}" is not a valid value for filter {1}.', $values, $filterName));
        }

        return true;
    }

    /**
     * Validate Search Filters
     * Input must be a non-assoc array with utf8 char values between 3 and 64 char in length
     * Example:
     * - Bueno: [0 => 'ada', 1 => 'betty']
     * - No Bueno: ['this' => 'no']
     *
     * @param array $values query items
     * @throw CakeException if the filter is not valid
     * @return bool true if the filter is valid
     */
    public static function validateFilterSearch(array $values): bool
    {
        foreach ($values as $i => $keyword) {
            if (!is_int($i)) {
                throw new CakeException(__('"{0}" is not a valid search filter.', $i));
            }
            if (!is_scalar($keyword) || empty($keyword)) {
                throw new CakeException(__('"{0}" is not a valid search filter.', $i));
            }
            if (!Validation::utf8($keyword)) {
                $msg = __('"{0}" is not a valid search filter. It is not a UTF8 string.', $keyword);
                throw new CakeException($msg);
            }
            if (!Validation::lengthBetween($keyword, 1, 64)) {
                $msg = __('"{0}" is not a valid search filter.', $keyword) . ' ';
                $msg .= __('It should be between 1 and 64 char in length.');
                throw new CakeException($msg);
            }
        }

        return true;
    }

    /**
     * Validate Users Filters
     * Input must be a non-assoc array with utf8 char values between 3 and 64 char in length
     * Examples:
     * - Bueno: [0 => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08']
     * - No Bueno: ['this' => 'no']
     *
     * @param array $values array of user id to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool true if the filter is valid
     */
    public static function validateFilterUsers(array $values, string $filterName): bool
    {
        foreach ($values as $i => $userId) {
            if (!is_int($i)) {
                throw new CakeException(__('"{0}" is not a valid user filter.', $i, $filterName));
            }
            if (!is_scalar($userId) || empty($userId)) {
                throw new CakeException(__('"{0}" is not a valid user filter.', $i));
            }
            if (!Validation::uuid($userId)) {
                throw new CakeException(__('"{0}" is not a valid user id for filter {1}.', $userId, $filterName));
            }
        }

        return true;
    }

    /**
     * Validate a filter that is an array of group id
     * Examples:
     * - Bueno: [0 => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08']
     * - No Bueno: ['this' => 'no']
     *
     * @param array $values array of group id to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool true if validate
     */
    public static function validateFilterGroups(array $values, string $filterName): bool
    {
        foreach ($values as $i => $groupId) {
            if (!is_int($i)) {
                throw new CakeException(__('"{0}" is not a valid group filter.', $i, $filterName));
            }
            if (!is_string($groupId) || empty($groupId)) {
                throw new CakeException(__('"{0}" is not a valid group filter.', $i));
            }
            self::validateFilterGroup($groupId, $filterName);
        }

        return true;
    }

    /**
     * Validate a filter that is an array of parent id
     * Examples:
     * - Bueno: [0 => '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08']
     * - No Bueno: ['this' => 'no']
     *
     * @param array $values array of group id to check
     * @param string|bool $filtername for error message display
     * @throw Exception if the filter is not valid
     * @return bool true if validate
     */
    public static function validateFilterParentFolders(array $values, $filtername)
    {
        foreach ($values as $i => $parentId) {
            if (!is_int($i)) {
                throw new CakeException(__('"{0}" is not a valid parent filter.', $i, $filtername));
            }
            if (!is_string($parentId) && $parentId !== false) {
                throw new CakeException(__('"{0}" is not a valid parent filter.', $i));
            }
            self::validateFilterParentFolder($parentId, $filtername);
        }

        return true;
    }

    /**
     * Validate a filter that is a single resource id
     * Examples:
     * - Bueno: '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08'
     * - No Bueno: 'no-bueno'
     *
     * @param array $values resources id
     * @param string $filterName name of filters
     * @throw CakeException if the filter is not valid
     * @return bool true if validate
     */
    public static function validateFilterResources(array $values, string $filterName): bool
    {
        foreach ($values as $i => $resourceId) {
            if (!is_int($i)) {
                throw new CakeException(__('"{0}" is not a valid resource id for filter {1}.', $i, $filterName));
            }
            if (!is_scalar($resourceId) || empty($resourceId)) {
                throw new CakeException(__('"{0}" is not a valid resource id for filter {1}.', $i));
            }
            if (!Validation::uuid($resourceId)) {
                $msg = __('"{0}" is not a valid resource id for filter {1}.', $resourceId, $filterName);
                throw new CakeException($msg);
            }
        }

        return true;
    }

    /**
     * Validate a filter that is a single group id
     * Examples:
     * - Bueno: '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08'
     * - No Bueno: 'no-bueno'
     *
     * @param string $groupId uuid
     * @param string $filterName name of filters
     * @throw CakeException if the filter is not valid
     * @return bool if validate
     */
    public static function validateFilterGroup(string $groupId, string $filterName): bool
    {
        if (!Validation::uuid($groupId)) {
            throw new CakeException(
                __('"{0}" is not a valid group id for filter {1}.', $groupId, $filterName)
            );
        }

        return true;
    }

    /**
     * Validate a filter that is a single parent id
     * Examples:
     * - Bueno: '98c2bef5-cd5f-59e7-a1a7-0107c9a7cf08'
     * - Bueno: false
     * - No Bueno: 'no-bueno'
     *
     * @param string|false $parentId uuid
     * @param string $filtername name of filters
     * @throw Exception if the filter is not valid
     * @return bool if validate
     */
    public static function validateFilterParentFolder($parentId, string $filtername)
    {
        if (!Validation::uuid($parentId) && $parentId != false) {
            throw new CakeException(__('"{0}" is not a valid parent id for filter {1}.', $parentId, $filtername));
        }

        return true;
    }

    /**
     * Validate a filter that is a timestamp
     *
     * @param mixed $values timestamp to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool if validate
     */
    public static function validateFilterTimestamp($values, string $filterName): bool
    {
        $timestamp = $values;
        if (!self::isTimestamp($timestamp)) {
            throw new CakeException(
                __('"{0}" is not a valid timestamp for filter {1}.', $timestamp, $filterName)
            );
        }

        return true;
    }

    /**
     * Validate a filter that is a datetime.
     *
     * @param mixed $values the value to check
     * @param string $filterName for error message display
     * @throw CakeException if the filter is not valid
     * @return bool if validate
     */
    public static function validateFilterDateTime($values, string $filterName): bool
    {
        if (!is_string($values)) {
            throw new CakeException(__('"{0}" is not a valid datetime for filter {1}.', $values, $filterName));
        }
        try {
            new \DateTime($values);
        } catch (\Exception $e) {
            throw new CakeException(__('"{0}" is not a valid datetime for filter {1}.', $values, $filterName));
        }

        return true;
    }

    /**
     * Validate order
     *
     * @param array|null $orders a list of order to validate like ['Groups.name ASC', 'Users.created']
     * @param array|null $allowedQueryItems whitelist
     * @return bool true if validate
     * @throws \Cake\Core\Exception\CakeException if the group name does not validate
     * @deprecated Use the ApiPaginationComponent
     */
    public static function validateOrders(?array $orders = null, ?array $allowedQueryItems = null): bool
    {
        if (isset($orders)) {
            foreach ($orders as $i => $orderName) {
                if (!self::isOrder($orderName)) {
                    throw new CakeException(__('"{0}" is not a valid order.', $orderName));
                }
                $order = explode(' ', $orderName); // remove ASC DESC if any
                if (!isset($allowedQueryItems) || !in_array($order[0], $allowedQueryItems['order'])) {
                    throw new CakeException(__('"{0}" is not in the list of allowed order.', $orderName));
                }
            }
        }

        return true;
    }

    /**
     * Validate Contain
     *
     * @param array|null $contain conditions
     * @return bool true if validate
     * @throws \Cake\Core\Exception\CakeException if the contain value is not 0 or 1
     */
    public static function validateContain(?array $contain = null): bool
    {
        if (isset($contain)) {
            foreach ($contain as $item => $value) {
                if (!is_bool($value)) {
                    throw new CakeException(__('"{0}" is not a valid contain value.', $value));
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
     * @param mixed $str the string to normalize
     * @return bool|string if original string is not bool
     */
    public static function normalizeBoolean($str)
    {
        if (is_bool($str)) {
            return $str;
        }
        if (!is_string($str)) {
            return false;
        }
        if ((strtolower($str) === 'true' || $str === '1')) {
            return true;
        } elseif ((strtolower($str) === 'false' || $str === '0')) {
            return false;
        }

        return $str;
    }

    /**
     * Normalize string to integer if it is a scalar
     *
     * @param mixed $str the string to normalize
     * @return false|int false if non scalar type, integer value of $str otherwise
     */
    public static function normalizeInteger($str)
    {
        if (!is_scalar($str)) {
            return false;
        }

        return intval($str);
    }

    /**
     * Format order so that it can be used as is by ORM layer
     * Examples:
     * - ['order' => ['User.first_name ASC', 'User.last_name DESC']]
     * becomes
     * - ['order' => ['Users.first_name' => 'ASC', 'Users.last_name' => 'DESC']]
     *
     * @param array $query items
     * @return array updated query items
     * @deprecated Use the ApiPaginationComponent
     */
    public static function finalizeOrder(array $query): array
    {
        if (isset($query['order']) && count($query['order'])) {
            $neworder = [];
            foreach ($query['order'] as $order) {
                $output = preg_split("/(\.|( ))/", $order);
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
     * @param mixed $timestamp unixtimestamp
     * @return bool true if unix timestamp
     */
    public static function isTimestamp($timestamp): bool
    {
        if (!is_scalar($timestamp)) {
            return false;
        }

        return ((string)(int)$timestamp === $timestamp) && ($timestamp <= PHP_INT_MAX) && ($timestamp >= ~PHP_INT_MAX);
    }

    /**
     * Return true if valid order
     *
     * @param mixed $orderName like Groups.name
     * @deprecated Order is now handled by the ApiPaginationComponent
     * @return bool true if valid order
     */
    public static function isOrder($orderName): bool
    {
        if (!is_string($orderName)) {
            return false;
        }
        $orderRegex = '/^[a-zA-Z]+(\.){1}([a-z_]+){1}(( ){1}(ASC|DESC){1}){0,1}$/';

        return preg_match($orderRegex, $orderName) === 1;
    }
}
