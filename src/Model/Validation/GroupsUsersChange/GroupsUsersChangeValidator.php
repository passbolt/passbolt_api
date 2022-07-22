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
 * @since         3.7.0
 */

namespace App\Model\Validation\GroupsUsersChange;

use Cake\Validation\Validator;

class GroupsUsersChangeValidator extends Validator
{
    /**
     * GroupsUsersChangeValidator constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->uuid('id', __('The identifier should be a valid UUID.'));

        $this->uuid('user_id', __('The user identifier should be a valid UUID.'));

        $this->boolean('delete', __('The delete status should be a valid boolean.'));

        $this->boolean('is_admin', __('The delete status should be a valid boolean.'));
    }

    /**
     * Check if a change could be relative to an addition
     *
     * @param array $change The group user change
     * @return bool
     */
    public static function isAddChange(array $change): bool
    {
        return !isset($change['id'])
            && isset($change['user_id'])
            && !isset($change['delete']);
    }

    /**
     * Check if a change could be relative to an update
     *
     * @param array $change The group user change
     * @return bool
     */
    public static function isUpdateChange(array $change): bool
    {
        return isset($change['id'])
            && !isset($change['user_id'])
            && isset($change['is_admin'])
            && !isset($change['delete']);
    }

    /**
     * Check if a change could be relative to a deletion
     *
     * @param array $change The group user change
     * @return bool
     */
    public static function isDeleteChange(array $change): bool
    {
        return isset($change['id'])
            && !isset($change['user_id'])
            && !isset($change['is_admin'])
            && isset($change['delete']);
    }
}
