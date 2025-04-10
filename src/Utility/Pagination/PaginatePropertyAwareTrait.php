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
 * @since         5.0.0
 */
namespace App\Utility\Pagination;

use Cake\Utility\Hash;

/**
 * Helper trait to get/set paginate property for controller.
 * This is used to keep manipulate property `$paginate` property with CakePHP v5.
 */
trait PaginatePropertyAwareTrait
{
    /**
     * Returns value from `$paginate` property of the controller.
     *
     * @param string $keyPath The path being searched for.
     * @return mixed
     */
    public function getPaginateValue(string $keyPath): mixed
    {
        return Hash::get($this->paginate, $keyPath);
    }

    /**
     * Sets value in `$paginate` property of the controller.
     *
     * @param string $path The path to insert at.
     * @param mixed $value The value to set.
     * @return void
     */
    public function setPaginateValue(string $path, mixed $value): void
    {
        $this->paginate = Hash::insert($this->paginate, $path, $value);
    }
}
