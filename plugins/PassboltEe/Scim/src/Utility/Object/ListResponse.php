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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Utility\Object;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Scim\Exception\BadRequestException;
use Passbolt\Scim\Exception\ScimException;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Utility\SchemaIdentifier;
use Passbolt\Scim\Utility\ScimObjectInterface;
use Passbolt\Scim\Utility\ScimResources;

/**
 * ListResponse class
 */
class ListResponse implements ScimObjectInterface
{
    use LocatorAwareTrait;

    /**
     * Constructor
     *
     * @param array $resources Resources on the list
     * @param int $startIndex Start index (1 if first page)
     * @param int $itemsPerPage max items per page
     * @param int $totalResults Total Count
     */
    public function __construct(
        protected array $resources = [],
        protected int $startIndex = 1,
        protected int $itemsPerPage = 25,
        protected int $totalResults = 0
    ) {
    }

    /**
     * @inheritDoc
     */
    public function toSCIM(): array
    {
        $resources = [];
        foreach ($this->resources as $resource) {
            $resources[] = $resource instanceof ScimObjectInterface ? $resource->toSCIM() : $resource;
        }

        return [
            'schemas' => [SchemaIdentifier::API_LIST_RESPONSE],
            'itemsPerPage' => $this->itemsPerPage,
            'startIndex' => $this->startIndex,
            'totalResults' => $this->totalResults,
            'Resources' => $resources,
        ];
    }

    /**
     * Fetch resources based on the type, filter and pagination config
     *
     * @param string $resourceType
     * @param int|null $startIndex
     * @param int|null $count
     * @param string|null $filter
     * @return $this
     * @throws \Exception
     */
    public function fetchResources(
        string $resourceType,
        ?int $startIndex = null,
        ?int $count = null,
        ?string $filter = null,
    ) {
        if (!ScimResources::isValid($resourceType)) {
            throw new BadRequestException(sprintf('The resource type `%s` is not valid', $resourceType));
        }
        if (!isset(ScimEntry::MODEL_MAP[$resourceType])) {
            throw new BadRequestException(
                sprintf('The resource type `%s` has not map for scim entry model', $resourceType)
            );
        }

        if ($startIndex !== null && $startIndex > 0) {
            $this->startIndex = $startIndex;
        }
        if ($count !== null && $count > 0) {
            $this->itemsPerPage = $count;
        }

        /** @var \Passbolt\Scim\Model\Table\ScimEntriesTable $scimEntriesTable */
        $scimEntriesTable = $this->fetchTable('Passbolt/Scim.ScimEntries');
        $conditions = [
            $scimEntriesTable->aliasField('foreign_model') => ScimEntry::MODEL_MAP[$resourceType],
        ];
        if ($filter !== null) {
            //@todo: tmilos/scim-filter-parser should be used if more filters are needed
            $formattedFilter = str_replace('+eq+', ' eq ', $filter);
            $formattedFilter = str_replace('"', '', $formattedFilter);
            $filterParts = explode(' ', $formattedFilter);
            $attribute = $filterParts[0] ?? null;
            $operator = $filterParts[1] ?? null;
            $value = $filterParts[2] ?? null;
            switch (strtolower($operator)) {
                case 'eq':
                    switch ($attribute) {
                        case 'userName':
                            $conditions[$scimEntriesTable->aliasField('scim_name')] = $value;
                            break;
                        default:
                            throw new ScimException(
                                sprintf('The filter for attribute `%s` is not supported yet', $attribute)
                            );
                    }
                    break;
                default:
                    throw new ScimException(sprintf('The filter for operator `%s` is not supported yet', $operator));
            }
        }

        $countQuery = $scimEntriesTable->find();
        $this->resources = [];
        $result = $countQuery
            ->select(['count' => $countQuery->func()->count('id')])
            ->where($conditions)
            ->whereNull($scimEntriesTable->aliasField('deleted'))
            ->first();
        $this->totalResults = $result['count'] ?? 0;
        if ($this->totalResults === 0) {
            return $this;
        }

        /** @var array<\Passbolt\Scim\Model\Entity\ScimEntry> $scimResources */
        $scimResources = $scimEntriesTable
            ->find()
            ->where($conditions)
            ->whereNull($scimEntriesTable->aliasField('deleted'))
            ->offset($this->startIndex - 1)
            ->limit($this->itemsPerPage)
            ->orderBy([
                $scimEntriesTable->aliasField('created') => 'ASC',
                $scimEntriesTable->aliasField('scim_name') => 'ASC',
            ])
            ->toArray();
        foreach ($scimResources as $scimResource) {
            $this->resources[] = ScimResources::build($resourceType)->setFromDatabase($scimResource->foreign_key);
        }

        return $this;
    }
}
