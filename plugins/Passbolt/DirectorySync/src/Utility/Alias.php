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

class Alias
{
    const STATUS_SUCCESS = 'success';
    const STATUS_IGNORE = 'ignore';
    const STATUS_ERROR = 'error';
    const STATUS_SYNC = 'sync';

    const MODEL_USERS = 'Users';
    const MODEL_GROUPS = 'Groups';
    const MODEL_GROUPS_USERS = 'GroupsUsers';
    const MODEL_DIRECTORY_ENTRIES = 'DirectoryEntries';

    const ACTION_CREATE = 'create';
    const ACTION_DELETE = 'delete';
    const ACTION_UPDATE = 'update';
    const ACTION_SYNC = 'update';
}
