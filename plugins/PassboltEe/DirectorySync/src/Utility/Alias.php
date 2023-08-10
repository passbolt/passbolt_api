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
namespace Passbolt\DirectorySync\Utility;

class Alias
{
    public const STATUS_SUCCESS = 'success';
    public const STATUS_IGNORE = 'ignore';
    public const STATUS_ERROR = 'error';
    public const STATUS_SYNC = 'sync';

    public const MODEL_USERS = 'Users';
    public const MODEL_GROUPS = 'Groups';
    public const MODEL_GROUPS_USERS = 'GroupsUsers';
    public const MODEL_DIRECTORY_ENTRIES = 'DirectoryEntries';

    public const ACTION_CREATE = 'create';
    public const ACTION_DELETE = 'delete';
    public const ACTION_UPDATE = 'update';
    public const ACTION_SYNC = 'update';
}
