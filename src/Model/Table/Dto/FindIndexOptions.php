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

namespace App\Model\Table\Dto;

class FindIndexOptions
{
    public const FILTER_OPTION = 'filter';
    public const ORDER_OPTION = 'order';
    public const CONTAIN_OPTION = 'contain';

    /**
     * @var string[]
     */
    private $filter = [];

    /**
     * @var string[]
     */
    private $contain = [];

    /**
     * @var string[]
     */
    private $order = [];

    /**
     * @var string[]
     */
    private $allowedFilter = [];

    /**
     * @var string[]
     */
    private $allowedOrder = [];

    /**
     * @var string[]
     */
    private $allowedContain = [];

    /**
     * @var callable[]
     */
    private $filterValidators = [];

    /**
     * UUID of a User
     *
     * @var string
     */
    private $userId;

    /**
     * @param array|null $filter filters Filters
     * @param array|null $order orders Orders
     * @param array|null $contain contains Contains
     */
    final public function __construct(?array $filter = [], ?array $order = [], ?array $contain = [])
    {
        $this->filter = $filter;
        $this->order = $order;
        $this->contain = $contain;
    }

    /**
     * @param array $findIndexOptions Find index options
     * @return \App\Model\Table\Dto\FindIndexOptions
     */
    public static function createFromArray(array $findIndexOptions)
    {
        return new static(
            $findIndexOptions[self::FILTER_OPTION] ?? [],
            $findIndexOptions[self::ORDER_OPTION] ?? [],
            $findIndexOptions[self::CONTAIN_OPTION] ?? []
        );
    }

    /**
     * @param array $filters Filters
     * @return $this
     */
    public function allowFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $this->allowFilter($filter);
        }

        return $this;
    }

    /**
     * @param array $contains Contains
     * @return $this
     */
    public function allowContains(array $contains)
    {
        foreach ($contains as $contain) {
            $this->allowContain($contain);
        }

        return $this;
    }

    /**
     * @param array $orders Orders
     * @return $this
     */
    public function allowOrders(array $orders)
    {
        foreach ($orders as $order) {
            $this->allowOrder($order);
        }

        return $this;
    }

    /**
     * @param string $filterName Filter
     * @return $this
     */
    public function allowFilter(string $filterName)
    {
        $this->allowedFilter[] = $filterName;

        return $this;
    }

    /**
     * @param string $containName Contain
     * @return $this
     */
    public function allowContain(string $containName)
    {
        $this->allowedContain[] = $containName;

        return $this;
    }

    /**
     * @param string $orderName Order
     * @return $this
     */
    public function allowOrder(string $orderName)
    {
        $this->allowedOrder[] = $orderName;

        return $this;
    }

    /**
     * @param string $filterName filter
     * @param string $value value
     * @return $this
     */
    public function addFilter(string $filterName, $value)
    {
        $this->filter[$filterName] = $value;

        return $this;
    }

    /**
     * @param string $orderName order name
     * @param mixed $value order value
     * @return $this
     */
    public function addOrder(string $orderName, $value = null)
    {
        $this->order[$orderName] = $value;

        return $this;
    }

    /**
     * Add a new filter validator callable. This validator will be used by QueryStringComponent to validate the
     * passed values of the filter.
     *
     * @param string $filterName Name of the filter
     * @param callable $validatorCallable The callable will receive the filter value as parameter and
     * must return true if the filtered value is valid. Return false or throw an exception if the filter is not valid.
     * @return $this
     * @see QueryStringComponent::validateFilters()
     */
    public function addFilterValidator(string $filterName, callable $validatorCallable)
    {
        $this->filterValidators[$filterName] = $validatorCallable;

        return $this;
    }

    /**
     * @param string $filterName Name of the filter
     * @return callable|null
     */
    public function getFilterValidator(string $filterName)
    {
        return $this->filterValidators[$filterName] ?? null;
    }

    /**
     * Set the ID of the user for which the findIndex is executed
     *
     * @param string $userId The user id
     * @return void
     */
    public function setUserId(string $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Return the ID of the user for which the findIndex is executed
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::FILTER_OPTION => $this->filter,
            self::ORDER_OPTION => $this->order,
            self::CONTAIN_OPTION => $this->contain,
        ];
    }

    /**
     * @return array
     */
    public function getAllowedOptions()
    {
        return [
            self::FILTER_OPTION => $this->allowedFilter,
            self::ORDER_OPTION => $this->allowedOrder,
            self::CONTAIN_OPTION => $this->allowedContain,
        ];
    }

    /**
     * @return string[]
     */
    public function getContain()
    {
        return $this->contain;
    }

    /**
     * @return string[]
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return callable[]
     */
    public function getFilterValidators()
    {
        return $this->filterValidators;
    }
}
