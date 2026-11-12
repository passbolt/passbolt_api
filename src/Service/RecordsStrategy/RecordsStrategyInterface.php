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
 * @since         5.16.0
 */

namespace App\Service\RecordsStrategy;

use App\Utility\UserAccessControl;

interface RecordsStrategyInterface
{
    /**
     * @param \App\Utility\UserAccessControl $uac user performing the operation
     * @return void
     * @throws \Cake\Http\Exception\HttpException if not allowed
     */
    public function assertAllowed(UserAccessControl $uac): void;
}
