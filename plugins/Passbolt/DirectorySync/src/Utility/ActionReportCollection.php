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

    public function __construct(array $reports = [])
    {
        $this->position = 0;
        $this->reports = $reports;
    }

    public function add(ActionReport $report)
    {
        $this->reports[] = $report;
    }

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

    public function isEmpty()
    {
        return (count($this->reports) === 0);
    }

    public function toArray()
    {
        return $this->reports;
    }

    public function serialize()
    {
        return serialize($this->reports);
    }

    public function unserialize($serialized)
    {
        $this->reports = unserialize($serialized);
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->reports[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->reports[$this->position]);
    }

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->reports[] = $value;
        } else {
            $this->reports[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->reports[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->reports[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->reports[$offset]) ? $this->reports[$offset] : null;
    }

    public function count() {
        return count($this->reports);
    }
}
