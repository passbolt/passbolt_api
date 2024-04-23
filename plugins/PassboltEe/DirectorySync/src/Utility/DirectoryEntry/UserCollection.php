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
 * @since         4.2.0
 */
namespace Passbolt\DirectorySync\Utility\DirectoryEntry;

use Cake\Core\Configure;
use Cake\Http\Exception\NotFoundException;

class UserCollection
{
    /**
     * @var array<string, \Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry>
     */
    private $users = [];

    /**
     * Add new user to the collection.
     *
     * @param string $offset Offset.
     * @param \Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry $user User entry object.
     * @return void
     */
    public function add(string $offset, UserEntry $user): void
    {
        $this->users[$this->transformOffset($offset)] = $user;
    }

    /**
     * Returns all users as an array.
     *
     * @return array<string, \Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry>
     */
    public function getAll()
    {
        return $this->users;
    }

    /**
     * Returns given offset's value from the collection.
     *
     * @param string $offset Offset.
     * @return \Passbolt\DirectorySync\Utility\DirectoryEntry\UserEntry
     */
    public function get(string $offset): UserEntry
    {
        if (!isset($this->users[$this->transformOffset($offset)])) {
            throw new NotFoundException('Offset does not exist in UserCollection');
        }

        return $this->users[$this->transformOffset($offset)];
    }

    /**
     * @param string $offset Offset to find.
     * @return bool
     */
    public function has(string $offset): bool
    {
        return isset($this->users[$this->transformOffset($offset)]);
    }

    /**
     * Check if collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->users);
    }

    /**
     * Transform offset/key in a way that it works in case-insensitive manner.
     *
     * @param string $offset Offset.
     * @return string
     */
    public function transformOffset(string $offset): string
    {
        return Configure::read('passbolt.plugins.directorySync.caseSensitiveFilters') ?
            $offset : mb_strtolower($offset);
    }
}
