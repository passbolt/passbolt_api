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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Resources;

use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\Exception\ScimException;

class ListResponse
{
    public const SCHEMA = 'urn:ietf:params:scim:api:messages:2.0:ListResponse';
    protected int $startIndex;
    protected int $totalResults;
    protected array $resources;

    /**
     * Constructor
     *
     * @param array $resources Resources on the list
     * @param int $startIndex Start index (1 if first page)
     * @param int $totalResults Total Count
     */
    public function __construct(array $resources, int $startIndex = 1, int $totalResults = 10)
    {
        $this->resources = $resources;
        $this->startIndex = $startIndex;
        $this->totalResults = $totalResults;
    }

    /**
     * Converts object to SCIM format
     *
     * @return array
     */
    public function toSCIM(): array
    {
        $resources = [];
        foreach ($this->resources as $resource) {
            if ($resource instanceof ResourceTypeInterface) {
                $resources[] = $resource->toSCIM();
                continue;
            }
            $resources[] = $resource;
        }

        return [
            'totalResults' => $this->totalResults,
            'itemsPerPage' => count($this->resources),
            'startIndex' => $this->startIndex,
            'schemas' => [self::SCHEMA],
            'Resources' => $resources,
        ];
    }

    /**
     * Parse filter (incomplete)
     *
     * @param string|null $filter Filter
     * @return array
     */
    protected function parseFilter(?string $filter): array
    {
        if (!$filter) {
            return [];
        }
        //@todo tmilos/scim-filter-parser must be used to avoid reimplement all parse logic
        //Only accepting userName eq XXXXX for now
        $operands = explode('eq', str_replace('"', '', $filter));
        if (!isset($operands[0])) {
            throw new ScimException(
                'The specified filter syntax was invalid',
                400,
                null,
                ScimException::SCIM_TYPE_INVALID_FILTER
            );
        }

        return [
            trim($operands[0]) => trim($operands[1]),
        ];
    }

    /**
     * Find existing User entities
     *
     * @param ?string $filter Filter
     * @return \Cake\ORM\Query
     */
    protected function findExistingEntities(?string $filter = null): Query
    {
        //@todo after implement parse filter this function needs to be refactored
        $conditions = $this->parseFilter($filter);

        return TableRegistry::getTableLocator()->get('Users')
            ->find()
            ->contain(['Profiles', 'ScimEntries'])
            ->matching('ScimEntries', function (Query $q) use ($conditions) {
                if (empty($conditions)) {
                    return $q;
                }

                return $q->where([
                    'ScimEntries.scim_name' => (string)$conditions['userName'],
                ]);
            });
        // ->where($conditions);
    }

    /**
     * Fetch all resources
     *
     * @param string $resourceType Resource Type
     * @param string|null $filter Filter
     * @return void
     * @throws \Exception
     */
    public function fetchAllResources(string $resourceType, ?string $filter = null): void
    {
        $entities = $this->findExistingEntities($filter)->toArray();
        $resources = [];
        foreach ($entities as $entity) {
            $resource = ResourceTypeFactory::build($resourceType);
            $resource->setFromDatabase($entity);
            $resources[] = $resource;
        }
        $this->resources = $resources;
        $this->totalResults = count($resources);
    }
}
