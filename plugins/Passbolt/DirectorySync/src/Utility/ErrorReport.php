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
 * Error Report Class
 * @package App\Utility
 */
class ErrorReport extends ActionReport
{
    /**
     * ErrorReport constructor.
     *
     * @param string $model Users, Groups, GroupsUsers
     * @param string $action SyncAction::CREATE, SyncAction::DELETE, SyncAction::UPDATE
     * @param object $data Entity, Exception
     * @throws InvalidArgumentException
     */
    public function __construct(string $model, string $action, $data)
    {
        parent::__construct($model, $action, SyncAction::ERROR, $data);
    }
}