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
 * @since         3.5.0
 */

namespace App\Service\Users;

use App\Model\Entity\User;
use App\Utility\UserAccessControl;
use Cake\View\ViewBuilder;

interface UserRecoverServiceInterface
{
    /**
     * Account recovery reasons
     */
    public const ACCOUNT_RECOVERY_CASE_LOST_PASSPHRASE = 'lost-passphrase';
    public const ACCOUNT_RECOVERY_CASE_DEFAULT = 'default';
    public const ACCOUNT_RECOVERY_CASES = [
        self::ACCOUNT_RECOVERY_CASE_DEFAULT,
        self::ACCOUNT_RECOVERY_CASE_LOST_PASSPHRASE,
    ];

    /**
     * @param \App\Utility\UserAccessControl $uac UAC performing the recovery
     * @return \App\Model\Entity\User
     * @throws \Cake\Http\Exception\InternalErrorException if the self registration settings in the DB are not valid
     */
    public function recover(UserAccessControl $uac): User;

    /**
     * @param \Cake\View\ViewBuilder $viewBuilder View builder
     * @return void
     */
    public function setTemplate(ViewBuilder $viewBuilder): void;
}
