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
 * @since         2.2.0
 */
namespace Passbolt\DirectorySync\Utility;

/**
 * Directory factory class
 * @package App\Utility
 */
class ActionReportCollection implements \Serializable, \Iterator, \ArrayAccess, \Countable
{
    protected $reports;
    private $position;

    /**
     * ActionReportCollection constructor.
     *
     * @param array $reports reports
     */
    public function __construct(array $reports = [])
    {
        $this->position = 0;
        $this->reports = $reports;
    }

    /**
     * Add report
     * @param ActionReport $report report
     * @return void
     */
    public function add(ActionReport $report)
    {
        $this->reports[] = $report;
    }

    /**
     * Get By Action
     * @param string $actionName action name
     *
     * @return ActionReportCollection
     */
    public function getByAction(string $actionName)
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
     * @param string $status status
     *
     * @return ActionReportCollection
     */
    public function getByStatus(string $status)
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
     * @return bool
     */
    public function isEmpty()
    {
        return (count($this->reports) === 0);
    }

    /**
     * Transform to array.
     * @return array
     */
    public function toArray()
    {
        return $this->reports;
    }

    /**
     * Serialize
     * @return string
     */
    public function serialize()
    {
        return serialize($this->reports);
    }

    /**
     * Unserialize
     * @param string $serialized serialized data
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->reports = unserialize($serialized);
    }

    /**
     * Rewind
     * @return void
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Current
     * @return mixed
     */
    public function current()
    {
        return $this->reports[$this->position];
    }

    /**
     * Key
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Next
     * @return void
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Check if valid.
     * @return bool
     */
    public function valid()
    {
        return isset($this->reports[$this->position]);
    }

    /**
     * Offset set.
     * @param mixed $offset offset
     * @param mixed $value value
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->reports[] = $value;
        } else {
            $this->reports[$offset] = $value;
        }
    }

    /**
     * Check if offset exists.
     * @param mixed $offset offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->reports[$offset]);
    }

    /**
     * unset offset.
     * @param mixed $offset offset
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->reports[$offset]);
    }

    /**
     * Get offset.
     * @param mixed $offset offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->reports[$offset]) ? $this->reports[$offset] : null;
    }

    /**
     * Count
     * @return int|void
     */
    public function count()
    {
        return count($this->reports);
    }
}
