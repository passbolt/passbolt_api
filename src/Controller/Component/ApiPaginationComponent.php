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
 * @since         3.2.0
 */
namespace App\Controller\Component;

use App\Datasource\Paging\NumericCountAwarePaginator;
use BryanCrowe\ApiPagination\Controller\Component\ApiPaginationComponent as BaseApiComponent;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Cake\Utility\Inflector;

class ApiPaginationComponent extends BaseApiComponent
{
    public const MAX_LIMIT = 1000000;

    public array $defaultConfig = [
        'visible' => [
            'count',
            'limit',
            'page',
        ],
    ];

    public array $paginate;

    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setConfig(array_merge($this->defaultConfig, $config));

        $order = $this->parseQuery();
        $this->setPaginationOptions($order);
        $this->removeSortingPaginationFromRequestQuery($order);
        $this->getController()->paginate['className'] = NumericCountAwarePaginator::class;
    }

    /**
     * @inheritDoc
     */
    public function beforeRender(Event $event): void
    {
        parent::beforeRender($event);

        /** @var \Cake\Controller\Controller $subject */
        $subject = $event->getSubject();

        $serialize = $subject->viewBuilder()->getOption('serialize') ?? [];
        $index = array_search('pagination', $serialize);
        if ($index) {
            unset($serialize[$index]);
            $header = $subject->viewBuilder()->getVar('header');
            $header['pagination'] = $subject->viewBuilder()->getVar('pagination');
            $subject->viewBuilder()->setVar('header', $header);
            $subject->viewBuilder()->setOption('serialize', $serialize);
        }
    }

    /**
     * Parse the request to extract the sorting parameters.
     * These are returned in format
     * [
     *  ['Models.field1' => 'asc'],
     *  ['Models.field2' => 'desc'],
     *  ...
     * ]
     *
     * @return array
     * @throws \Cake\Http\Exception\BadRequestException if a pagination parameter is not in the sortable list of the controller.
     */
    public function parseQuery(): array
    {
        $order = [];

        // The ApiPagination has priority on the legacy order
        if ($this->getController()->getRequest()->getQuery('sort')) {
            $order = $this->parseQueryPagination($this->getController()->getRequest());
        } elseif ($this->getController()->getRequest()->getQuery('order')) {
            $order = $this->parseLegacyOrderQuery($this->getController()->getRequest());
        }

        $this->filterSortableFields($order);

        return $order;
    }

    /**
     * @param array $orders Array of orders
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if a pagination parameter is not in the sortable list of the controller.
     */
    private function filterSortableFields(array $orders): void
    {
        $sortableFields = $this->getController()->paginate['sortableFields'] ?? [];
        foreach ($orders as $field => $direction) {
            if (!in_array($field, $sortableFields)) {
                $this->throwPaginationError(__('Invalid order. "{0}" is not in the list of allowed order.', $field));
            }
            if (!in_array($direction, ['asc', 'desc'])) {
                $this->throwPaginationError(__('Invalid order. "{0}" is not a valid order.', $direction));
            }
        }
    }

    /**
     * Set the sorting and default max limits in the controller's $paginate.
     *
     * @param array $order Order to set.
     * @return void
     */
    public function setPaginationOptions(array $order): void
    {
        if (!empty($order)) {
            $this->getController()->paginate['order'] = $order;
        }

        $this->getController()->paginate['limit'] = self::MAX_LIMIT;
        $this->getController()->paginate['maxLimit'] = self::MAX_LIMIT;
    }

    /**
     * Clean the soring parameters from the query.
     * This is handled by $controller->paginate
     *
     * @param array $order Sorting order.
     * @return void
     */
    private function removeSortingPaginationFromRequestQuery(array $order): void
    {
        $queryParams = $this->getController()->getRequest()->getQueryParams();
        unset($queryParams['sort']);
        unset($queryParams['order']);
        unset($queryParams['direction']);

        $this->getController()->setRequest(
            $this->getController()->getRequest()->withQueryParams($queryParams)
        );
    }

    /**
     * Parse the sort and direction query parameters.
     *
     * @param \Cake\Http\ServerRequest $request Request
     * @return array
     */
    private function parseQueryPagination(ServerRequest $request): array
    {
        $sort = $request->getQuery('sort', []);
        $direction = $request->getQuery('direction', 'asc');

        if (is_string($sort)) {
            return [$sort => $direction];
        } elseif (is_array($sort)) {
            foreach ($sort as &$v) {
                if ($v === '') {
                    $v = 'asc';
                }
            }

            return $sort;
        }

        return [];
    }

    /**
     * Parse all legacy orders.
     *
     * @param \Cake\Http\ServerRequest $request Request to parse.
     * @return array
     */
    private function parseLegacyOrderQuery(ServerRequest $request): array
    {
        $orders = (array)$request->getQuery('order');
        $result = [];

        foreach ($orders as $order) {
            if (!is_string($order)) {
                $this->throwPaginationError();
            }
            $result += $this->processLegacyOrder($order);
        }

        return $result;
    }

    /**
     * Parse a single legacy order.
     *
     * @param string $order Single field order.
     * @return string[]
     */
    private function processLegacyOrder(string $order): array
    {
        $fieldDirection = explode(' ', $order);
        $direction = 'asc';

        if (count($fieldDirection) === 2) {
            $order = $fieldDirection[0];
            $direction = strtolower($fieldDirection[1]);
            if (!in_array($direction, ['asc', 'desc'])) {
                $this->throwPaginationError(__('Invalid order. "{0}" is not a valid order.', $fieldDirection[1]));
            }
        } elseif (count($fieldDirection) > 2) {
            $this->throwPaginationError(__('Invalid order. "{0}" is not a valid order.', $order));
        }

        $modelField = explode('.', $order);
        if (count($modelField) !== 2) {
            $this->throwPaginationError(__('Invalid order. "{0}" is not a valid field.', $modelField));
        }

        $modelField[0] = Inflector::pluralize($modelField[0]);
        $field = implode('.', $modelField);

        return [$field => $direction];
    }

    /**
     * @param string|null $msg Message to display
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException
     */
    private function throwPaginationError(?string $msg = null): void
    {
        if (is_null($msg)) {
            $msg = __('Invalid pagination.');
        }
        throw new BadRequestException($msg);
    }
}
