<?php
declare(strict_types=1);

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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Actions\Reports;

/**
 * Directory factory class
 *
 * @package App\Utility
 */
class ActionReportCollection implements \Iterator, \ArrayAccess, \Countable
{
    /**
     * @var array
     */
    protected $reports = [];

    /**
     * @var int
     */
    private $position;

    /**
     * ActionReportCollection constructor.
     *
     * @param array|null $reports reports
     */
    public function __construct(?array $reports = [])
    {
        $this->position = 0;
        $this->reports = $reports;
    }

    /**
     * Add report
     *
     * @param \Passbolt\DirectorySync\Actions\Reports\ActionReport $report report
     * @return void
     */
    public function add(ActionReport $report): void
    {
        $this->reports[] = $report;
    }

    /**
     * Get By Action
     *
     * @param string $actionName action name
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection // phpcs:ignore
     */
    public function getByAction(string $actionName): ActionReportCollection
    {
        $result = [];
        foreach ($this->reports as $i => $report) {
            if ($report->getAction() === $actionName) {
                $result[] = $report;
            }
        }

        return new ActionReportCollection($result);
    }

    /**
     * Get by status.
     *
     * @param string $status status
     * @return \Passbolt\DirectorySync\Actions\Reports\ActionReportCollection // phpcs:ignore
     */
    public function getByStatus(string $status): ActionReportCollection
    {
        $result = [];
        foreach ($this->reports as $i => $report) {
            if ($report->getStatus() === $status) {
                $result[] = $report;
            }
        }

        return new ActionReportCollection($result);
    }

    /**
     * check if empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return count($this->reports) === 0;
    }

    /**
     * Transform to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->reports;
    }

    /**
     * Serialize
     *
     * @return array
     */
    public function __serialize(): array // phpcs:ignore
    {
        return $this->reports;
    }

    /**
     * Unserialize
     *
     * @param array $data data
     * @return void
     */
    public function __unserialize(array $data): void // phpcs:ignore
    {
        $this->reports = $data;
    }

    /**
     * Rewind
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    #[\ReturnTypeWillChange]

    /**
     * Current
     *
     * @return mixed // not strict for 7.3 compatibility
     */
    public function current()
    {
        return $this->reports[$this->position];
    }

    #[\ReturnTypeWillChange]

    /**
     * Key
     *
     * @return int // should be mixed for PHP 8 interface compliance
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next
     *
     * @return void
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * Check if valid.
     *
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->reports[$this->position]);
    }

    /**
     * Offset set.
     *
     * @param mixed $offset offset
     * @param mixed $value value
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if (is_null($offset)) {
            $this->reports[] = $value;
        } else {
            $this->reports[$offset] = $value;
        }
    }

    /**
     * Check if offset exists.
     *
     * @param mixed $offset offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return isset($this->reports[$offset]);
    }

    /**
     * unset offset.
     *
     * @param mixed $offset offset
     * @return void
     */
    public function offsetUnset($offset): void
    {
        unset($this->reports[$offset]);
    }

    #[\ReturnTypeWillChange]

    /**
     * Get offset.
     *
     * @param mixed $offset offset
     * @return mixed|null // not strict for 7.3 compatibility
     */
    public function offsetGet($offset)
    {
        return $this->reports[$offset] ?? null;
    }

    /**
     * Count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->reports);
    }

    /**
     * Transform a collection of reports to Json.
     *
     * @return array
     */
    public function toFormattedArray(): array
    {
        $res = [];
        foreach ($this->reports as $report) {
            $res[] = $report->toArray();
        }

        return $res;
    }
}
