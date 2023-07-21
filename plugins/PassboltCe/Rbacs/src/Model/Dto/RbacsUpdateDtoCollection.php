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

namespace Passbolt\Rbacs\Model\Dto;

use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Hash;
use Cake\Validation\Validation;

class RbacsUpdateDtoCollection
{
    /**
     * @var array $data
     */
    protected array $data = [];

    /**
     * Build a DTO from user provided data
     *
     * @throws \Cake\Http\Exception\BadRequestException if data is invalid
     * @param array $data data from user request
     */
    public function __construct(array $data)
    {
        $this->assertdata($data);
        $this->data = $data;
    }

    /**
     * Get all the data
     *
     * @return array data [{id:<uuid>, control_function:<string>},...]
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get an array with all the IDs
     *
     * @return array data [<uuid>,...]
     */
    public function getIds(): array
    {
        return (array)Hash::extract($this->data, '{n}.id');
    }

    /**
     * Get an item from the collection using the id
     *
     * @param string $id uuid
     * @return array data {id:<uuid>, control_function:<string>}
     */
    public function getById(string $id): array
    {
        if (!Validation::uuid($id)) {
            throw new \InvalidArgumentException(__('The identifier should be a valid UUID.') . ' ' . $id);
        }
        $result = (array)Hash::extract($this->data, '{n}[id=' . $id . ']');
        if (!count($result)) {
            throw new NotFoundException(__('Record not found') . ' ' . $id);
        }

        return $result[0];
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Assert the whole data set is correct
     *
     * @throw BadRequestException if data is invalid
     * @param array $data data [{id:<uuid>, control_function:<string>},...]
     * @return void
     */
    public function assertdata(array $data): void
    {
        if (!count($data)) {
            throw new BadRequestException(__('The request data is empty.'));
        }
        foreach ($data as $entry) {
            if (!is_array($entry)) {
                throw new BadRequestException(__('The request data is invalid: expected a collection.'));
            }
            $this->assertEntry($entry);
        }

        $this->assertUniqueIds($data);
    }

    /**
     * Assert a given data entry
     *
     * @throw BadRequestException if entry doesn't match the expected format
     * @param array $entry entry {id:<uuid>, control_function:<string>}
     * @return void
     */
    public function assertEntry(array $entry): void
    {
        if (count($entry) > 2) {
            throw new BadRequestException(__('The request data is invalid: invalid fields.'));
        }
        if (!isset($entry['id'])) {
            throw new BadRequestException(__('The request data is invalid: id missing.'));
        }
        if (!is_string($entry['id']) || !Validation::uuid($entry['id'])) {
            throw new BadRequestException(__('The request data is invalid: id invalid.'));
        }
        if (!isset($entry['control_function'])) {
            throw new BadRequestException(__('The request data is invalid: control_function missing.'));
        }
        if (!is_string($entry['control_function']) || !Validation::ascii($entry['control_function'])) {
            throw new BadRequestException(__('The request data is invalid: control_function invalid.'));
        }
    }

    /**
     * Assert data contains only one occurence of each id
     *
     * @throw BadRequestException if multiple entries with same id is sent
     * @param array $data data [{id:<uuid>, control_function:<string>},...] where id values must be unique
     * @return void
     */
    public function assertUniqueIds(array $data): void
    {
        $unique = array_unique(Hash::extract($data, '{n}.id'));
        if (count($unique) != count($data)) {
            throw new BadRequestException(__('The request data is invalid: ids must be unique.'));
        }
    }
}
